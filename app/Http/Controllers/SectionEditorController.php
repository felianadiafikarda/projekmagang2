<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Paper;
use App\Models\User;
use App\Models\Role;
use App\Mail\ReviewerAssignmentMail;
use App\Mail\ReviewerReminderMail;

class SectionEditorController extends Controller
{
    /**
     * Menampilkan halaman section editor
     * Hanya menampilkan paper yang di-assign ke section editor ini
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 'list');
        $currentUser = auth()->user();

        if ($page === 'list') {
            // Hanya ambil paper yang di-assign ke section editor ini
            $papers = Paper::with(['authors'])
                ->whereHas('sectionEditors', function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('sectionEditor', [
                'page'     => $page,
                'papers'   => $papers,
                'paper'    => null,
                'articleUrl' => null,
            ]);
        }

        if ($page === 'assign') {
            $id = $request->get('id');
            
            // Pastikan paper ini di-assign ke section editor ini
            $paper = Paper::with(['authors'])
                ->whereHas('sectionEditors', function($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id);
                })
                ->findOrFail($id);

            $articleUrl = $paper->file_path ? asset('storage/' . $paper->file_path) : '#';

            // Ambil semua user yang memiliki role reviewer dengan info jumlah paper aktif
            $all_reviewers = User::whereHas('roles', function($q) {
                $q->where('name', 'reviewer');
            })->get()->map(function($reviewer) {
                // Hitung jumlah paper aktif (yang sudah di-accept atau sedang di-review)
                $activePapers = DB::table('paper_reviewer')
                    ->where('user_id', $reviewer->id)
                    ->whereIn('status', ['accept_to_review', 'completed'])
                    ->count();
                
                // Hitung total paper yang di-assign (termasuk yang belum di-accept)
                $totalPapers = DB::table('paper_reviewer')
                    ->where('user_id', $reviewer->id)
                    ->count();
                
                $reviewer->active_papers = $activePapers;
                $reviewer->total_papers = $totalPapers;
                
                return $reviewer;
            });

            return view('sectionEditor', [
                'page'                  => 'assign',
                'paper'                 => $paper,
                'articleUrl'            => $articleUrl,
                'all_reviewers'         => $all_reviewers,
                'assignedReviewers'     => $paper->reviewers,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Assign reviewers ke paper
     */
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

        // Generate tokens dan assign reviewers
        $pivotData = [];
        foreach ($reviewerIds as $id) {
            $token = Str::uuid()->toString();
            $pivotData[$id] = [
                'deadline' => $deadline, 
                'status' => 'assigned',
                'invitation_token' => $token
            ];
        }

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

        if ($sendEmail) {
            $reviewers = User::whereIn('id', $reviewerIds)->get();
            $editorName = auth()->user()->first_name . ' ' . auth()->user()->last_name;

            foreach ($reviewers as $rev) {
                $invitationToken = $pivotData[$rev->id]['invitation_token'];
                $invitationUrl = route('reviewer.invitation', $invitationToken);

                $subject = trim($request->subject) !== ''
                    ? $request->subject
                    : 'Invitation to Review Manuscript';

                $emailBody = trim($request->email_body);

                if ($emailBody === '') {
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

    /**
     * Kirim reminder ke reviewer
     */
    public function sendReminder(Request $request, $paperId)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        $paper = Paper::findOrFail($paperId);
        $reviewer = User::findOrFail($request->reviewer_id);

        $editorName = auth()->user()
            ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name)
            : 'Section Editor';

        $subject = trim($request->subject) !== ''
            ? $request->subject
            : 'Review Reminder';

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

    /**
     * Unassign reviewer dari paper
     */
    public function unassignReviewer(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        $paper->reviewers()->detach($request->reviewer_id);

        return back()->with('success', 'Reviewer unassigned successfully!');
    }

    /**
     * Detail paper
     */
    public function detail($id)
    {
        $currentUser = auth()->user();
        
        $paper = Paper::with(['authors', 'reviewers', 'sectionEditors'])
            ->whereHas('sectionEditors', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->findOrFail($id);

        return view('sectionEditorDetail', [
            'paper'                 => $paper,
            'assignedReviewers'     => $paper->reviewers,
        ]);
    }
}