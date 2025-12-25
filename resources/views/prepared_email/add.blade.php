@extends('layouts.app')

@section('page_title', 'Add Prepared Email')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- SENDER --}}
                <div>
                    <label class="font-medium">Select Sender</label>
                    <select id="senderSelect"
                        name="sender[]"
                        multiple
                        placeholder="Cari dan pilih sender..."
                        autocomplete="off">
                        @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ ucfirst($role->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- RECIPIENT --}}
                <div>
                    <label class="font-medium">Select Recipient</label>
                    <select id="recipientSelect"
                        name="recipient[]"
                        multiple
                        placeholder="Cari dan pilih recipient..."
                        autocomplete="off">
                        @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ ucfirst($role->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>
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
<script>
    new TomSelect('#senderSelect', {
        plugins: ['remove_button'],
        persist: false,
        create: false
    });
    new TomSelect('#recipientSelect', {
        plugins: ['remove_button'],
        persist: false,
        create: false
    });
</script>

@endsection