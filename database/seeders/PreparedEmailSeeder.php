<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreparedEmail;

class PreparedemailSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'email_template' => 'new_registration_notification',
                'sender' => 'system',
                'recipient' => 'author',
                'subject' => 'New Notification of Registration',
                'body' => "Dear {{authorName}},\n\nThank you for registering at {{journalName}}.\n\nLogin URL:\n{{loginUrl}}\n\nBest regards,\nEditorial Team",
            ],

            [
                'email_template' => 'invite_to_review',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Invitation to Review Manuscript',
                'body' => "Dear {{reviewerName}},\n\nWe invite you to review the manuscript entitled \"{{articleTitle}}\".\n\nSubmission URL:\n{{articleUrl}}\n\nReview Deadline: {{reviewDeadline}}\n\nBest regards,\n{{editorName}}",
            ],

            [
                'email_template' => 'review_reminder',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => '⦁	Submission Review Reminder',
                'body' => "Dear {{reviewerName}},\n\nThis is a reminder to complete your review for \"{{articleTitle}}\".\n\nDeadline: {{reviewDeadline}}\n\nBest regards,\n{{editorName}}",
            ],

            [
                'email_template' => 'review_request_cancelled',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Request for Review Cancelled',
                'body' => "Dear {{reviewerName}},\n\nThe review request for \"{{articleTitle}}\" has been cancelled.\n\nThank you for your willingness to assist.\n\nBest regards,\n{{editorName}}",
            ],

            [
                'email_template' => 'review_acceptance',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Able to Review Manuscript',
                'body' => "Dear {{editorName}},\n\nI confirm that I am able to review the manuscript \"{{articleTitle}}\".\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'review_decline',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Unable to Review Manuscript',
                'body' => "Dear {{editorName}},\n\nUnfortunately, I am unable to review the manuscript \"{{articleTitle}}\" at this time.\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'review_completed',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Review Completed',
                'body' => "Dear {{editorName}},\n\nI have completed the review for \"{{articleTitle}}\".\n\nThe review has been submitted via the system.\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'article_accepted',
                'sender' => 'editor',
                'recipient' => 'author',
                'subject' => 'Decision on Your Manuscript Submission',
                'body' => "Dear {{authorName}},\n\nWe are pleased to inform you that your manuscript \"{{articleTitle}}\" has been accepted.\n\nBest regards,\n{{editorName}}",
            ],

            [
                'email_template' => 'article_rejected',
                'sender' => 'editor',
                'recipient' => 'author',
                'subject' => 'Decision on Your Manuscript Submission',
                'body' => "Dear {{authorName}},\n\nAfter careful review, we regret to inform you that your manuscript \"{{articleTitle}}\" has not been accepted.\n\nBest regards,\n{{editorName}}",
            ],
        ];

        foreach ($templates as $template) {
            PreparedEmail::updateOrCreate(
                ['email_template' => $template['email_template']],
                $template
            );
        }
    }
}
