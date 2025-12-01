@extends('layouts.app')

@section('page_title', 'Edit Email')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold">Edit Email</h1>
        <p class="text-sm text-gray-600">
            This email is sent by an sender to a recipient for a specific purpose.
        </p>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white p-6 border rounded-lg shadow-sm">
        <form action="{{ route('prepared_email.update', $template->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- SUBJECT --}}
            <div>
                <label class="font-medium">Subject</label>
                <input type="text" name="subject" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    value="{{ $template->subject }}">
            </div>

            {{-- BODY --}}
            <div>
                <label class="font-medium">Body</label>
                <textarea name="body" rows="8" class="mt-1 w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    placeholder="Enter email body...">{{ $template->body }}</textarea>
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