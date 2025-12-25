<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\PreparedEmail;

class PreparedEmailController extends Controller
{
    public function index()
    {
        $templates = PreparedEmail::orderBy('email_template')->get();

        return view('prepared_email.list', compact('templates'));
    }

    // Show create form
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('prepared_email.add', compact('roles'));
    }

    // Store new template
    public function store(Request $request)
    {
        $request->validate([
            'email_template' => 'required|string|max:255',

            'sender'         => 'nullable|array',
            'sender.*'       => 'exists:roles,name',

            'recipient'      => 'nullable|array',
            'recipient.*'    => 'exists:roles,name',

            'subject'        => 'required|string|max:255',
        ]);

        PreparedEmail::create([
            'email_template' => $request->email_template,
            'sender' => $request->sender
                ? implode(',', $request->sender)
                : null,

            'recipient' => $request->recipient
                ? implode(',', $request->recipient)
                : null,
            'subject'        => $request->subject,
            'body'           => $request->body,
        ]);


        return redirect()->route('prepared_email.index')
            ->with('success', 'Email template created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $template = PreparedEmail::findOrFail($id);
        return view('prepared_email.edit', compact('template'));
    }

    // Update template
    public function update(Request $request, $id)
    {
        $template = PreparedEmail::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'nullable|string',
        ]);

        $template->update([
            'subject' => $request->subject,
            'body'    => $request->body,
        ]);

        return redirect()->route('prepared_email.index')
            ->with('success', 'Email template updated successfully.');
    }

    // Delete template
    public function destroy(PreparedEmail $PreparedEmail)
    {
        $PreparedEmail->delete();

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template deleted.');
    }

    public function getTemplate($template)
    {
        $normalized = str_replace('_', ' ', strtolower($template));

        $email = PreparedEmail::whereRaw('LOWER(email_template) = ?', [$normalized])
            ->firstOrFail();

        if (!$email) {
            return response()->json([
                'subject' => '',
                'body' => ''
            ]);
        }

        return response()->json([
            'subject' => $email?->subject ?? '',
            'body'    => $email?->body ?? '',
        ]);
    }
}
