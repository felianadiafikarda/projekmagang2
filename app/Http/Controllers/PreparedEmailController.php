<?php

namespace App\Http\Controllers;

use App\Models\PreparedEmail;
use Illuminate\Http\Request;

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
        return view('prepared_email.add');
    }

    // Store new template
    public function store(Request $request)
    {
        $request->validate([
            'email_template'    => 'required|string|max:255',
            'sender'            => 'nullable|string|max:255',
            'recipient'         => 'nullable|string|max:255',
            'subject'           => 'required|string|max:255'
        ]);

        PreparedEmail::create($request->all());

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
}
