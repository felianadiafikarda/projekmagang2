<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Paper;
use App\Models\User;
use App\Mail\ReviewerAssignmentMail;
use App\Mail\ReviewerReminderMail;

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

            $all_reviewers = User::whereHas('roles', function($q) {
                $q->where('name', 'reviewer');
            })->get();

            $all_section_editors = User::whereHas('roles', function($q) {
                $q->where('name', 'section_editor');
            })->get();

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

        // Tambahkan reviewer ke pivot
        $paper->reviewers()->syncWithoutDetaching(
            collect($reviewerIds)->mapWithKeys(fn($id) => [
                $id => ['deadline' => $deadline, 'status' => 'assigned']
            ])->toArray()
        );

        if ($sendEmail) {

            $reviewers = User::whereIn('id', $reviewerIds)->get();
            $editorName = auth()->user()->first_name . ' ' . auth()->user()->last_name;

            foreach ($reviewers as $rev) {

                // SUBJECT
                $subject = trim($request->subject) !== ''
                    ? $request->subject
                    : 'Invitation to Review Manuscript';

                // BODY
                $emailBody = trim($request->email_body);

                if ($emailBody === '') {
                    // fallback template
                    $emailBody = view('emails.reviewer_assignment', [
                        'names'        => $rev->first_name . ' ' . $rev->last_name,
                        'articleTitle' => $paper->judul,
                        'editorName'   => $editorName,
                        'articleUrl'   => asset('storage/' . $paper->file_path),
                    ])->render();
                }

                Mail::to($rev->email)->send(
                    new ReviewerAssignmentMail(
                        $paper,
                        $rev,
                        $deadline,
                        $editorName,
                        $subject,
                        $emailBody
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


    public function assignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);
        $editorIds = $request->section_editors ?? [];

        $paper->sectionEditors()->sync($editorIds);

        return back()->with('success', 'Section editor updated!');
    }


    public function unassignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);
        $paper->sectionEditors()->detach($request->editor_id);

        return back()->with('success', 'Section editor removed!');
    }
}