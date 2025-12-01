@extends('layouts.app')

@section('page_title', 'Add Prepared Email')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold">Add Email Template</h1>
        <p class="text-sm text-gray-600">
            Create a new predefined email template for system notifications.
        </p>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 border rounded-lg shadow-sm">
        <form action="{{ route('prepared_email.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- EMAIL NAME --}}
            <div>
                <label class="font-medium">Template Name</label>
                <input type="text" name="email_template" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="e.g. Reviewer Invitation">
            </div>

            {{-- SENDER --}}
            <div>
                <label class="font-medium">Sender</label>
                <input type="text" name="sender" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="editor / chiefeditor / system">
            </div>

            {{-- RECIPIENT --}}
            <div>
                <label class="font-medium">Recipient</label>
                <input type="text" name="recipient" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Author, Reviewer, Editor...">
            </div>

            {{-- SUBJECT --}}
            <div>
                <label class="font-medium">Subject</label>
                <input type="text" name="subject" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Enter email subject...">
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save
                </button>

                <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Reset
                </button>

                <a href="{{ route('prepared_email.index') }}" class="bg-white text-gray-800 px-4 py-2 rounded hover:bg-gray-400 border border-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection