<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Paper;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Mail\ReviewerReminderMail;
use Illuminate\Support\Facades\DB;
use App\Mail\ReviewerAssignmentMail;
use App\Mail\SectionEditorAssignmentMail;
use Illuminate\Support\Facades\Mail;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 'list');

        if ($page === 'list') {

            $papers = Paper::with(['authors'])
                ->orderBy('created_at', 'desc')
                ->get();

            $editors = User::whereHas('roles', function($q) {
                $q->where('name', 'editor');
            })->get();

            return view('editor', [
                'page'     => $page,
                'papers'   => $papers,
                'editors'  => $editors,
                'paper'    => null,
                'articleUrl' => null,
            ]);
        }

        if ($page === 'assign') {

            $id = $request->get('id');
            $paper = Paper::with(['authors'])->findOrFail($id);

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
            ->get();


            return view('editor', [
                'page'                  => 'assign',
                'paper'                 => $paper,
                'articleUrl'            => $articleUrl,
                'all_reviewers'         => $all_reviewers,
                'all_section_editors'   => $all_section_editors,
                'assignedReviewers'     => $paper->reviewers,
                'assignedSectionEditors'=> $paper->sectionEditors,
                'editors'               => $all_section_editors,
                'modalType'             => 'assign',
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
            'assignedSectionEditors'=> $paper->sectionEditors
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

        // Generate tokens for each reviewer and add to pivot
        $pivotData = [];
        foreach ($reviewerIds as $id) {
            $token = Str::uuid()->toString();
            $pivotData[$id] = [
                'deadline' => $deadline,
                'status' => 'assigned',
                'invitation_token' => $token
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
                        $emailBody,
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
                $emailBody
            )
        );

        return back()->with('success', 'Reminder sent!');
    }


    public function unassignReviewer(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

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

        foreach ($editors as $editor) {
            Mail::to($editor->email)->send(
                new SectionEditorAssignmentMail(
                    $paper,
                    $editor,
                    $editorName,
                    $subject,
                    $emailBody
                )
            );
        }
    }

    return back()->with('success', 'Section editor assigned & email sent!');
}

    
    public function unassignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);
        $paper->sectionEditors()->detach($request->editor_id);

        return back()->with('success', 'Section editor removed!');
    }
}