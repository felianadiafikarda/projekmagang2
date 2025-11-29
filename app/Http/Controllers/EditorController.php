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
        // Ambil apakah page=list atau page=assign
        $page = $request->get('page', 'list');

        // ========= LIST PAGE ==========
        if ($page === 'list') {

            // Ambil semua paper + authors
            $papers = Paper::with(['authors'])
                        ->orderBy('created_at', 'desc')
                        ->get();

            // Ambil semua editor
            $editors = User::whereHas('roles', function($q) {
                $q->where('name', 'editor'); // atau 'section_editor' sesuai kebutuhan
            })->get();

            return view('editor', [
                'page'     => $page,
                'papers'   => $papers,
                'editors'  => $editors,
                'paper'    => null,   // ← WAJIB TAMBAH INI
                'articleUrl' => null, // opsional
            ]);
            
        }

        // ========= ASSIGN REVIEWER PAGE ==========
        if ($page === 'assign') {

            $id = $request->get('id');
            $paper = Paper::with(['authors'])->findOrFail($id);

            // URL artikel untuk JS
            $articleUrl = $paper->file_path ? asset('storage/' . $paper->file_path) : '#';

            // Reviewer list
            $all_reviewers = User::whereHas('roles', function($q) {
                $q->where('name', 'reviewer');
            })->get();
            
            // Section editor list
            $all_section_editors = User::whereHas('roles', function($q) {
                $q->where('name', 'section_editor');
            })->get();
            
            // Assigned reviewers dan section editors
            $assignedReviewers = $paper->reviewers()->get(); 
            $assignedSectionEditors = $paper->sectionEditors()->get();

            // Editor yang muncul di JS (misal sebagai pengirim email)
            $editors = $all_section_editors;

            return view('editor', [
                'page'                  => 'assign',
                'paper'                 => $paper,
                'articleUrl'            => $articleUrl,
                'all_reviewers'         => $all_reviewers,
                'all_section_editors'   => $all_section_editors,
                'assignedReviewers'     => $assignedReviewers,
                'assignedSectionEditors'=> $assignedSectionEditors,
                'editors'               => $editors,
                'modalType' => 'assign',
            ]);
        }

        // Default redirect
        return redirect()->back();
    }

    public function detail($id)
    {
        $paper = Paper::with(['authors', 'reviewers', 'sectionEditors'])->findOrFail($id);

        $assignedReviewers = $paper->reviewers()->get();
        $assignedSectionEditors = $paper->sectionEditors()->get();

        return view('editorDetail', compact(
            'paper',
            'assignedReviewers',
            'assignedSectionEditors'
        ));
    }




    public function assignReviewers(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewers' => 'required|array',
            'deadline' => 'required|date',
            'send_email' => 'required|boolean'
        ]);

        $reviewerIds = $request->reviewers; 
        $deadline = $request->deadline;
        $sendEmail = $request->send_email;

        // Assign reviewers ke pivot table (misal paper_reviewer)
        $paper->reviewers()->syncWithoutDetaching(
            collect($reviewerIds)->mapWithKeys(fn($id) => [$id => ['deadline' => $deadline, 'status'=>'assigned']])->toArray()
        );

        if ($sendEmail) {
            $reviewers = User::whereIn('id', $reviewerIds)->get();
            foreach ($reviewers as $rev) {
                // Kirim nama editor pertama sebagai pengirim
                $editorName = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        
                Mail::to($rev->email)->send(new ReviewerAssignmentMail($paper, $rev, $deadline, $editorName));
            }
        }

        return redirect()->back()->with('success', 'Reviewer assigned successfully!');
    }

    public function sendReminder(Request $request, $paperId)
    {
        // Validasi minimal
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        // Ambil paper sebagai model (pastikan ini instance App\Models\Paper)
        $paper = Paper::findOrFail($paperId);

        // Ambil reviewer
        $reviewer = User::findOrFail($request->reviewer_id);

        // Nama editor pengirim
        $editorName = auth()->user()
                        ? (trim(auth()->user()->first_name . ' ' . auth()->user()->last_name))
                        : 'Editor';

        // Kirim email (gunakan Mailable yang menerima Paper & User)
        Mail::to($reviewer->email)->send(new ReviewerReminderMail($paper, $reviewer, $editorName));

        return back()->with('success', 'Reminder sent!');
    }

    public function unassignReviewer(Request $request, Paper $paper)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id',
        ]);

        // Hapus reviewer dari pivot table
        $paper->reviewers()->detach($request->reviewer_id);

        return back()->with('success', 'Reviewer unassigned successfully!');
    }

    public function assignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);

        // list editor yg dipilih
        $editorIds = $request->section_editors ?? [];

        // sync = update + delete + add
        $paper->sectionEditors()->sync($editorIds);

        return back()->with('success', 'Section editor updated!');
    }

    public function unassignSectionEditor(Request $request, $paperId)
    {
        $paper = Paper::findOrFail($paperId);

        $editorId = $request->editor_id;

        $paper->sectionEditors()->detach($editorId);

        return back()->with('success', 'Section editor removed!');
    }


}