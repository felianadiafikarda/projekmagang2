<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Paper;
use App\Models\User;

class ReviewerController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        
        // Ambil semua paper yang di-assign ke reviewer ini
        $assignedPapers = Paper::with(['authors'])
            ->whereHas('reviewers', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->get()
            ->map(function($paper) use ($currentUser) {
                // Ambil data pivot untuk reviewer ini
                $pivot = DB::table('paper_reviewer')
                    ->where('paper_id', $paper->id)
                    ->where('user_id', $currentUser->id)
                    ->first();
                
                $paper->review_status = $pivot->status ?? 'assigned';
                $paper->review_deadline = $pivot->deadline ?? null;
                $paper->invitation_token = $pivot->invitation_token ?? null;
                
                return $paper;
            });

        // Hitung statistik
        $stats = [
            'pending' => $assignedPapers->where('review_status', 'assigned')->count(),
            'in_progress' => $assignedPapers->where('review_status', 'accept_to_review')->count(),
            'completed' => $assignedPapers->where('review_status', 'completed')->count(),
            'declined' => $assignedPapers->where('review_status', 'decline_to_review')->count(),
            'total' => $assignedPapers->count(),
        ];

        return view('reviewer', [
            'papers' => $assignedPapers,
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

        // Update status to accept_to_review
        DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->update(['status' => 'accept_to_review']);

        DB::table('notifications')
            ->where('type', 'invite_review')
            ->where('data->token', $token)  
            ->update([
                'status' => 'accepted',
                'is_read' => true,
                'updated_at' => now(),
            ]);


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

        // Update status to decline_to_review
        DB::table('paper_reviewer')
            ->where('invitation_token', $token)
            ->update(['status' => 'decline_to_review']);

        DB::table('notifications')
            ->where('type', 'invite_review')
            ->where('data->token', $token)
            ->update([
                'status' => 'declined',
                'is_read' => true,
                'updated_at' => now(),
            ]);


        return redirect()->route('login')->with('info', 'You have declined the review invitation. Thank you for your response.');
    }

    /**
     * Accept review from dashboard
     */
    public function acceptReview(Paper $paper)
    {
        $currentUser = auth()->user();
        
        // Get invitation token if exists
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->first();
        
        // Update status
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->update(['status' => 'accept_to_review']);

        // Update notification if exists
        if ($pivot && $pivot->invitation_token) {
            DB::table('notifications')
                ->where('type', 'invite_review')
                ->where('data->token', $pivot->invitation_token)
                ->update([
                    'status' => 'accepted',
                    'is_read' => true,
                    'updated_at' => now(),
                ]);
        }

        return back()->with('success', 'Review accepted successfully! You can now start reviewing the manuscript.');
    }

    /**
     * Decline review from dashboard
     */
    public function declineReview(Paper $paper)
    {
        $currentUser = auth()->user();
        
        // Get invitation token if exists
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->first();
        
        // Update status
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->update(['status' => 'decline_to_review']);

        // Update notification if exists
        if ($pivot && $pivot->invitation_token) {
            DB::table('notifications')
                ->where('type', 'invite_review')
                ->where('data->token', $pivot->invitation_token)
                ->update([
                    'status' => 'declined',
                    'is_read' => true,
                    'updated_at' => now(),
                ]);
        }

        return back()->with('info', 'Review declined. Thank you for your response.');
    }

    /**
     * Show review form
     */
    public function showReviewForm(Paper $paper)
    {
        $currentUser = auth()->user();
        
        // Pastikan reviewer ini di-assign ke paper ini
        $pivot = DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->first();

        if (!$pivot) {
            abort(403, 'You are not assigned to review this paper.');
        }

        $paper->load('authors');

        return view('review', [
            'paper' => $paper,
            'deadline' => $pivot->deadline,
            'status' => $pivot->status,
        ]);
    }

    /**
     * Submit review
     */
    public function submitReview(Request $request, Paper $paper)
    {
        $request->validate([
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
            'comments_for_author' => 'required|string',
            'comments_for_editor' => 'nullable|string',
            'review_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $currentUser = auth()->user();

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('review_file')) {
            $filePath = $request->file('review_file')->store('reviews', 'public');
        }

        // Update paper_reviewer pivot
        DB::table('paper_reviewer')
            ->where('paper_id', $paper->id)
            ->where('user_id', $currentUser->id)
            ->update([
                'status' => 'completed',
                'recommendation' => $request->recommendation,
                'comments_for_author' => $request->comments_for_author,
                'comments_for_editor' => $request->comments_for_editor,
                'review_file' => $filePath,
                'reviewed_at' => now(),
            ]);

        return redirect()->route('reviewer.index')->with('success', 'Review submitted successfully! Thank you for your contribution.');
    }
}
