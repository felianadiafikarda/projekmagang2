{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor Dashboard')

@section('content')

{{-- TomSelect CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<div class="max-w-7xl rounded-xl mx-auto space-y-6 py-6">

    {{-- === HEADER: NAVIGASI, FILTER & SEARCH === --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">

        {{-- Kiri: Tombol Navigasi --}}
        <div class="flex gap-2">

        </div>

        {{-- Kanan: Filter Status & Search Input --}}
        @if($page === 'list')
        <form action="{{ url()->current() }}" method="GET"
            class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="page" value="list">

            {{-- Dropdown Filter --}}
            <select name="filter_status" onchange="this.form.submit()"
                class="w-full md:w-48 border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 cursor-pointer bg-white text-gray-700">
                <option value="">All Status</option>
                @foreach(['Unassign', 'In Review','Accept with Review','Revised', 'Accepted','Rejected',] as $status)
                <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
                @endforeach
            </select>

            {{-- Input Search --}}
            <div class="relative w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                    class="w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 pr-10">

                {{-- Tombol Search --}}
                <button type="submit"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-white bg-gray-900 hover:bg-gray-700 rounded-r-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('error') }}
    </div>
    @endif


    {{-- === TABLE LIST === --}}
    @if($page === 'list')
    <div class="text-sm text-gray-700 font-medium">
        Showing <span class="font-bold text-red-500">{{ $papers?->count() ?? 0 }}</span>
        {{ Str::plural('paper', $papers?->count() ?? 0) }}
    </div>

    <div class="bg-white border rounded-xl shadow p-6">
        <table class="w-full text-left border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 border text-center">No</th>
                    <th class="p-2 border">Title</th>
                    <th class="p-2 border">Authors</th>
                    <th class="p-2 border text-center">Status</th>
                    <th class="p-2 border text-center">Submitted</th>
                    <th class="p-2 border text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($papers as $p)
                @php
                $statusColor = [
                'unassign' => 'bg-gray-300 text-gray-800',
                'in_review' => 'bg-yellow-300 text-yellow-900',
                'accept_with_review' => 'bg-blue-300 text-blue-900',
                'revised' => 'bg-orange-300 text-orange-900',
                'rejected' => 'bg-red-300 text-red-900',
                'accepted' => 'bg-green-300 text-green-900',
                ];

                @endphp


                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                    <td class="p-2 border font-semibold text-gray-800">{{ $p->judul }}</td>
                    <td class="p-2 border">
                        {{ $p->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?: '-' }}
                    </td>

                    {{-- STATUS (TEXT LABEL BIASA) --}}
                    <td class="p-2 border text-center">
                        <span class="px-2 py-1 rounded text-sm font-semibold
        {{ $statusColor[strtolower(str_replace(' ', '_', $p->editor_display_status))] 
            ?? 'bg-gray-200 text-gray-700' }}">
                            {{ $p->editor_display_status }}
                        </span>
                    </td>


                    <td class="p-2 border text-center">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="p-2 border text-center">
                        <a href="{{ route('editor.detail', $p->id) }}"
                            class="inline-block px-3 py-1 border-2 border-gray-300 bg-gray-300 text-gray-800 rounded hover:bg-gray-500 hover:border-gray-500 mr-2 font-medium transition">
                            Detail
                        </a>

                        <a href="{{ url()->current().'?page=assign&id='.$p->id }}"
                            class="inline-block px-3 py-1 border-2 border-gray-900 bg-gray-900 text-white rounded hover:bg-gray-700 hover:border-gray-700 mr-2 font-medium transition">
                            Edit
                        </a>


                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">No data available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    {{-- === ASSIGN PAGE (DETAIL & EDIT) === --}}
    @if(($page === 'assign' || request('id')) && $paper)
    <div class="bg-white border rounded-xl shadow p-6 relative">

        {{-- HEADER DETAIL --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-semibold">{{ $paper->judul }}</h3>
                <div class="text-sm text-gray-500 mt-1">
                    Author: <span class="font-medium text-gray-800">
                        {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?: '-' }}
                    </span> • Submitted: {{ $paper->created_at->format('d M Y') }}
                </div>
            </div>

            {{-- TOMBOL AKSI UTAMA --}}
            <div class="text-right w-56">
                {{-- Form Wrapper (Optional, karena sekarang semua pakai modal) --}}
                <form action="#" method="POST" onsubmit="return false;">

                    <div class="flex flex-col gap-2">

                        {{-- 1. Request Revisions (MODAL) --}}
                        <button type="button" onclick="openRevisionModal()"
                            style="background-color: #828180; border-color: #828180;"
                            class="w-full hover:opacity-90 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition text-center">
                            Request Revisions
                        </button>

                        {{-- 2. Accept Submission (MODAL) --}}
                        <button type="button" onclick="openAcceptModal()"
                            style="background-color: #ABE7B2; border-color: #ABE7B2;"
                            class="w-full hover:opacity-90 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition text-center">
                            Accept Submission
                        </button>

                        {{-- 3. Decline Submission (MODAL) --}}
                        <button type="button" onclick="openDeclineModal()"
                            style="background-color: #FF2F00; border-color: #FF2F00;"
                            class="w-full hover:opacity-90 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition text-center">
                            Decline Submission
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ========================================================== --}}
        {{-- 1. GROUP: ASSIGN SECTION EDITOR --}}
        {{-- ========================================================== --}}
        <div class="mt-8 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Section Editor</h4>

            <div class="bg-gray-50 border rounded-lg p-4">

                {{-- Form --}}
                <form id="assignSectionEditorForm" method="POST"
                    action="{{ route('editor.assignSectionEditor', $paper->id) }}" class="mb-4">
                    @csrf
                    <input type="hidden" name="subject" id="seSubjectInput">
                    <input type="hidden" name="email_body" id="seBodyInput">
                    <input type="hidden" name="section_editors" id="seEditorsInput">
                    <input type="hidden" name="send_email" id="seSendEmailInput" value="1">
                    <div class="flex gap-2">
                        <div class="flex-grow">
                            <select id="editorSelect" name="section_editors[]" multiple
                                placeholder="Select Section Editor...">
                                @foreach($all_section_editors as $se)
                                <option value="{{ $se->id }}" data-active="{{ $se->active_papers }}" @if(in_array($se->
                                    id, $assignedSectionEditors->pluck('id')->toArray())) selected @endif>
                                    {{ $se->first_name . ' ' . $se->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" onclick="openAssignSectionEditorModal()"
                            class="bg-gray-900 text-white px-6 py-2 rounded hover:bg-gray-700 shadow-sm transition duration-200 flex items-center gap-2 text-sm font-medium">
                            Assign Section Editor
                        </button>

                    </div>
                </form>

                {{-- List Assigned --}}
                <div class="mt-2 border-t border-gray-200 pt-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Assigned Section
                        Editors List</label>
                    <div class="space-y-2">
                        @forelse($assignedSectionEditors as $ase)
                        <div class="flex justify-between items-center bg-white p-2.5 rounded border shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">SE</div>
                                <span
                                    class="font-medium text-gray-700">{{$ase->first_name . ' ' . $ase->last_name}}</span>
                            </div>
                            <form method="POST" action="{{ route('editor.unassignSectionEditor', $paper->id) }}">
                                @csrf
                                <input type="hidden" name="editor_id" value="{{ $ase->id }}">
                                <button
                                    class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded transition"
                                    onclick="return confirm('Remove this section editor?')">
                                    Unassign
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-sm text-gray-500 italic">No section editors assigned yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        {{-- ========================================================== --}}
        {{-- 2. GROUP: ASSIGN REVIEWER --}}
        {{-- ========================================================== --}}
        <div class="mt-8 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Reviewer</h4>

            <div class="bg-gray-50 border rounded-lg p-4">

                {{-- Form --}}
                <form id="assignForm" method="POST" action="{{ route('editor.assignReviewers', $paper->id) }}">
                    @csrf
                    <input type="hidden" id="subjectInput" name="subject">
                    <input type="hidden" id="bodyInput" name="email_body">
                    <input type="hidden" id="reviewersInput" name="reviewers">
                    <input type="hidden" id="deadlineInput" name="deadline">
                    <input type="hidden" id="sendEmailInput" name="send_email" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Reviewers</label>
                            <select id="reviewerSelect" name="reviewers[]" multiple
                                placeholder="Cari dan pilih Reviewer..." autocomplete="off">
                                @foreach($all_reviewers as $rev)
                                <option value="{{ $rev->id }}" data-active="{{ $rev->active_papers }}"
                                    @if(in_array($rev->id, $assignedReviewers->pluck('id')->toArray())) selected @endif>
                                    {{ $rev->first_name . ' ' . $rev->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                            <input type="date" id="deadlineDate" name="deadline"
                                class="border border-gray-300 rounded p-1.5 w-full focus:ring-gray-900 focus:border-gray-900 h-[38px] mt-[1px]">
                        </div>
                    </div>

                    <button type="button" onclick="openAssignModal()"
                        class="bg-gray-900 text-white px-6 py-2 rounded hover:bg-gray-700 shadow-sm transition duration-200 flex items-center gap-2 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Request & Assign Reviewers
                    </button>
                </form>

                {{-- List Assigned --}}
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Assigned Reviewers
                        List</label>
                    <div class="space-y-4">
                        @forelse($assignedReviewers as $ar)
                        <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition duration-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-lg text-gray-800">
                                        {{ $ar->first_name . ' ' . $ar->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Deadline: <span
                                            class="font-medium">{{ date('d M Y', strtotime($ar->pivot->deadline)) }}</span>
                                    </div>
                                    <div class="text-sm mt-1 flex items-center gap-2">
                                        Status:
                                        @php $status = $ar->pivot->status; @endphp
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold
                                            @if($status === 'completed') bg-green-100 text-green-700 @endif
                                            @if($status === 'assigned') bg-blue-100 text-blue-700 @endif
                                            @if($status === 'accept_to_review') bg-indigo-100 text-indigo-700 @endif
                                            @if($status === 'decline_to_review') bg-red-100 text-red-700 @endif">
                                            {{ ucwords(str_replace('_',' ', $status)) }}
                                        </span>
                                    </div>
                                    @if($status === 'completed' && $ar->pivot->recommendation)
                                    <div
                                        class="text-sm mt-2 p-2 bg-gray-50 rounded text-gray-700 border border-gray-200">
                                        Recommendation: <strong>{{ $ar->pivot->recommendation }}</strong>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 items-end">
                                    @if(in_array($status, ['assigned', 'accept_to_review', 'decline_to_review']))
                                    <button type="button"
                                        onclick="openReminderModal('{{ $ar->id }}', '{{ $ar->first_name . ' ' . $ar->last_name }}', '{{ $ar->pivot->deadline }}')"
                                        class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 transition shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                        </svg> Send Reminder
                                    </button>
                                    @endif

                                    @if($status === 'completed')
                                    <div class="flex gap-2 justify-center">
                                        <button
                                            class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 transition shadow-sm"
                                            data-review='{!!
                                        json_encode([
                                            "reviewer" => $ar->first_name . " " . $ar->last_name,
                                            "reviewed_at" => $ar->pivot->reviewed_at,
                                            "recommendation" => $ar->pivot->recommendation,
                                            "Q1" => $ar->pivot->Q1,
                                            "Q2" => $ar->pivot->Q2,
                                            "Q3" => $ar->pivot->Q3,
                                            "author_comment" => $ar->pivot->comments_for_author,
                                            "editor_comment" => $ar->pivot->comments_for_editor,
                                            "file" => [
                                            "path" => $ar->pivot->review_file,
                                            "name" => $ar->pivot->review_file_name,
                                            ],

                                        ])
                                    !!}' onclick="openReviewModal(this)">
                                            Read Review
                                        </button>
                                        <button onclick="openThankYouModal('{{ $ar->id }}', '{{ $ar->full_name }}')" 
                                            class="inline-flex items-center gap-1.5
    text-sm px-3 py-1.5 rounded
    border border-amber-400
    bg-amber-50 text-amber-700 font-medium
    hover:bg-amber-100
    transition shadow-sm">

                                            <svg class="w-3 h-3 text-amber-500 flex-shrink-0
        translate-y-[-0.5px]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M10 2a1 1 0 01.894.553l2.553 5.173
        5.713.83a1 1 0 01.554 1.705l-4.133 4.03
        .976 5.692a1 1 0 01-1.451 1.054L10 18.347
        4.894 21.037a1 1 0 01-1.451-1.054l.976-5.692
        -4.133-4.03a1 1 0 01.554-1.705l5.713-.83L9.106 2.553A1 1 0 0110 2z" />
                                            </svg>

                                            Appreciate
                                        </button>

                                    </div>
                                    @endif

                                    <!-- Review Modal (Enhanced Design) -->
                                    <div id="reviewModal"
                                        class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-start justify-center p-4"
                                        onclick="closeReviewModal()">

                                        <!-- MODAL BOX -->
                                        <div class="relative bg-white w-full max-w-4xl mt-10 rounded-2xl shadow-2xl
        overflow-hidden max-h-[80vh] flex flex-col border border-slate-200" onclick="event.stopPropagation()">

                                            <!-- HEADER -->
                                            <div
                                                class="px-6 py-4 bg-slate-800 text-white flex justify-between items-center">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-xl bg-slate-700 flex items-center justify-center">
                                                        📋
                                                    </div>
                                                    <div>
                                                        <h3 class="text-lg font-semibold">Reviewer Reports</h3>
                                                        <p class="text-xs text-slate-300">Confidential peer review
                                                        </p>
                                                    </div>
                                                </div>

                                                <button onclick="closeReviewModal()" class="w-9 h-9 flex items-center justify-center rounded-lg
                bg-white/10 hover:bg-white/20 transition">
                                                    ✕
                                                </button>
                                            </div>

                                            <!-- CONTENT -->
                                            <div id="reviewModalContent"
                                                class="px-6 py-6 overflow-y-auto space-y-6 bg-slate-50">

                                                @forelse($reviews as $review)

                                                <!-- REVIEW CARD -->
                                                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">

                                                    <!-- TOP BAR (RECOMMENDATION COLOR) -->
                                                    <div
                                                        class="h-1.5 rounded-t-2xl
                    {{ $review->recommendation === 'accepted' ? 'bg-emerald-500' : '' }}
                    {{ $review->recommendation === 'rejected' ? 'bg-rose-500' : '' }}
                    {{ in_array($review->recommendation, ['minor revision','major revision']) ? 'bg-amber-500' : '' }}">
                                                    </div>

                                                    <div class="p-6 space-y-5">

                                                        <!-- REVIEWER INFO -->
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <p class="text-lg font-semibold text-slate-800">
                                                                    {{ $review->first_name }}
                                                                    {{ $review->last_name }}
                                                                </p>
                                                                <p class="text-xs text-slate-500">
                                                                    Reviewed at:
                                                                    {{ $review->reviewed_at ?? 'Pending' }}
                                                                </p>
                                                            </div>

                                                            <span
                                                                class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white
                            {{ $review->recommendation === 'accepted' ? 'bg-emerald-500' : '' }}
                            {{ $review->recommendation === 'rejected' ? 'bg-rose-500' : '' }}
                            {{ in_array($review->recommendation, ['minor revision','major revision']) ? 'bg-amber-500' : '' }}">
                                                                {{ ucfirst($review->recommendation) }}
                                                            </span>
                                                        </div>

                                                        <!-- COMMENTS FOR AUTHOR -->
                                                        <div>
                                                            <p class="text-sm font-semibold text-slate-700 mb-2">
                                                                Comments for Author
                                                            </p>
                                                            <div class="text-sm text-slate-700 leading-relaxed bg-slate-50
                            border border-slate-200 rounded-xl p-4">
                                                                {{ $review->comments_for_author }}
                                                            </div>
                                                        </div>

                                                        <!-- COMMENTS FOR EDITOR -->
                                                        @if($review->comments_for_editor)
                                                        <div>
                                                            <p class="text-sm font-semibold text-rose-700 mb-2">
                                                                Confidential – Editor Only
                                                            </p>
                                                            <div class="text-sm text-rose-900 leading-relaxed bg-rose-50
                            border border-rose-200 rounded-xl p-4">
                                                                {{ $review->comments_for_editor }}
                                                            </div>
                                                        </div>
                                                        @endif

                                                        <!-- REVIEW FILE -->
                                                        @if($review->review_file)
                                                        <div class="flex justify-end pt-2">
                                                            <a href="{{ asset('storage/' . $review->review_file) }}"
                                                                target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                            bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold transition">
                                                                ⬇ Download Review File
                                                            </a>
                                                        </div>
                                                        @endif

                                                    </div>
                                                </div>

                                                @empty
                                                <!-- EMPTY STATE -->
                                                <div class="text-center py-20">
                                                    <p class="text-slate-600 text-lg font-semibold">
                                                        No completed reviews available
                                                    </p>
                                                    <p class="text-slate-400 text-sm mt-1">
                                                        Reviewer feedback will appear here once completed
                                                    </p>
                                                </div>
                                                @endforelse

                                            </div>
                                            <!-- FOOTER -->
                                            <div class="px-8 py-4 border-t bg-white text-right">
                                                <button onclick="closeReviewModal()"
                                                    class="px-5 py-2 rounded-xl bg-slate-700 text-white hover:bg-slate-800">
                                                    Close
                                                </button>
                                            </div>

                                        </div>
                                    </div>



                                    @if($assignedReviewers->contains('id', $ar->id))
                                    <button onclick="unassignReviewer('{{ $ar->id }}')"
                                        class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded transition">Unassign</button>
                                    @else
                                    <button onclick="assignReviewer('{{ $ar->id }}')"
                                        class="text-sm text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-1 rounded transition">Assign</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-gray-500 italic">No reviewers assigned yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ url()->current() . '?page=list' }}"
            class="px-5 py-2 rounded-xl bg-slate-700 text-white hover:bg-slate-800">
            Back
        </a>
    </div>

</div>

@endif

{{-- MODAL EMAIL (Hidden by default) --}}
<div id="emailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
        <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white text-lg font-semibold" id="modalTitle">Add Reviewer & Send Email</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Reviewer(s)</label>
                <input type="text" id="modalRecipient" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="emailSubject"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-gray-900 focus:border-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                <textarea id="emailBody" rows="8"
                    class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm focus:ring-gray-900 focus:border-gray-900"></textarea>
                <p class="text-xs text-gray-500 mt-1">You can edit the message above before sending.</p>
            </div>
            @if(isset($modalType) && $modalType === 'assign')
            <div class="flex items-center mb-4">
                <input type="checkbox" id="skipEmail" name="send_email" value="0"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                    onclick="document.getElementById('sendEmailInput').value = this.checked ? 0 : 1">
                <label for="skipEmail" class="ml-2 block text-sm text-gray-900">Do not send email to Reviewer
                    (Assign
                    only).</label>
            </div>
            @endif
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
            <button onclick="closeModal()"
                class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button onclick="submitProcess()"
                class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-700 shadow-sm transition">Send &
                Process</button>
        </div>
    </div>
</div>

{{-- MODAL 2: REQUEST REVISIONS --}}
<div id="revisionModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden z-[60] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl my-8 transform transition-all">

        {{-- Header Modal --}}
        <div class="bg-gray-800 px-6 py-5 flex justify-between items-center rounded-t-lg">
            <h3 class="text-xl font-semibold text-white">Request Revisions</h3>
            <button onclick="closeRevisionModal()" class="text-white hover:text-gray-300 focus:outline-none transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form Pembungkus --}}
        @if($paper)
        <form method="POST" id="requestRevision" action="{{ route('editor.updateStatus', $paper->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="accept_with_review">

            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                {{-- A. Require New Review Round --}}
                <div>
                    <h4 class="font-bold text-gray-800 mb-2">Require New Review Round</h4>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-md">
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="new_review_round" value="0"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                            <span class="text-gray-700 text-sm">Revisions will <strong>not</strong> be subject to a
                                new
                                round of peer reviews.</span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="new_review_round" value="1"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700 text-sm">Revisions will be subject to a new round of peer
                                reviews.</span>
                        </label>
                    </div>
                </div>

                {{-- B. Send Email Option --}}
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 text-base">Send Email</h4>
                    <div class="space-y-3 mb-4 bg-gray-50 p-4 rounded-md">
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="1"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500" checked
                                onclick="toggleEmailEditor(true, 'revisionEmailContainer')">
                            <div class="text-sm text-gray-700">
                                <span>Send an email notification to the author(s): </span>
                                <span class="font-medium text-gray-900">
                                    {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?? 'Author' }}
                                </span>
                            </div>
                        </label>
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="0"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500"
                                onclick="toggleEmailEditor(false, 'revisionEmailContainer')">
                            <span class="text-gray-700 text-sm">Do not send an email notification</span>
                        </label>
                    </div>

                    {{-- Text Editor Area --}}
                    <div id="revisionEmailContainer"
                        class="border border-gray-300 rounded-md shadow-sm transition-opacity duration-200 bg-white">
                        {{-- Toolbar Dummy --}}
                        <div class="bg-gray-50 border-b border-gray-300 px-3 py-2 flex gap-3 text-gray-600">
                            <button type="button" data-action="bold"
                                class="hover:text-gray-900 font-bold text-sm transition">B</button>
                            <button type="button" data-action="italic"
                                class="hover:text-gray-900 italic text-sm transition">I</button>
                            <button type="button" data-action="underline"
                                class="hover:text-gray-900 underline text-sm transition">U</button>
                        </div>

                        {{-- Textarea --}}
                        <textarea name="email_body" rows="6"
                            class="w-full p-4 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-b-md"
                            spellcheck="false">
{{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') }}:

We have reached a decision regarding your submission to {{ config('app.name', 'Jurnal JPSD') }}, "{{ $paper->judul ?? 'Untitled' }}".

Our decision is: Accept with Review

Please revise your manuscript based on the reviewers' comments and resubmit it for further consideration.

Best regards,
{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}

                            </textarea>
                    </div>
                </div>

                {{-- C. Select Review Files --}}
                <div class="border-t pt-5">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-gray-800 text-base">Select review files to share with the
                            author(s)
                        </h4>
                        <div class="bg-gray-50 flex gap-3 items-center">
                            <input type="file" id="additionalFiles" name="additional_files[]" multiple class="hidden">

                            <label for="additionalFiles"
                                class="text-blue-600 text-sm hover:underline font-semibold cursor-pointer">
                                Upload File
                            </label>
                        </div>

                    </div>

                    <div class="border rounded-md overflow-hidden">
                        @php
                        $reviewFiles = $paper->reviewers
                        ->where('pivot.status', 'completed')
                        ->whereNotNull('pivot.review_file')
                        ->values();
                        @endphp


                        @if($reviewFiles->count())
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">

                                @foreach($reviewFiles as $r)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" name="review_files[]"
                                            value="{{ $r->pivot->review_file }}" checked onclick="return false"
                                            class="rounded border-gray-300 text-blue-600">

                                    </td>

                                    <td class="px-4 py-3 text-blue-600 font-medium">
                                        📄 {{ $r->pivot->review_file_name ?? 'Review File' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-500 text-xs">
                                        {{ $r->pivot->reviewed_at
                                        ? \Carbon\Carbon::parse($r->pivot->reviewed_at)->format('d M Y')
                                        : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ asset('storage/'.$r->pivot->review_file) }}" target="_blank"
                                            class="text-blue-600 hover:underline text-xs font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tbody id="uploadedFilesPreview" class="divide-y divide-gray-100"></tbody>
                        </table>
                        @else
                        <p
                            class="border border-gray-300 rounded-md overflow-hidden bg-gray-50 p-6 text-center text-sm text-gray-500">
                            No review files available.
                        </p>
                        @endif

                    </div>

                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-lg">
                <button type="button" onclick="closeRevisionModal()"
                    class="px-5 py-2 border border-red-500 text-red-600 font-semibold rounded hover:bg-red-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded shadow transition">
                    Request Revision
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

{{-- MODAL 3: ACCEPT SUBMISSION --}}
<div id="AcceptModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden z-[60] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl my-8 transform transition-all">

        {{-- Header Modal --}}
        <div class="bg-gray-800 px-6 py-5 flex justify-between items-center rounded-t-lg">
            <h3 class="text-xl font-semibold text-white">Accept Submission</h3>
            <button onclick="closeAcceptModal()" class="text-white hover:text-gray-300 focus:outline-none transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form Pembungkus --}}
        @if($paper)
        <form method="POST" id="acceptModal" action="{{ route('editor.updateStatus', $paper->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="accepted">

            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                {{-- A. Send Email Option --}}
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 text-base">Send Email</h4>
                    <div class="space-y-3 mb-4 bg-gray-50 p-4 rounded-md">
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="1"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500" checked
                                onclick="toggleEmailEditor(true, 'acceptEmailContainer')">
                            <div class="text-sm text-gray-700">
                                <span>Send an email notification to the author(s): </span>
                                <span class="font-medium text-gray-900">
                                    {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?? 'Author' }}
                                </span>
                            </div>
                        </label>
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="0"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500"
                                onclick="toggleEmailEditor(false, 'acceptEmailContainer')">
                            <span class="text-gray-700 text-sm">Do not send an email notification</span>
                        </label>
                    </div>

                    {{-- Text Editor Area (Accept Template) --}}
                    <div id="acceptEmailContainer"
                        class="border border-gray-300 rounded-md shadow-sm transition-opacity duration-200 bg-white">
                        {{-- Toolbar Dummy --}}
                        <div class="bg-gray-50 border-b border-gray-300 px-3 py-2 flex gap-3 text-gray-600">
                            <button type="button" data-action="bold"
                                class="hover:text-gray-900 font-bold text-sm transition">B</button>
                            <button type="button" data-action="italic"
                                class="hover:text-gray-900 italic text-sm transition">I</button>
                            <button type="button" data-action="underline"
                                class="hover:text-gray-900 underline text-sm transition">U</button>
                        </div>

                        {{-- Textarea Isi Email ACCEPT --}}
                        <textarea name="email_body" rows="6"
                            class="w-full p-4 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-b-md"
                            spellcheck="false">
{{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') }}:

We have reached a decision regarding your submission to {{ config('app.name', 'Jurnal JPSD') }}, "{{ $paper->judul ?? 'Untitled' }}".

Our decision is to: Accept

{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}

                            </textarea>
                    </div>
                </div>

                {{-- B. Select Review Files --}}
                <div class="border-t pt-5">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-gray-800 text-base">Select review files to share with the
                            author(s)
                        </h4>
                        <div class="bg-gray-50 flex gap-3 items-center">
                            <input type="file" id="additionalFilesAccept" name="additional_files_accept[]" multiple
                                class="hidden">

                            <label for="additionalFilesAccept"
                                class="text-blue-600 text-sm hover:underline font-semibold cursor-pointer">
                                Upload File
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-md overflow-hidden bg-white">
                        @php
                        $reviewFiles = $paper->reviewers
                        ->where('pivot.status', 'completed')
                        ->whereNotNull('pivot.review_file')
                        ->values();
                        @endphp

                        @if($reviewFiles->count())
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($reviewFiles as $r)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" name="review_files[]"
                                            value="{{ $r->pivot->review_file }}" checked onclick="return false"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                    </td>

                                    <td class="px-4 py-3 text-blue-600 font-medium">
                                        📄 {{ $r->pivot->review_file_name ?? 'Review File' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-500 text-xs">
                                        {{ $r->pivot->reviewed_at
                                        ? \Carbon\Carbon::parse($r->pivot->reviewed_at)->format('d M Y')
                                        : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ asset('storage/'.$r->pivot->review_file) }}" target="_blank"
                                            class="text-blue-600 hover:underline text-xs font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tbody id="uploadedFilesPreviewAccept" class="divide-y divide-gray-100"></tbody>

                        </table>
                        @else
                        <p class="p-6 text-center text-sm text-gray-500">
                            No review files available.
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-white border-t border-gray-200 flex justify-end gap-3 rounded-b-lg">
                <button type="button" onclick="closeAcceptModal()"
                    class="px-5 py-2 border-2 border-red-500 text-red-600 font-semibold rounded hover:bg-red-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded shadow transition">
                    Accept Submission
                </button>
            </div>
        </form>
        @endif
    </div>
</div>


{{-- MODAL 4: DECLINE SUBMISSION --}}
<div id="DeclineModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden z-[60] flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl my-8 transform transition-all">

        {{-- Header Modal --}}
        <div class="bg-gray-800 px-6 py-5 flex justify-between items-center rounded-t-lg">
            <h3 class="text-xl font-semibold text-white">Decline Submission</h3>
            <button onclick="closeDeclineModal()" class="text-white hover:text-gray-300 focus:outline-none transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form Pembungkus (Submit status = Rejected) --}}
        @if($paper)
        <form id="declineModal" action="{{ route('editor.updateStatus', $paper->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">

            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto bg-white">

                {{-- A. Send Email Option --}}
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 text-base">Send Email</h4>
                    <div class="space-y-3 mb-4 bg-gray-50 p-4 rounded-md">
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="1"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500" checked
                                onclick="toggleEmailEditor(true, 'declineEmailContainer')">
                            <div class="text-sm text-gray-700">
                                <span>Send an email notification to the author(s): </span>
                                <span class="font-medium text-gray-900">
                                    {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?? 'Author' }}
                                </span>
                            </div>
                        </label>
                        <label class="flex items-start cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="radio" name="send_email_decision" value="0"
                                class="mt-1 mr-3 text-blue-600 border-gray-300 focus:ring-blue-500"
                                onclick="toggleEmailEditor(false, 'declineEmailContainer')">
                            <span class="text-gray-700 text-sm">Do not send an email notification</span>
                        </label>
                    </div>

                    {{-- Text Editor Area (Decline Template) --}}
                    <div id="declineEmailContainer"
                        class="border border-gray-300 rounded-md shadow-sm transition-opacity duration-200 bg-white">
                        {{-- Toolbar --}}
                        <div class="bg-gray-50 border-b border-gray-300 px-3 py-2 flex gap-3 text-gray-600">
                            <button type="button" data-action="bold"
                                class="hover:text-gray-900 font-bold text-sm transition">B</button>
                            <button type="button" data-action="italic"
                                class="hover:text-gray-900 italic text-sm transition">I</button>
                            <button type="button" data-action="underline"
                                class="hover:text-gray-900 underline text-sm transition">U</button>
                        </div>

                        {{-- Textarea Isi Email DECLINE --}}
                        <textarea name="email_body" rows="6"
                            class="w-full p-4 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-b-md"
                            spellcheck="false">
{{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') }}:

We have reached a decision regarding your submission to {{ config('app.name', 'Jurnal JPSD') }}, "{{ $paper->judul ?? 'Untitled' }}".

Our decision is to: Decline

{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}

                            </textarea>
                    </div>
                </div>

                {{-- B. Select Review Files --}}
                <div class="border-t pt-5">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-gray-800 text-base">Select review files to share with the
                            author(s)
                        </h4>
                        <div class="bg-gray-50 flex gap-3 items-center">
                            <input type="file" id="additionalFilesDecline" name="additional_files_decline[]" multiple
                                class="hidden">

                            <label for="additionalFilesDecline"
                                class="text-blue-600 text-sm hover:underline font-semibold cursor-pointer">
                                Upload File
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-md overflow-hidden bg-white">
                        @php
                        $reviewFiles = $paper->reviewers
                        ->where('pivot.status', 'completed')
                        ->whereNotNull('pivot.review_file')
                        ->values();
                        @endphp


                        @if($reviewFiles->count())
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">

                                @foreach($reviewFiles as $ar)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" name="review_files[]"
                                            value="{{ $r->pivot->review_file }}" checked onclick="return false"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                    </td>

                                    <td class="px-4 py-3 text-blue-600 font-medium">
                                        📄 {{ $ar->pivot->review_file_name ?? 'Review File' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-pink-600 text-xs">
                                        {{ $ar->pivot->reviewed_at
                                ? \Carbon\Carbon::parse($ar->pivot->reviewed_at)->format('d M Y')
                                : '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-500">
                                        <a href="{{ asset('storage/' . $ar->pivot->review_file) }}" target="_blank"
                                            class="text-blue-600 hover:underline text-xs font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tbody id="uploadedFilesPreviewDecline" class="divide-y divide-gray-100"></tbody>
                        </table>

                        @else
                        <div class="p-4 text-center text-sm text-gray-500">
                            No review files available.
                        </div>
                        @endif
                    </div>

                </div>

            </div>

            {{-- Footer Tombol (Sesuai Screenshot: Cancel & Record Editorial Decision) --}}
            <div class="px-6 py-4 bg-white border-t border-gray-200 flex justify-end gap-3 rounded-b-lg">
                <button type="button" onclick="closeDeclineModal()"
                    class="px-5 py-2 border-2 border-red-500 text-red-600 font-semibold rounded hover:bg-red-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded shadow transition">
                    Decline Submission
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
// --- Variabel Data dari PHP ke JS ---
const articleTitle = "{{ $paper->judul ?? 'Judul Artikel' }}";
const articleUrl = "{{ $articleUrl ?? '#' }}"
const editorName = "{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}";
const paperId = "{{ $paper->id ?? '' }}";
let reviewerSelectInstance;
let modalMode = "assign";

document.addEventListener('DOMContentLoaded', function() {
    // Init TomSelect Reviewer
    if (document.getElementById("reviewerSelect")) {
        reviewerSelectInstance = new TomSelect("#reviewerSelect", {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: 'Cari dan pilih Reviewer...',
            render: {
                option: function(data, escape) {
                    const activePapers = data.$option?.dataset?.active || '0';
                    const reviewerName = escape(data.text);
                    return `<div class="py-2 px-3 hover:bg-blue-50 border-b border-gray-100 last:border-0">
                                    <div class="font-medium text-gray-800">${reviewerName}</div>
                                    <div class="text-xs text-gray-600">Active Review: ${activePapers} paper(s)</div>
                                </div>`;
                },
                item: function(data, escape) {
                    const activePapers = data.$option?.dataset?.active || '0';
                    const reviewerName = escape(data.text);
                    return `<div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full mr-1 flex items-center shadow-sm border border-blue-200">
                                    ${reviewerName} (Active Review: ${activePapers})
                                </div>`;
                }
            }
        });
    }

    // Init TomSelect Editor
    if (document.getElementById("editorSelect")) {
        new TomSelect("#editorSelect", {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: 'Pilih Section Editor...',
            render: {
                option: function(data, escape) {
                    const activePapers = data.$option?.dataset?.active || '0';
                    const editorName = escape(data.text);
                    return `<div class="py-2 px-3 hover:bg-blue-50 border-b border-gray-100 last:border-0">
                                <div class="font-medium text-gray-800">${editorName}</div>
                                <div class="text-xs text-gray-600">Active Papers: ${activePapers}</div>
                            </div>`;
                },
                item: function(data, escape) {
                    const activePapers = data.$option?.dataset?.active || '0';
                    const editorName = escape(data.text);
                    return `<div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full mr-1 flex items-center shadow-sm border border-blue-200">
                                ${editorName} (Active Papers: ${activePapers})
                            </div>`;
                }
            }
        });
    }
});

// --- LOGIKA MODAL REVISION, ACCEPT & DECLINE ---
const revisionModal = document.getElementById('revisionModal');
const acceptModal = document.getElementById('AcceptModal');
const declineModal = document.getElementById('DeclineModal');

function openRevisionModal() {
    if (revisionModal) revisionModal.classList.remove('hidden');
}

function closeRevisionModal() {
    if (revisionModal) revisionModal.classList.add('hidden');
}

function openAcceptModal() {
    if (acceptModal) acceptModal.classList.remove('hidden');
}

function closeAcceptModal() {
    if (acceptModal) acceptModal.classList.add('hidden');
}

function openDeclineModal() {
    if (declineModal) declineModal.classList.remove('hidden');
}

function closeDeclineModal() {
    if (declineModal) declineModal.classList.add('hidden');
}

// Fungsi Generic untuk toggle editor email di modal manapun
function toggleEmailEditor(enable, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const textarea = container.querySelector('textarea');
    if (enable) {
        container.classList.remove('opacity-50', 'pointer-events-none');
        textarea.disabled = false;
    } else {
        container.classList.add('opacity-50', 'pointer-events-none');
        textarea.disabled = true;
    }
}

document.addEventListener('click', function(e) {
    const button = e.target.closest('button[data-action]');
    if (!button) return;

    const container = button.closest('[id$="EmailContainer"]');
    if (!container) return;

    const textarea = container.querySelector('textarea');
    if (!textarea) return;

    const action = button.dataset.action;

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    if (start === end) return; // tidak ada teks dipilih

    const selectedText = textarea.value.substring(start, end);
    let wrapped = selectedText;
    switch (action) {
        case 'bold':
            wrapped = `<b>${selectedText}</b>`;
            break;
        case 'italic':
            wrapped = `<i>${selectedText}</i>`;
            break;
        case 'underline':
            wrapped = `<u>${selectedText}</u>`;
            break;
    }
    textarea.setRangeText(wrapped, start, end, 'end');
    textarea.focus();

});

document.getElementById('additionalFiles').addEventListener('change', function() {
    const preview = document.getElementById('uploadedFilesPreview');
    preview.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        const fileUrl = URL.createObjectURL(file);

        row.innerHTML = `
            <td class="px-4 py-3 w-10 text-center">
                <input type="checkbox"
                       name="selected_new_files[]"
                       value="${index}"
                       class="rounded border-gray-300 text-blue-600">
            </td>

            <td class="px-4 py-3 text-blue-600 font-medium">
                📄 ${file.name}
                <span class="ml-2 text-xs text-gray-400">(new)</span>
            </td>

            <td class="px-4 py-3 text-right text-gray-500 text-xs">
                Just now
            </td>

            <td class="px-4 py-3 text-right">
                <a href="${fileUrl}" target="_blank"
                   class="text-blue-600 hover:underline text-xs font-semibold">
                    View
                </a>
            </td>
        `;

        preview.appendChild(row);
    });
});

document.getElementById('additionalFilesAccept').addEventListener('change', function() {
    const preview = document.getElementById('uploadedFilesPreviewAccept');
    preview.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        const fileUrl = URL.createObjectURL(file);

        row.innerHTML = `
            <td class="px-4 py-3 w-10 text-center">
                <input type="checkbox"
                       name="selected_new_files_accept[]"
                       value="${index}"
                       class="rounded border-gray-300 text-blue-600">
            </td>

            <td class="px-4 py-3 text-blue-600 font-medium">
                📄 ${file.name}
                <span class="ml-2 text-xs text-gray-400">(new)</span>
            </td>

            <td class="px-4 py-3 text-right text-gray-500 text-xs">
                Just now
            </td>

            <td class="px-4 py-3 text-right">
                <a href="${fileUrl}" target="_blank"
                   class="text-blue-600 hover:underline text-xs font-semibold">
                    View
                </a>
            </td>
        `;

        preview.appendChild(row);
    });
});

document.getElementById('additionalFilesDecline').addEventListener('change', function() {
    const preview = document.getElementById('uploadedFilesPreviewDecline');
    preview.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        const fileUrl = URL.createObjectURL(file);

        row.innerHTML = `
            <td class="px-4 py-3 w-10 text-center">
                <input type="checkbox"
                       name="selected_new_files_decline[]"
                       value="${index}"
                       class="rounded border-gray-300 text-blue-600">
            </td>

            <td class="px-4 py-3 text-blue-600 font-medium">
                📄 ${file.name}
                <span class="ml-2 text-xs text-gray-400">(new)</span>
            </td>

            <td class="px-4 py-3 text-right text-gray-500 text-xs">
                Just now
            </td>

            <td class="px-4 py-3 text-right">
                <a href="${fileUrl}" target="_blank"
                   class="text-blue-600 hover:underline text-xs font-semibold">
                    View
                </a>
            </td>
        `;

        preview.appendChild(row);
    });
});

// --- LOGIKA MODAL EMAIL ---
const modal = document.getElementById('emailModal');
const modalTitle = document.getElementById('modalTitle');
const modalRecipient = document.getElementById('modalRecipient');
const emailSubject = document.getElementById('emailSubject');
const emailBody = document.getElementById('emailBody');

// 1. Fungsi Buka Modal Assign Reviewer
function openAssignModal() {
    modalMode = "assign";
    if (!reviewerSelectInstance) return;

    // Ambil data reviewer yang dipilih
    const selectedItems = reviewerSelectInstance.items;
    const selectedOptions = reviewerSelectInstance.options;

    if (selectedItems.length === 0) {
        alert("Please select at least one reviewer first.");
        return;
    }

    // Ambil deadline
    const deadlineInputEl = document.getElementById('deadlineDate');
    const deadline = deadlineInputEl ? deadlineInputEl.value : '';
    if (!deadline) {
        alert('Please set a deadline.');
        deadlineInputEl.focus();
        return;
    }

    // Gabungkan nama reviewer (ambil hanya nama, tanpa info jumlah pekerjaan)
    let names = selectedItems.map(id => {
        const text = selectedOptions[id].text;
        return text.split(' (')[0].trim(); // Ambil hanya nama sebelum tanda kurung
    }).join(", ");

    // Set isi modal
    modalTitle.innerText = "Assign Reviewer & Send Invitation";
    modalRecipient.value = names;

    fetch('/prepared-email/invite_to_review')
        .then(res => res.json())
        .then(data => {
            emailSubject.value = data.subject;

            let body = data.body;
            body = body.replace(/@{{reviewerName}}/g, names);
            body = body.replace(/@{{articleTitle}}/g, articleTitle);
            body = body.replace(/@{{articleUrl}}/g, articleUrl);
            body = body.replace(/@{{assignedBy}}/g, editorName);
            body = body.replace(/@{{deadline}}/g, deadline);
            emailBody.value = body;
            modal.classList.remove('hidden');
        })
        .catch(err => {
            console.error('Failed to load email template', err);
            alert('Failed to load email template.');
        });
}

// 2. Fungsi Buka Modal Reminder
function openReminderModal(reviewerId, reviewerName, deadline) {
    window.selectedReviewerId = reviewerId;

    modalMode = "reminder";
    // Set Isi Modal
    modalTitle.innerText = "Send Reminder to Reviewer";
    modalRecipient.value = reviewerName;
    emailSubject.value = "Review Reminder: " + articleTitle;

    fetch('/prepared-email/review_reminder')
        .then(res => res.json())
        .then(data => {

            // Subject dari DB (fallback jika kosong)
            emailSubject.value = data.subject && data.subject.trim() !== '' ?
                data.subject :
                "Review Reminder: " + articleTitle;

            // Body dari DB + replace placeholder
            let body = data.body ?? '';

            body = body.replace(/@{{reviewerName}}/g, reviewerName);
            body = body.replace(/@{{articleTitle}}/g, articleTitle);
            body = body.replace(/@{{deadline}}/g, deadline);
            body = body.replace(/@{{assignedBy}}/g, editorName);

            emailBody.value = body;

            modal.classList.remove('hidden');
        })
        .catch(err => {
            console.error('Failed to load reminder template', err);
            alert('Failed to load reminder email template.');
        });
}

function openAssignSectionEditorModal() {
    modalMode = "section_editor";

    const editorSelect = document.getElementById('editorSelect');
    const selected = Array.from(editorSelect.selectedOptions);

    if (selected.length === 0) {
        alert("Please select at least one Section Editor.");
        return;
    }

    const names = selected
        .map(opt => opt.text)
        .join(', ');

    modalTitle.innerText = "Assign Section Editor & Send Email";
    modalRecipient.value = names;
    fetch('/prepared-email/assign_section_editor')
        .then(res => res.json())
        .then(data => {

            emailSubject.value = data.subject;

            let body = data.body;

            body = body.replace(/@{{sectionEditorName}}/g, names);
            body = body.replace(/@{{articleTitle}}/g, articleTitle);
            body = body.replace(/@{{assignedBy}}/g, editorName);

            emailBody.value = body;
            modal.classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            alert('Failed to load Section Editor email template.');
        });
}

// 3. Fungsi Tutup Modal
function closeModal() {
    modal.classList.add('hidden');
}

// 4. Simulasi Submit
function submitProcess() {
    if (modalMode === "section_editor") {

        const editorSelect = document.getElementById('editorSelect');
        const selectedIds = Array.from(editorSelect.selectedOptions)
            .map(opt => opt.value);

        document.getElementById('seEditorsInput').value = selectedIds.join(',');
        document.getElementById('seSubjectInput').value = emailSubject.value;
        document.getElementById('seBodyInput').value = emailBody.value;
        document.getElementById('seSendEmailInput').value =
            document.getElementById('skipEmail')?.checked ? 0 : 1;

        document.getElementById('assignSectionEditorForm').submit();
        return;
    }

    if (modalMode === "assign") {

        const deadlineInputEl = document.querySelector('input[type="date"]');
        const deadlineValue = deadlineInputEl ? deadlineInputEl.value : '';

        if (!deadlineValue) {
            alert("Please select a deadline before proceeding.");
            deadlineInputEl.focus();
            return;
        }

        // kirim daftar reviewer
        document.getElementById('reviewersInput').value = reviewerSelectInstance.items.join(',');

        // kirim deadline
        document.getElementById('deadlineInput').value = deadlineValue;

        // kirim custom subject
        document.getElementById('subjectInput').value = emailSubject.value;

        // kirim custom email body
        document.getElementById('bodyInput').value = emailBody.value;

        // kirim switch email (skip atau tidak)
        document.getElementById('sendEmailInput').value = document.getElementById('skipEmail')?.checked ? 0 : 1;

        document.getElementById('assignForm').submit();
        return;
    }

    // --- PROCESS REMINDER EMAIL ---
    if (modalMode === "reminder") {
        // buatkan form khusus reminder
        const form = document.createElement('form');
        form.method = "POST";
        form.action = "/editor/" + paperId + "/send-reminder";

        const csrf = document.createElement('input');
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";

        const body = document.createElement('input');
        body.type = "hidden";
        body.name = "email_body";
        body.value = emailBody.value;

        const subject = document.createElement('input');
        subject.type = "hidden";
        subject.name = "subject";
        subject.value = emailSubject.value;

        const reviewerIdInput = document.createElement('input');
        reviewerIdInput.type = "hidden";
        reviewerIdInput.name = "reviewer_id";
        reviewerIdInput.value = window.selectedReviewerId;

        form.appendChild(reviewerIdInput);
        form.appendChild(csrf);
        form.appendChild(body);
        form.appendChild(subject);

        document.body.appendChild(form);
        form.submit();
    }

    // --- PROCESS THANK YOU EMAIL ---
    if (modalMode === "appreciation_mail") {

        const form = document.createElement('form');
        form.method = "POST";
        form.action = "/editor/" + paperId + "/send-appreciation-mail";

        const csrf = document.createElement('input');
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";

        const reviewerId = document.createElement('input');
        reviewerId.type = "hidden";
        reviewerId.name = "reviewer_id";
        reviewerId.value = window.selectedReviewerId;

        const subject = document.createElement('input');
        subject.type = "hidden";
        subject.name = "subject";
        subject.value = emailSubject.value;

        const body = document.createElement('input');
        body.type = "hidden";
        body.name = "email_body";
        body.value = emailBody.value;

        form.appendChild(csrf);
        form.appendChild(reviewerId);
        form.appendChild(subject);
        form.appendChild(body);

        document.body.appendChild(form);
        form.submit();
        return;
    }
}

function unassignReviewer(reviewerId) {
    if (!confirm("Remove this reviewer from the assignment?")) return;

    const form = document.createElement('form');
    form.method = "POST";
    form.action = "/editor/" + paperId + "/unassign-reviewer";

    // CSRF Token
    const csrf = document.createElement('input');
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";

    // Reviewer ID
    const reviewerInput = document.createElement('input');
    reviewerInput.type = "hidden";
    reviewerInput.name = "reviewer_id";
    reviewerInput.value = reviewerId;

    form.appendChild(csrf);
    form.appendChild(reviewerInput);

    document.body.appendChild(form);
    form.submit();
}

const REVIEW_QUESTIONS = {
    Q1: {
        question: "Is the presentation of the work clear?",
        options: {
            yes: "Yes",
            no1: "No, it's not suitable for publication unless extensively edited",
            no2: "No, it needs some language corrections before being published"
        }
    },
    Q2: {
        question: "Is the study design appropriate and supported by evidence?",
        options: {
            yes: "Yes",
            no1: "No, but these points can be addressed with revisions",
            no2: "No, and there are fundamental issues that cannot be addressed"
        }
    },
    Q3: {
        question: "Are the methods sufficiently described to allow replication?",
        options: {
            yes: "Yes",
            no1: "No, but these points can be addressed with revisions",
            no2: "No, and there are fundamental issues that cannot be addressed"
        }
    }
};


function openReviewModal(button) {
    const data = JSON.parse(button.dataset.review);

    const modal = document.getElementById('reviewModal');
    const content = document.getElementById('reviewModalContent');

    function renderQuestion(key) {
        if (!REVIEW_QUESTIONS[key]) return '';

        const q = REVIEW_QUESTIONS[key];
        const answerValue = data[key];
        const answerText = q.options[answerValue] ?? 'Tidak diisi';

        return `
            <div class="mb-3">
                <p class="font-semibold text-sm">${key}. ${q.question}</p>
                <p class="text-sm text-gray-700 ml-4">
                    <strong>Answer:</strong> ${answerText}
                </p>
            </div>
        `;
    }

    content.innerHTML = `
        <div class="border rounded p-4 mb-4">
            <p class="text-sm text-gray-500 mb-1">
                Reviewer: <strong>${data.reviewer}</strong> |
                ${data.reviewed_at ?? '-'}
            </p>

            <p class="mb-3">
                <strong>Recommendation:</strong>
                ${data.recommendation ?? '-'}
            </p>

            <div class="mb-4">
                <strong>Reviewer Evaluation:</strong>
                ${renderQuestion('Q1')}
                ${renderQuestion('Q2')}
                ${renderQuestion('Q3')}
            </div>

            <p class="mb-3">
                <strong>Comments for Author:</strong><br>
                ${data.author_comment ?? '-'}
            </p>

            ${
                data.editor_comment
                ? `<p class="mb-3 text-red-600">
                    <strong>Confidential (Editor):</strong><br>
                    ${data.editor_comment}
                   </p>`
                : ''
            }

            ${
                data.file
                ? `
                    <a href="/storage/${data.file.path}"
                    target="_blank"
                    class="inline-block mt-2 text-blue-600 hover:underline text-sm">
                    📄 ${data.file.name}
                    </a>
                `
                : `<p class="text-sm text-gray-400 italic mt-2">No review file attached.</p>`
            }




        </div>
    `;

    modal.classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}

function openThankYouModal(reviewerId, reviewerName) {
    modalMode = "appreciation_mail";
    window.selectedReviewerId = reviewerId;

    modalTitle.innerText = "Send Thank You Email";
    modalRecipient.value = reviewerName;

    fetch('/prepared-email/thank_you_for_review')
        .then(res => res.json())
        .then(data => {
            emailSubject.value = data.subject ?? '';

            let body = data.body ?? '';
            body = body.replace(/@{{reviewerName}}/g, reviewerName);
            body = body.replace(/@{{articleTitle}}/g, articleTitle);
            body = body.replace(/@{{assignedBy}}/g, editorName);

            emailBody.value = body;
            modal.classList.remove('hidden');
        });
}
</script>

@endsection