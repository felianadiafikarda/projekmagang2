@extends('layouts.app')

@section('page_title', 'Prepared Emails')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 py-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Prepared Emails</h1>
            <p class="text-sm text-gray-600">Manage predefined email templates for notifications.</p>
        </div>

        <a href="{{ route('prepared_email.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow-sm">
            + Add New Template
        </a>
    </div>

    {{-- TABLE LIST --}}
    <div class="bg-white border rounded shadow p-6">
        <h3 class="font-semibold mb-4">Email Templates</h3>

        <table class="w-full text-left border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 border w-1/5">Email Template</th>
                    <th class="p-3 border w-1/6">Sender</th>
                    <th class="p-3 border w-1/6">Recipient</th>
                    <th class="p-3 border w-1/3">Subject</th>
                    <th class="p-3 border w-24">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($templates as $t)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-3">{{ $t->email_template }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ implode(', ', $t->sender ?? []) }}</td>
                    <td class="p-3 text-sm text-gray-700">{{ implode(', ', $t->recipient ?? []) }}</td>
                    <td class="p-3">{{ $t->subject }}</td>

                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="{{ route('prepared_email.edit', $t->id) }}" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                Edit
                            </a>

                            <button
                                class="px-3 py-1 text-sm bg-white text-gray-800 rounded hover:bg-gray-400 border border-gray-400">
                                Reset
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection