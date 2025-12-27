<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paper;
use Illuminate\Http\Request;
use App\Models\PreparedEmail;
use App\Mail\ReviewerResponseMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewerController extends Controller
{
    public function index()
    {
        $reviewer = Auth::user();

        // Ambil semua papers yang di-assign ke reviewer ini dengan status dari pivot table
        $papers = Paper::whereHas('reviewers', function ($query) use ($reviewer) {
            $query->where('user_id', $reviewer->id);
        })
            ->with(['authors', 'reviewers' => function ($query) use ($reviewer) {
                $query->where('user_id', $reviewer->id);
            }])
            ->get()
            ->map(function ($paper) use ($reviewer) {
                // Ambil pivot data untuk reviewer ini
                $pivot = DB::table('paper_reviewer')
                    ->where('paper_id', $paper->id)
                    ->where('user_id', $reviewer->id)
                    ->first();

                // Normalisasi status ke lowercase dan mapping ke format yang diharapkan
                $status = strtolower($pivot->status ?? 'assigned');

                // Mapping status yang mungkin berbeda format
                $statusMap = [
                    'assigned' => 'assigned',
                    'accepted' => 'accept_to_review',
                    'accept_to_review' => 'accept_to_review',
                    'completed' => 'completed',
                    'declined' => 'decline_to_review',
                    'decline_to_review' => 'decline_to_review',
                ];

                // Tambahkan review_status dan review_deadline dari pivot
                $paper->review_status = $statusMap[$status] ?? 'assigned';
                $paper->review_deadline = $pivot->deadline ?? null;

                return $paper;
            });

        // Hitung statistik berdasarkan status
        $stats = [
            'pending' => $papers->where('review_status', 'assigned')->count(),
            'in_progress' => $papers->where('review_status', 'accept_to_review')->count(),
            'completed' => $papers->where('review_status', 'completed')->count(),
            'declined' => $papers->where('review_status', 'decline_to_review')->count(),
            'total' => $papers->count(),
        ];

        return view('reviewer', [
            'papers' => $papers,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the invitation page for a reviewer
     */
    public function showInvitation($token)
    {
        // Find the paper_reviewer record by token
        $invitation = DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->first();

        if (!$invitation) {
            abort(404, 'Invitation not found or has expired.');
        }

        $paper = Paper::with('authors')->findOrFail($invitation->paper_id);
        $reviewer = User::findOrFail($invitation->user_id);

        return view('reviewer.invitation', [
            'paper' => $paper,
            'reviewer' => $reviewer,
            'deadline' => $invitation->deadline,
            'status' => $invitation->status,
            'token' => $token,
        ]);
    }

    /**
     * Accept the review invitation
     */
    public function acceptInvitation(Request $request, $token)
    {
        $invitation = DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->first();

        if (!$invitation) {
            return redirect()->route('login')->with('error', 'Invalid invitation token.');
        }

        // Update status to accepted
        DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->update(['status' => 'accepted']);

        return redirect()->route('login')->with('success', 'You have accepted the review invitation. Please login to continue reviewing the manuscript.');
    }

    /**
     * Decline the review invitation
     */
    public function declineInvitation(Request $request, $token)
    {
        $invitation = DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->first();

        if (!$invitation) {
            return redirect()->route('login')->with('error', 'Invalid invitation token.');
        }

        // Update status to declined
        DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->update(['status' => 'declined']);

        return redirect()->route('login')->with('info', 'You have declined the review invitation. Thank you for your response.');
    }

    /**
     * Accept review assignment (from dashboard)
     */
    public function acceptReview(Paper $paper)
    {
        $reviewer = Auth::user();

        // Update status to accept_to_review
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->update(['status' => 'accept_to_review']);

        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->first();

        $assigned_by = User::find($pivot->assigned_by); // sesuaikan jika relasi berbeda

        $email = $this->renderEmailTemplate('review_acceptance', [
            'assignedBy'   => trim($assigned_by->first_name . ' ' . $assigned_by->last_name),
            'reviewerName' => trim($reviewer->first_name . ' ' . $reviewer->last_name),
            'articleTitle' => $paper->judul,
        ]);

        Mail::to($assigned_by->email)->send(
            new ReviewerResponseMail($email['subject'], $email['body'])
        );

        return redirect()->route('reviewer.index')->with('success', 'Review accepted successfully.');
    }

    /**
     * Decline review assignment (from dashboard)
     */
    public function declineReview(Paper $paper)
    {
        $reviewer = Auth::user();

        // Update status to decline_to_review
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->update(['status' => 'decline_to_review']);

        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->first();

        $assigned_by = User::find($pivot->assigned_by); // sesuaikan jika relasi berbeda

        $email = $this->renderEmailTemplate('review_decline', [
            'assignedBy'   => trim($assigned_by->first_name . ' ' . $assigned_by->last_name),
            'reviewerName' => trim($reviewer->first_name . ' ' . $reviewer->last_name),
            'articleTitle' => $paper->judul,
        ]);

        Mail::to($assigned_by->email)->send(
            new ReviewerResponseMail($email['subject'], $email['body'])
        );

        return redirect()->route('reviewer.index')->with('info', 'Review declined.');
    }

    /**
     * Show review form
     */
    public function showReviewForm(Paper $paper)
    {
        $reviewer = Auth::user();

        // Verifikasi bahwa paper ini di-assign ke reviewer ini
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->first();

        if (!$pivot) {
            abort(403, 'You are not assigned to review this paper.');
        }

        // Load paper dengan authors
        $paper->load('authors');

        return view('review', [
            'paper' => $paper,
            'deadline' => $pivot->deadline ?? now()->addDays(14),
            'draft' => [
                'recommendation' => $pivot->recommendation ?? null,
                'comments_for_author' => $pivot->comments_for_author ?? null,
                'comments_for_editor' => $pivot->comments_for_editor ?? null,
                'Q1' => $pivot->Q1 ?? null,
                'Q2' => $pivot->Q2 ?? null,
                'Q3' => $pivot->Q3 ?? null,
                'review_file' => $pivot->review_file ?? null,
                'review_file_name' => $pivot->review_file_name ?? null,
            ],
        ]);
    }

    /**
     * Submit review
     */
    public function submitReview(Request $request, Paper $paper)
    {
        $reviewer = Auth::user();

        // Verifikasi bahwa paper ini di-assign ke reviewer ini
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->first();

        if (!$pivot) {
            abort(403, 'You are not assigned to review this paper.');
        }

        // Validasi request - semua required untuk submit final
        $request->validate([
            'recommendation' => 'required|string',
            'comments_for_author' => 'required|string',
            'comments_for_editor' => 'nullable|string',
            'review_file' => 'nullable|file|mimes:doc,docx,pdf|max:51200', // 50MB max
            'Q1' => 'required|string',
            'Q2' => 'required|string',
            'Q3' => 'required|string',
        ]);

        // Handle file upload jika ada
        $updateData = [
            'status' => 'completed',
            'recommendation' => $request->recommendation,
            'comments_for_author' => $request->comments_for_author,
            'comments_for_editor' => $request->comments_for_editor,
            'Q1' => $request->Q1,
            'Q2' => $request->Q2,
            'Q3' => $request->Q3,
            'reviewed_at' => now(),
            'updated_at' => now(),
        ];

        // Handle file upload jika ada
        if ($request->hasFile('review_file')) {
            // Hapus file lama jika ada
            if ($pivot->review_file) {
                $oldFilePath = storage_path('app/public/' . $pivot->review_file);
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            $file = $request->file('review_file');
            // Jika multiple files, ambil file pertama
            if (is_array($file)) {
                $file = $file[0];
            }
            $updateData['review_file'] = $file->store('review_files', 'public');
            $updateData['review_file_name'] = $file->getClientOriginalName();
        }

        // Update pivot table dengan review data
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->update($updateData);

        return redirect()->route('reviewer.index')->with('success', 'Review submitted successfully.');
    }

    /**
     * Save draft review
     */
    public function saveDraft(Request $request, Paper $paper)
    {
        $reviewer = Auth::user();

        // Verifikasi bahwa paper ini di-assign ke reviewer ini
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $reviewer->id)
            ->first();

        if (!$pivot) {
            abort(403, 'You are not assigned to review this paper.');
        }

        // Validasi request (semua optional untuk draft)
        $request->validate([
            'recommendation' => 'nullable|string',
            'comments_for_author' => 'nullable|string',
            'comments_for_editor' => 'nullable|string',
            'review_file' => 'nullable|file|mimes:doc,docx,pdf|max:51200', // 50MB max
            'Q1' => 'nullable|string',
            'Q2' => 'nullable|string',
            'Q3' => 'nullable|string',
        ]);

        // Handle file upload jika ada
        $updateData = [];

        if ($request->has('recommendation') && $request->recommendation) {
            $updateData['recommendation'] = $request->recommendation;
        }

        if ($request->has('comments_for_author') && $request->comments_for_author) {
            $updateData['comments_for_author'] = $request->comments_for_author;
        }

        if ($request->has('comments_for_editor') && $request->comments_for_editor) {
            $updateData['comments_for_editor'] = $request->comments_for_editor;
        }

        if ($request->has('Q1') && $request->Q1) {
            $updateData['Q1'] = $request->Q1;
        }

        if ($request->has('Q2') && $request->Q2) {
            $updateData['Q2'] = $request->Q2;
        }

        if ($request->has('Q3') && $request->Q3) {
            $updateData['Q3'] = $request->Q3;
        }

        // Handle file upload jika ada
        if ($request->hasFile('review_file')) {
            // Hapus file lama jika ada
            if ($pivot->review_file) {
                $oldFilePath = storage_path('app/public/' . $pivot->review_file);
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            $file = $request->file('review_file');
            // Jika multiple files, ambil file pertama saja
            if (is_array($file)) {
                $file = $file[0];
            }
            $updateData['review_file'] = $file->store('review_files', 'public');
            $updateData['review_file_name'] = $file->getClientOriginalName();
        }

        // Update status tetap accept_to_review (in review), tidak completed
        // Hanya update status jika ada perubahan data
        if (!empty($updateData)) {
            $updateData['status'] = 'accept_to_review';
            $updateData['updated_at'] = now();

            // Update pivot table dengan draft data
            DB::table('paper_reviewer')
                ->where('paper_id', $paper->id)
                ->where('user_id', $reviewer->id)
                ->update($updateData);
        }

        // Jika request via AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully.'
            ]);
        }

        // Redirect ke dashboard reviewer
        return redirect()->route('reviewer.index')->with('success', 'Draft saved successfully.');
    }

    // Helper to render email template with data
    private function renderEmailTemplate($templateCode, array $data)
    {
        $email = PreparedEmail::where('email_template', $templateCode)
            ->firstOrFail();

        $subject = $email->subject;
        $body    = $email->body;

        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
            $body    = str_replace('{{' . $key . '}}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body'    => nl2br(e($body))
        ];
    }
}
