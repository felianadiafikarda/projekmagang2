<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Paper;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\PreparedEmail;
use App\Mail\PaperDecisionMail;
use App\Mail\ReviewerReminderMail;
use App\Mail\ReviewerResponseMail;
use Illuminate\Support\Facades\DB;
use App\Mail\ReviewerAssignmentMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SectionEditorAssignmentMail;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 'list');
        $filterStatus = $request->get('filter_status', '');
        $search = $request->get('search', '');
        $papers = Paper::with(['authors', 'reviewers', 'revisions'])
            ->when($search, function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->get();


        // Filter berdasarkan dropdown status
        if ($filterStatus) {
            $papers = $papers->filter(fn($p) => $p->display_status === $filterStatus);
        }

        $editors = User::whereHas('roles', function ($q) {
            $q->where('name', 'editor');
        })->get();

        if ($page === 'list') {

            $paper = Paper::with(['authors', 'reviewers'])
                ->orderBy('created_at', 'desc')
                ->get();

            $editors = User::whereHas('roles', function ($q) {
                $q->where('name', 'editor');
            })->get();

            return view('editor', [
                'page'     => $page,
                'papers'   => $papers,
                'editors'  => $editors,
                'paper'    => null,
                'articleUrl' => null,
            ]);

            $editors = User::whereHas('roles', function ($q) {
                $q->where('name', 'editor');
            })->get();

            return view('editor', [
                'page'     => $page,
                'papers'   => $paper,
                'editors'  => $editors,
                'paper'    => null,
                'articleUrl' => null,
            ]);
        }

        if ($page === 'assign') {

            $id = $request->get('id');
            $paper = Paper::with(['authors', 'reviewers', 'sectionEditors'])->findOrFail($id);

            $articleUrl = $paper->file_path ? asset('storage/' . $paper->file_path) : '#';

            // Ambil semua user yang memiliki role reviewer dengan info jumlah paper aktif
            $assignedReviewerIds = $paper->reviewers()->pluck('users.id')->toArray();
            $all_reviewers = User::whereHas('roles', function ($q) {
                $q->where('name', 'reviewer');
            })
                ->whereNotIn('id', $assignedReviewerIds) // 🔥 DI SINI PAKAI ID
                ->get()
                ->map(function ($reviewer) {

                    $reviewer->active_papers = DB::table('paper_reviewer')
                        ->where('user_id', $reviewer->id)
                        ->whereIn('status', ['accept_to_review', 'completed'])
                        ->count();

                    return $reviewer;
                });


            // Ambil semua user yang memiliki role section_editor dengan info jumlah paper aktif
            $assignedSectionEditorIds = $paper->sectionEditors()
                ->pluck('users.id')
                ->toArray();

            $all_section_editors = User::whereHas('roles', function ($q) {
                $q->where('name', 'section_editor');
            })
                ->whereNotIn('id', $assignedSectionEditorIds)
                ->get()
                ->map(function ($se) {
                    // Hitung jumlah paper aktif (yang di-assign ke section editor ini)
                    $activePapers = DB::table('paper_section_editor')
                        ->where('user_id', $se->id)
                        ->count();

                    $se->active_papers = $activePapers;

                    return $se;
                });

            $reviews = DB::table('paper_reviewer')
                ->join('users', 'users.id', '=', 'paper_reviewer.user_id')
                ->where('paper_reviewer.paper_id', $paper->id)
                ->where('paper_reviewer.status', 'completed')
                ->select(
                    'users.first_name',
                    'users.last_name',
                    'paper_reviewer.recommendation',
                    'paper_reviewer.comments_for_author',
                    'paper_reviewer.comments_for_editor',
                    'paper_reviewer.review_file',
                    'paper_reviewer.reviewed_at'
                )
                ->orderBy('paper_reviewer.reviewed_at', 'desc')
                ->get();

            return view('editor', [
                'page'                  => 'assign',
                'paper'                 => $paper,
                'articleUrl'            => $articleUrl,
                'all_reviewers'         => $all_reviewers,
                'all_section_editors'   => $all_section_editors,
                'assignedReviewers'     => $paper->reviewers,
                'assignedSectionEditors' => $paper->sectionEditors,
                'editors'               => $all_section_editors,
                'modalType'             => 'assign',
                'reviews'               => $reviews,
            ]);
        }

        return redirect()->back();
    }

    public function detail($id)
    {
        $paper = Paper::with(['authors', 'reviewers', 'sectionEditors'])->findOrFail($id);

        return view('editorDetail', [
            'paper'                 => $paper,
            'assignedReviewers'     => $paper->reviewers,
            'assignedSectionEditors' => $paper->sectionEditors
        ]);
    }

    // ===========================
    // ASSIGN REVIEWERS + EMAIL
    // ===========================
    public function assignReviewers(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewers'   => 'required|array',
            'deadline'    => 'required|date',
            'send_email'  => 'required|boolean',
        ]);

        $reviewerIds = $request->reviewers;
        $deadline    = $request->deadline;
        $sendEmail   = $request->send_email;
        $assignedBy = auth()->id();

        // Generate tokens for each reviewer and add to pivot
        $pivotData = [];
        foreach ($reviewerIds as $id) {
            $token = Str::uuid()->toString();
            $pivotData[$id] = [
                'deadline' => $deadline,
                'status' => 'assigned',
                'invitation_token' => $token,
                'assigned_by' => $assignedBy
            ];
        }

        // Tambahkan reviewer ke pivot with tokens
        $paper->reviewers()->syncWithoutDetaching($pivotData);


        // Tambahkan role reviewer ke user yang di-assign (jika belum punya)
        $reviewerRole = Role::where('name', 'reviewer')->first();
        if ($reviewerRole) {
            foreach ($reviewerIds as $userId) {
                $user = User::find($userId);
                if ($user && !$user->roles->contains($reviewerRole->id)) {
                    $user->roles()->attach($reviewerRole->id);
                }
            }
        }

        foreach ($reviewerIds as $revId) {
            Notification::create([
                'user_id'  => $revId,
                'title'    => 'Invitation to Review',
                'message'  => 'You are invited to review the manuscript: "' . $paper->judul . '"',
                'type'     => 'invite_review',   // khusus invitation
                'status'   => 'pending',         // reviewer belum accept/decline
                'data'     => [
                    'paper_id'      => $paper->id,
                    'paper_title'   => $paper->judul,
                    'deadline'      => $deadline,
                    'invited_by'    => auth()->id(),
                    'token'         => $pivotData[$revId]['invitation_token']
                ],
            ]);
        }

        if ($sendEmail) {

            $reviewers = User::whereIn('id', $reviewerIds)->get();
            $editorName = auth()->user()->first_name . ' ' . auth()->user()->last_name;

            foreach ($reviewers as $rev) {

                // Get the token for this reviewer
                $invitationToken = $pivotData[$rev->id]['invitation_token'];
                $invitationUrl = route('reviewer.invitation', $invitationToken);

                // SUBJECT
                $subject = trim($request->subject) !== ''
                    ? $request->subject
                    : 'Invitation to Review Manuscript';

                // BODY
                $emailBody = trim($request->email_body);

                $emailBodyHtml = nl2br(e($emailBody));

                if ($emailBody === '') {
                    // fallback template - body kosong, akan diisi oleh template
                    $emailBody = '';
                }

                Mail::to($rev->email)->send(
                    new ReviewerAssignmentMail(
                        $paper,
                        $rev,
                        $deadline,
                        $editorName,
                        $subject,
                        $emailBodyHtml,
                        $invitationUrl
                    )
                );
            }
        }

        return back()->with('success', 'Reviewer assigned successfully!');
    }


    // ===========================
    // SEND REMINDER
    // ===========================
    public function sendReminder(Request $request, $paperId)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        $paper = Paper::findOrFail($paperId);
        $reviewer = User::findOrFail($request->reviewer_id);

        $editorName = auth()->user()
            ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name)
            : 'Editor';

        // SUBJECT
        $subject = trim($request->subject) !== ''
            ? $request->subject
            : 'Review Reminder';

        // BODY
        $emailBody = trim($request->email_body);

        if ($emailBody === '') {
            $deadline = $paper->reviewers()
                ->where('user_id', $reviewer->id)
                ->first()
                ->pivot
                ->deadline ?? '-';

            $emailBody = view('emails.reviewer_reminder', [
                'reviewerName' => $reviewer->first_name . ' ' . $reviewer->last_name,
                'articleTitle' => $paper->judul,
                'deadline'     => $deadline,
                'editorName'   => $editorName
            ])->render();
        }

        Mail::to($reviewer->email)->send(
            new ReviewerReminderMail(
                $paper,
                $reviewer,
                $editorName,
                $subject,
                $emailBody,
                $request->input('email_body') ?? '',
            )
        );

        return back()->with('success', 'Reminder sent!');
    }


    public function unassignReviewer(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        $editor   = auth()->user();
        $reviewer = User::findOrFail($request->reviewer_id);

        // ================= SEND EMAIL: REVIEW REQUEST CANCELLED =================

        $template = PreparedEmail::where('email_template', 'review_request_cancelled')->first();

        if ($template && $reviewer->email) {

            $body = $template->body;
            $body = str_replace('{{reviewerName}}', $reviewer->full_name, $body);
            $body = str_replace('{{articleTitle}}', $paper->judul, $body);
            $body = str_replace('{{assignedBy}}', $editor->full_name, $body);

            // ubah newline ke HTML <br>
            $bodyHtml = nl2br(e($body));

            Mail::to($reviewer->email)->send(
                new ReviewerResponseMail(
                    $template->subject,
                    $bodyHtml
                )
            );
        }

        $paper->reviewers()->detach($request->reviewer_id);

        return back()->with('success', 'Reviewer unassigned successfully!');
    }


    public function assignSectionEditorWithEmail(Request $request, $paperId)
    {
        $request->validate([
            'section_editors' => 'required|array',
            'send_email'      => 'required|boolean',
        ]);

        $paper     = Paper::findOrFail($paperId);
        $editorIds = $request->section_editors;
        $sendEmail = $request->send_email;

        // Sync section editor
        $paper->sectionEditors()->sync($editorIds);

        // Assign role
        $role = Role::where('name', 'section_editor')->first();
        if ($role) {
            foreach ($editorIds as $id) {
                $user = User::find($id);
                if ($user && !$user->roles->contains($role->id)) {
                    $user->roles()->attach($role->id);
                }
            }
        }

        // Notification
        foreach ($editorIds as $id) {
            Notification::create([
                'user_id' => $id,
                'title'   => 'Section Editor Assignment',
                'message' => 'You are assigned as section editor for "' . $paper->judul . '"',
                'type'    => 'assign_section_editor',
            ]);
        }

        // ================= EMAIL =================
        if ($sendEmail) {
            $editors     = User::whereIn('id', $editorIds)->get();
            $editorName  = auth()->user()->first_name . ' ' . auth()->user()->last_name;

            $subject = trim($request->subject) !== ''
                ? $request->subject
                : 'Assignment as Section Editor';

            $emailBody = trim($request->email_body);
            $emailBodyHtml = nl2br(e($emailBody));

            foreach ($editors as $editor) {
                Mail::to($editor->email)->send(
                    new SectionEditorAssignmentMail(
                        $paper,
                        $editor,
                        $editorName,
                        $subject,
                        $emailBodyHtml,

                    )
                );
            }
        }

        return back()->with('success', 'Section editor assigned & email sent!');
    }

    protected function isPaperRevised(Paper $paper)
    {
        // Cek apakah ada histori revisi di tabel paper_revisions
        return $paper->revisions()->exists();
    }

    public function updateStatus(Request $request, $id)
    {
        $paper = Paper::with(['reviewers', 'authors'])->findOrFail($id);

        // =========================
        // Tentukan status default
        // =========================
        $newEditorStatus = $paper->editor_status;
        $newAuthorStatus = $paper->status;


        // =========================
        // Editor memberikan keputusan
        // =========================
        if ($request->has('status')) {
            switch ($request->input('status')) {
                case 'accepted':
                    $newEditorStatus = 'accepted';
                    $newAuthorStatus = 'accepted';
                    break;

                case 'accept_with_review':
                    $newEditorStatus = 'accept_with_review';
                    $newAuthorStatus = 'accept_with_review';
                    break;

                case 'rejected':
                    $newEditorStatus = 'rejected';
                    $newAuthorStatus = 'rejected';
                    break;
            }
        }

        $paper->update([
            'editor_status' => $newEditorStatus,
            'status'        => $newAuthorStatus,
        ]);


        $paper->save();

        \Log::info('Paper updated', [
            'id' => $paper->id,
            'editor_status' => $paper->editor_status,
            'status' => $paper->status
        ]);


        // =========================
        // Kirim email ke author jika dipilih
        // =========================
        if ($request->input('send_email_decision') == 1 && in_array($newEditorStatus, ['accepted', 'accept_with_review', 'rejected'])) {

            $reviewFiles = [];
            foreach ($paper->reviewers as $reviewer) {
                if ($reviewer->pivot->review_file) {
                    $reviewFiles[] = [
                        'path' => storage_path('app/public/' . $reviewer->pivot->review_file), // harus string
                        'name' => $reviewer->pivot->review_file_name ?? basename($reviewer->pivot->review_file),
                    ];
                }
            }

            // tentukan field file berdasarkan status
            if ($newEditorStatus === 'accept_with_review') {
                $fileField = 'additional_files';
                $selectedField = 'selected_new_files';
            } elseif ($newEditorStatus === 'accepted') {
                $fileField = 'additional_files_accept';
                $selectedField = 'selected_new_files_accept';
            } elseif ($newEditorStatus === 'rejected') {
                $fileField = 'additional_files_decline';
                $selectedField = 'selected_new_files_decline';
            } else {
                $fileField = null;
                $selectedField = null;
            }

            $selectedIndexes = $selectedField ? $request->input($selectedField, []) : [];

            if ($fileField && $request->hasFile($fileField) && count($selectedIndexes)) {
                foreach ($selectedIndexes as $index) {
                    if (isset($request->file($fileField)[$index])) {
                        $file = $request->file($fileField)[$index];

                        if ($file->isValid()) {
                            $path = $file->store('editor-uploads', 'public');

                            $reviewFiles[] = [
                                'path' => storage_path('app/public/' . $path), // string
                                'name' => $file->getClientOriginalName(),
                            ];
                        }
                    }
                }
            }

            foreach ($paper->authors as $author) {
                Mail::to($author->email)->send(new PaperDecisionMail(
                    $paper,
                    $author,
                    $newEditorStatus,
                    auth()->user()->name,
                    $reviewFiles,
                    $request->input('email_body') ?? ''
                ));
            }
        }
        return redirect()->route('editor.index')
            ->with('success', 'Paper status updated successfully!');
    }


    public function unassignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);
        $paper->sectionEditors()->detach($request->editor_id);


        return back()->with('success', 'Section editor removed!');
    }

    public function sendAppreciationMail(Request $request, $paperId)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
            'subject'     => 'nullable|string',
            'email_body'  => 'nullable|string',
        ]);

        $paper    = Paper::findOrFail($paperId);
        $reviewer = User::findOrFail($request->reviewer_id);

        $editorName = auth()->user()
            ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name)
            : 'Editor';

        // SUBJECT
        $subject = trim($request->subject) !== ''
            ? $request->subject
            : 'Thank You for Your Review';

        // BODY
        $emailBody = trim($request->email_body);
        $emailBodyHtml = nl2br(e($emailBody));

        Mail::to($reviewer->email)->send(
            new ReviewerResponseMail($subject, $emailBodyHtml)
        );

        return back()->with('success', 'Thank you email sent successfully.');
    }
}
