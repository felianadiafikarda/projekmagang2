<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreparedEmail;

class PreparedEmailSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'email_template' => 'new_notification_registration',
                'sender' => 'system',
                'recipient' => 'user',
                'subject' => 'New Account Registration Successful',
                'body' => "Dear {{authorName}},\n\nYour account has been successfully created in our system.\n\nYou can now log in using your registered email address:\nEmail: {{email}}\n\nIf you did not create this account, please ignore this email.\n\nThank you for registering and welcome to our platform.\n\nBest regards,\nEditorial Team",
            ],

            [
                'email_template' => 'new_registration_notification',
                'sender' => 'system',
                'recipient' => 'author',
                'subject' => 'New Notification of Registration',
                'body' => "Dear {{fullName}},\n\nThank you for registering at {{journalName}}.\n\nLogin URL:\n{{loginUrl}}\n\nBest regards,\nEditorial Team",
            ],

            [
                'email_template' => 'thank_you_for_review',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Thank You for Completing the Review',
                'body' => "Dear {{reviewerName}},\n\nThank you for completing the review of the manuscript entitled\n\"{{articleTitle}}\".\n\nWe sincerely appreciate the time, effort, and expertise you have devoted\nto evaluating this submission. Your valuable comments and recommendations\nare essential in maintaining the quality and integrity of our journal.\n\nShould we require further clarification or additional review in the future,\nwe will be pleased to contact you again.\n\nThank you for your contribution and continued support.\n\nBest regards,\n{{assignedBy}}",
            ],

            [
                'email_template' => 'invite_to_review',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Invitation to Review Manuscript',
                'body' => "Dear {{reviewerName}},\n\nI believe that you would serve as an excellent reviewer of the manuscript, \"{{articleTitle}}\".\n\nPlease log into the journal web site to indicate whether you will undertake the review or not.\n\nSubmission URL:\n{{articleUrl}}\n\nReview Deadline: {{deadline}}\n\nThank you for considering this request.\n{{assignedBy}}",
            ],

            [
                'email_template' => 'assign_section_editor',
                'sender' => 'editor',
                'recipient' => 'section_editor',
                'subject' => 'Assignment as Section Editor',
                'body' => "Dear {{sectionEditorName}},\n\nYou have been assigned as the Section Editor for the manuscript \"{{articleTitle}}\".\n\nPlease log into the journal system to manage this submission.\nThe manuscript is attached to this email.\n\nBest regards,\n{{assignedBy}}",
            ],

            [
                'email_template' => 'review_reminder',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Submission Review Reminder',
                'body' => "Dear {{reviewerName}},\n\nJust a gentle reminder regarding the manuscript \"{{articleTitle}}\" which is currently assigned to you for review.\n\nWe noticed that the deadline is approaching ({{deadline}}).\nWe would appreciate it if you could submit your review soon.\n\nBest regards,\n{{assignedBy}}",
            ],

            [
                'email_template' => 'review_request_cancelled',
                'sender' => 'editor',
                'recipient' => 'reviewer',
                'subject' => 'Request for Review Cancelled',
                'body' => "Dear {{reviewerName}},\n\nThe review request for \"{{articleTitle}}\" has been cancelled.\n\nThank you for your willingness to assist.\n\nBest regards,\n{{assignedBy}}",
            ],

            [
                'email_template' => 'review_acceptance',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Able to Review Manuscript',
                'body' => "Dear {{assignedBy}},\n\nI confirm that I am able to review the manuscript \"{{articleTitle}}\".\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'review_decline',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Unable to Review Manuscript',
                'body' => "Dear {{assignedBy}},\n\nUnfortunately, I am unable to review the manuscript \"{{articleTitle}}\" at this time.\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'review_completed',
                'sender' => 'reviewer',
                'recipient' => 'editor',
                'subject' => 'Review Completed',
                'body' => "Dear {{assignedBy}},\n\nI have completed the review for \"{{articleTitle}}\".\n\nThe review has been submitted via the system.\n\nBest regards,\n{{reviewerName}}",
            ],

            [
                'email_template' => 'article_accepted',
                'sender' => 'editor',
                'recipient' => 'author',
                'subject' => 'Decision on Your Manuscript Submission',
                'body' => "Dear {{authorName}},\n\nWe are pleased to inform you that your manuscript \"{{articleTitle}}\" has been accepted.\n\nBest regards,\n{{assignedBy}}",
            ],

            [
                'email_template' => 'article_rejected',
                'sender' => 'editor',
                'recipient' => 'author',
                'subject' => 'Decision on Your Manuscript Submission',
                'body' => "Dear {{authorName}},\n\nAfter careful review, we regret to inform you that your manuscript \"{{articleTitle}}\" has not been accepted.\n\nBest regards,\n{{assignedBy}}",
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
