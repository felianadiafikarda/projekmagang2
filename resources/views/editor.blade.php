{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor Dashboard')

@section('content')

{{-- TomSelect CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="max-w-7xl rounded-xl mx-auto space-y-6 py-6">

    {{-- === HEADER: NAVIGASI, FILTER & SEARCH === --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">
        
        {{-- Kiri: Tombol Navigasi --}}
        <div class="flex gap-2">
            <a href="{{ url()->current().'?page=list' }}"
                class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 transition font-medium text-gray-700">
                All Submissions
            </a>
        </div>

        {{-- Kanan: Filter Status & Search Input --}}
        @if($page === 'list')
        <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="page" value="list">
            
            {{-- Dropdown Filter --}}
            <select name="filter_status" onchange="this.form.submit()" 
                class="w-full md:w-48 border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 cursor-pointer bg-white text-gray-700">
                <option value="">All Status</option>
                @foreach(['Unassign', 'In Review', 'Rejected', 'Accept with Review', 'Accepted'] as $status)
                    <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>

            {{-- Input Search --}}
            <div class="relative w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search..." 
                    class="w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 pr-10">
                
                {{-- Tombol Search --}}
                <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-white bg-gray-900 hover:bg-gray-700 rounded-r-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
                $authors = $p->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ');
                $statusColor = [
                    'Unassign' => 'bg-gray-300 text-gray-800',
                    'In Review' => 'bg-yellow-300 text-yellow-900',
                    'Rejected' => 'bg-red-300 text-red-900',
                    'Accept with Review' => 'bg-yellow-300 text-yellow-900',
                    'Accepted' => 'bg-green-300 text-green-900',
                ];
                @endphp

                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                    <td class="p-2 border font-semibold text-gray-800">{{ $p->judul }}</td>
                    <td class="p-2 border">{{ $authors ?: '-' }}</td>
                    
                    {{-- STATUS (TEXT LABEL BIASA) --}}
                    <td class="p-2 border text-center">
                        <span class="px-2 py-1 rounded text-sm font-semibold {{ $statusColor[$p->status] ?? 'bg-gray-200 text-gray-700' }}">
                            {{ ucfirst(str_replace('_',' ', $p->status)) }}
                        </span>
                    </td>

                    <td class="p-2 border text-center">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="p-2 border text-center">
                        <a href="{{ route('editor.detail', $p->id) }}"
                            class="px-3 py-1 bg-gray-300 text-gray-800 rounded hover:bg-gray-500 mr-2">
                            Detail
                        </a>

                        <a href="{{ url()->current().'?page=assign&id='.$p->id }}"
                            class="px-3 py-1 bg-gray-900 text-white rounded hover:bg-gray-700">
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

            {{-- [MODIFIKASI] 3 TOMBOL AKSI PENGGANTI DROPDOWN --}}
            <div class="text-right w-56">
                <form action="{{ route('editor.updateStatus', $paper->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="flex flex-col gap-2">
                        {{-- 1. Request Revisions (Abu-abu) --}}
                        <button type="submit" name="status" value="Accept with Review"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow-sm text-sm transition text-left border border-gray-300">
                            Request Revisions
                        </button>

                        {{-- 2. Accept Submission (Biru) --}}
                        <button type="submit" name="status" value="Accepted"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition text-left">
                            Accept Submission
                        </button>

                        {{-- 3. Decline Submission (Pink/Merah) --}}
                        <button type="submit" name="status" value="Rejected"
                            class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition text-left">
                            Decline Submission
                        </button>
                    </div>
                </form>
            </div>
            {{-- [AKHIR MODIFIKASI] --}}
        </div>


        {{-- ========================================================== --}}
        {{-- 1. GROUP: ASSIGN SECTION EDITOR --}}
        {{-- ========================================================== --}}
        <div class="mt-8 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Section Editor</h4>

            <div class="bg-gray-50 border rounded-lg p-4">
                
                {{-- Form --}}
                <form method="POST" action="{{ route('editor.assignSectionEditor', $paper->id) }}" class="mb-4">
                    @csrf
                    <div class="flex gap-2">
                        <div class="flex-grow">
                            <select id="editorSelect" name="section_editors[]" multiple placeholder="Select Section Editor...">
                                @foreach($all_section_editors as $se)
                                <option value="{{ $se->id }}" @if(in_array($se->id, $assignedSectionEditors->pluck('id')->toArray())) selected @endif>
                                    {{ $se->first_name . ' ' . $se->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700 text-sm font-medium whitespace-nowrap h-[38px] mt-[1px]">
                            Assign Selected
                        </button>
                    </div>
                </form>

                {{-- List Assigned --}}
                <div class="mt-2 border-t border-gray-200 pt-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Assigned Section Editors List</label>
                    <div class="space-y-2">
                        @forelse($assignedSectionEditors as $ase)
                        <div class="flex justify-between items-center bg-white p-2.5 rounded border shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">SE</div>
                                <span class="font-medium text-gray-700">{{$ase->first_name . ' ' . $ase->last_name}}</span>
                            </div>
                            <form method="POST" action="{{ route('editor.unassignSectionEditor', $paper->id) }}">
                                @csrf
                                <input type="hidden" name="editor_id" value="{{ $ase->id }}">
                                <button class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline"
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
                            <select id="reviewerSelect" name="reviewers[]" multiple placeholder="Cari dan pilih Reviewer..." autocomplete="off">
                                @foreach($all_reviewers as $rev)
                                <option value="{{ $rev->id }}" @if(in_array($rev->id, $assignedReviewers->pluck('id')->toArray())) selected @endif>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Request & Assign Reviewers
                    </button>
                </form>

                {{-- List Assigned --}}
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Assigned Reviewers List</label>
                    <div class="space-y-4">
                        @forelse($assignedReviewers as $ar)
                        <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition duration-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-lg text-gray-800">
                                        {{ $ar->first_name . ' ' . $ar->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Deadline: <span class="font-medium">{{ date('d M Y', strtotime($ar->pivot->deadline)) }}</span>
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
                                    <div class="text-sm mt-2 p-2 bg-gray-50 rounded text-gray-700 border border-gray-200">
                                        Recommendation: <strong>{{ $ar->pivot->recommendation }}</strong>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 items-end">
                                    @if(in_array($status, ['assigned', 'accept_to_review', 'decline_to_review']))
                                    <button type="button" onclick="openReminderModal('{{ $ar->id }}', '{{ $ar->first_name . ' ' . $ar->last_name }}', '{{ $ar->pivot->deadline }}')" 
                                        class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 transition shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                        </svg> Send Reminder
                                    </button>
                                    @endif

                                    @if($status === 'completed')
                                    <button class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 transition shadow-sm">Read Review</button>
                                    @endif

                                    @if($assignedReviewers->contains('id', $ar->id))
                                    <button onclick="unassignReviewer('{{ $ar->id }}')" class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded transition">Unassign</button>
                                    @else
                                    <button onclick="assignReviewer('{{ $ar->id }}')" class="text-sm text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-1 rounded transition">Assign</button>
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
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Reviewer(s)</label>
                <input type="text" id="modalRecipient" readonly class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="emailSubject" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-gray-900 focus:border-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                <textarea id="emailBody" rows="8" class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm focus:ring-gray-900 focus:border-gray-900"></textarea>
                <p class="text-xs text-gray-500 mt-1">You can edit the message above before sending.</p>
            </div>
            @if(isset($modalType) && $modalType === 'assign')
            <div class="flex items-center mb-4">
                <input type="checkbox" id="skipEmail" name="send_email" value="0" class="h-4 w-4 text-blue-600 border-gray-300 rounded" onclick="document.getElementById('sendEmailInput').value = this.checked ? 0 : 1">
                <label for="skipEmail" class="ml-2 block text-sm text-gray-900">Do not send email to Reviewer (Assign only).</label>
            </div>
            @endif
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button onclick="submitProcess()" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-700 shadow-sm transition">Send & Process</button>
        </div>
    </div>
</div>

</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
// --- Variabel Data dari PHP ke JS ---
const articleTitle = "{{ $paper->judul ?? 'Judul Artikel' }}";
const articleUrl = "{{ $articleUrl ?? '#' }}"
const editorName = "{{ $editors->first()->name ?? 'Editor' }}";
const paperId = "{{ $paper->id ?? '' }}";
let reviewerSelectInstance;

document.addEventListener('DOMContentLoaded', function() {
    // Init TomSelect Reviewer
    if (document.getElementById("reviewerSelect")) {
        reviewerSelectInstance = new TomSelect("#reviewerSelect", {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: 'Cari dan pilih Reviewer...',
            render: {
                option: function(data, escape) {
                    return `<div class="py-2 px-3 hover:bg-blue-50 border-b border-gray-100 last:border-0">
                                    <div class="font-medium text-gray-800">${escape(data.text)}</div>
                                    <div class="text-xs text-green-600">Available Reviewer</div>
                                </div>`;
                },
                item: function(data, escape) {
                    return `<div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full mr-1 flex items-center shadow-sm border border-blue-200">
                                    ${escape(data.text)}
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
        });
    }
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

    // Gabungkan nama reviewer
    let names = selectedItems.map(id => selectedOptions[id].text).join(", ");

    // Set isi modal
    modalTitle.innerText = "Assign Reviewer & Send Invitation";
    modalRecipient.value = names;
    emailSubject.value = "Invitation to Review Manuscript";

    // TEMPLATE PESAN ASLI (Multi-line)
    const template = `Dear ${names},

I believe that you would serve as an excellent reviewer of the manuscript, "${articleTitle},".

Please log into the journal web site to indicate whether you will undertake the review or not.

Submission URL: ${articleUrl}

Thank you for considering this request.

${editorName}`;

    emailBody.value = template;

    // Tampilkan modal
    modal.classList.remove('hidden');
}

// 2. Fungsi Buka Modal Reminder
function openReminderModal(reviewerId, reviewerName, deadline) {
    window.selectedReviewerId = reviewerId;

    modalMode = "reminder";
    // Set Isi Modal
    modalTitle.innerText = "Send Reminder to Reviewer";
    modalRecipient.value = reviewerName;
    emailSubject.value = "Review Reminder: " + articleTitle;

    // TEMPLATE PESAN REMINDER ASLI (Multi-line)
    const template = `Dear ${reviewerName},

Just a gentle reminder regarding the manuscript "${articleTitle}" which is currently assigned to you for review.

We noticed that the deadline is approaching (${deadline}). We would appreciate it if you could submit your review soon.



Best regards,

${editorName}`;

    emailBody.value = template;
    modal.classList.remove('hidden');
}


// 3. Fungsi Tutup Modal
function closeModal() {
    modal.classList.add('hidden');
}

// 4. Simulasi Submit
function submitProcess() {
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
</script>

@endsection