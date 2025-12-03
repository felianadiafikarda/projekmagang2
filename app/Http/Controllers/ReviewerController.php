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
        return view('reviewer');
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

        return redirect()->route('login')->with('info', 'You have declined the review invitation. Thank you for your response.');
    }
}
