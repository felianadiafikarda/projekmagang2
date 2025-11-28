{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor Dashboard')

@section('content')

{{-- TomSelect CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>



<div class="max-w-6xl mx-auto space-y-6 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">


        <div class="flex gap-2">
            <a href="{{ url()->current().'?page=list' }}"
                class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 transition">All Submissions</a>
        </div>
    </div>

    {{-- TABLE LIST --}}
    @if($page === 'list')
    <div class="bg-white border rounded shadow p-6">
        <h3 class="font-semibold mb-2">All Submissions</h3>

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
                'submitted' => 'bg-gray-300 text-gray-800',
                'assigned_to_section_editor' => 'bg-blue-300 text-blue-800',
                'editing' => 'bg-yellow-300 text-yellow-900',
                'reviewing' => 'bg-purple-300 text-purple-900',
                'revision' => 'bg-orange-300 text-orange-900',
                'accepted' => 'bg-green-300 text-green-900',
                'rejected' => 'bg-red-300 text-red-900',
                ];
                @endphp

                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                    <td class="p-2 border font-semibold text-gray-800">{{ $p->judul }}</td>
                    <td class="p-2 border">{{ $authors ?: '-' }}</td>
                    <td class="p-2 border text-center">
                        <span
                            class="px-2 py-1 rounded text-sm font-semibold {{ $statusColor[$p->status] ?? 'bg-gray-200 text-gray-700' }}">
                            {{ ucfirst(str_replace('_',' ', $p->status)) }}
                        </span>
                    </td>
                    <td class="p-2 border text-center">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="p-2 border text-center">
                        <a href="{{ url()->current() . '?page=assign&id=' . $p->id }}"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Detail
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




    {{-- ASSIGN PAGE --}}
    @if(($page === 'assign' || request('id')) && $paper)
    <div class="bg-white border rounded shadow p-6 relative">

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

            <div class="text-right mt-1">
                <span class="text-sm font-medium px-3 py-1 rounded 
    {{ $paper->status == 'in_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-50 text-blue-700' }}">
                    {{ ucfirst(str_replace('_',' ',$paper->status ?? 'unassigned')) }}
                </span>

            </div>
        </div>


        {{-- ASSIGN REVIEWER SECTION --}}
        <div class="mt-6 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Reviewer</h4>

            {{-- Multiselect Reviewer Dropdown --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Reviewers</label>
                <select id="reviewerSelect" name="reviewers[]" multiple placeholder="Cari dan pilih Reviewer..."
                    autocomplete="off">
                    @foreach($all_reviewers as $rev)
                    <option value="{{ $rev->id }}" @if(in_array($rev->id, $assignedReviewers->pluck('id')->toArray()))
                        selected @endif>
                        {{ $rev->first_name . ' ' . $rev->last_name }}
                    </option>

                    @endforeach

                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline for selected reviewers</label>
                <input type="date" class="border rounded p-2 w-full md:w-64 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- TOMBOL UPDATE: Memicu Modal Assign --}}
            <button type="button" onclick="openAssignModal()"
                class="mt-2 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-sm transition duration-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Request & Assign Reviewers
            </button>
        </div>


        {{-- ASSIGN SECTION EDITOR SECTION --}}
        <div class="mt-10 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Section Editor</h4>

            {{-- Multiselect Section Editor Dropdown --}}
            <div class="mb-4">
                <select id="editorSelect" name="section_editors[]" multiple placeholder="Pilih Section Editor..."
                    autocomplete="off">
                    @foreach($all_section_editors as $se)
                    <option value="{{ $se->id }}" @if(in_array($se->id,
                        $assignedSectionEditors->pluck('id')->toArray())) selected @endif>
                        {{ $se->first_name . ' ' . $se->last_name }}
                    </option>

                    @endforeach
                </select>
            </div>

            <button
                class="mt-2 bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 shadow-sm transition duration-200">
                Assign Selected Editors
            </button>
        </div>


        {{-- LIST ASSIGNED REVIEWERS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Reviewers</h4>

            <div class="space-y-4">
                @forelse($assignedReviewers as $ar)
                <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-lg text-gray-800">{{$ar->first_name . ' ' . $ar->last_name  }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Deadline: <span class="font-medium">{{ fmt($ar->deadline) }}</span>
                            </div>
                            <div class="text-sm mt-1 flex items-center gap-2">
                                Status:
                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                    {{ $ar->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $ar->status === 'assigned' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $ar->status === 'declined' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($ar->status) }}
                                </span>
                            </div>
                            @if($ar->status==='completed' && $ar->recommendation)
                            <div class="text-sm mt-2 p-2 bg-gray-50 rounded text-gray-700 border border-gray-200">
                                Recommendation: <strong>{{ $ar->recommendation }}</strong>
                            </div>
                            @endif
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-col gap-2 items-end">
                            {{-- TOMBOL UPDATE: Memicu Modal Reminder --}}
                            @if($ar->status === 'assigned' || $ar->status === 'declined')
                            <button type="button"
                                onclick="openReminderModal('{{ $ar->reviewer_name }}', '{{ fmt($ar->deadline) }}')"
                                class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 transition shadow-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                </svg>
                                Send Reminder
                            </button>
                            @endif

                            @if($ar->status === 'completed')
                            <button
                                class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 transition shadow-sm">
                                Read Review
                            </button>
                            @endif

                            <button
                                class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded transition">
                                Unassign
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-sm text-gray-500 p-4 border border-dashed rounded text-center">No reviewers assigned
                    yet.</div>
                @endforelse
            </div>
        </div>


        {{-- LIST ASSIGNED SECTION EDITORS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Section Editors</h4>

            <div class="space-y-2">
                @forelse($assignedSectionEditors as $ase)
                <div class="border rounded p-3 shadow-sm flex justify-between items-center bg-gray-50">
                    <span class="font-medium text-gray-700">{{$ase->first_name . ' ' . $ase->last_name}}</span>
                    <a href="#" class="text-xs text-red-500 hover:text-red-700 font-medium hover:underline">
                        Unassign
                    </a>
                </div>
                @empty
                <div class="text-sm text-gray-500 p-3 border border-dashed rounded text-center">No section editors
                    assigned yet.</div>
                @endforelse
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
        {{-- Modal Header --}}
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white text-lg font-semibold" id="modalTitle">Add Reviewer & Send Email</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 space-y-4">

            {{-- Recipient Display --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Reviewer(s)</label>
                <input type="text" id="modalRecipientName" readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-600 focus:outline-none">
            </div>

            {{-- Email Subject --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="emailSubject"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Email Body --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                <textarea id="emailBody" rows="8"
                    class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                <p class="text-xs text-gray-500 mt-1">You can edit the message above before sending.</p>
            </div>

            {{-- Checkbox --}}
            <div class="flex items-center">
                <input id="skipEmail" type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="skipEmail" class="ml-2 block text-sm text-gray-900">
                    Do not send email to Reviewer (Assign only).
                </label>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
            <button onclick="closeModal()"
                class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button onclick="submitProcess()"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow-sm transition">Send &
                Process</button>
        </div>
    </div>
</div>

</div>


{{-- JAVASCRIPT LOGIC --}}
<script>
// --- Variabel Data dari PHP ke JS ---
const articleTitle = "{{ $paper->judul ?? 'Judul Artikel' }}";

// Nama jurnal tetap statis atau bisa diambil dari config/db
const journalName = "{{ $journalName ?? 'Jurnal Pemberdayaan: Publikasi Hasil Pengabdian Kepada Masyarakat' }}";


const articleUrl = "{{ $paper->file_path ? asset('storage/' . $paper->file_path) : '#' }}";


const editorName = "{{ $editors->first()->name ?? 'Editor' }}";


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
const modalRecipient = document.getElementById('modalRecipientName');
const emailSubject = document.getElementById('emailSubject');
const emailBody = document.getElementById('emailBody');

// 1. Fungsi Buka Modal Assign Reviewer
function openAssignModal() {
    if (!reviewerSelectInstance) return;

    // Ambil data reviewer yang dipilih
    const selectedItems = reviewerSelectInstance.items; // Array ID
    const selectedOptions = reviewerSelectInstance.options; // Object Data

    if (selectedItems.length === 0) {
        alert("Please select at least one reviewer first.");
        return;
    }

    // Gabungkan nama reviewer
    let names = selectedItems.map(id => selectedOptions[id].text).join(", ");

    // Set Isi Modal
    modalTitle.innerText = "Assign Reviewer & Send Invitation";
    modalRecipient.value = names;
    emailSubject.value = "Invitation to Review Manuscript";

    // Template Pesan Assign
    const template = `Dear ${names},

    I believe that you would serve as an excellent reviewer of the manuscript, "${articleTitle},".

The submission's abstract is inserted below, and I hope that you will consider undertaking this important task for us.

Please log into the journal web site to indicate whether you will undertake the review or not.

Submission URL: ${articleUrl}

Thank you for considering this request.

${editorName}
Editor`;

    emailBody.value = template;

    // Tampilkan Modal
    modal.classList.remove('hidden');
}

// 2. Fungsi Buka Modal Reminder
function openReminderModal(reviewerName, deadline) {
    // Set Isi Modal
    modalTitle.innerText = "Send Reminder to Reviewer";
    modalRecipient.value = reviewerName;
    emailSubject.value = "Review Reminder: " + articleTitle;

    // Template Pesan Reminder
    const template = `Dear ${reviewerName},

Just a gentle reminder regarding the manuscript "${articleTitle}" which is currently assigned to you for review.

We noticed that the deadline is approaching (${deadline}). We would appreciate it if you could submit your review soon.

Submission URL: ${articleUrl}

Best regards,

${editorName}
Editor`;

    emailBody.value = template;
    modal.classList.remove('hidden');
}

// 3. Fungsi Tutup Modal
function closeModal() {
    modal.classList.add('hidden');
}

// 4. Simulasi Submit (Ganti dengan form submit asli jika backend siap)
function submitProcess() {

    const deadlineInput = document.querySelector('input[type="date"]');
    const deadlineValue = deadlineInput ? deadlineInput.value : '';

    // Validasi: jika kosong, jangan lanjut
    if (!deadlineValue) {
        alert("Please select a deadline for the selected reviewers before proceeding.");
        deadlineInput.focus();
        return; // hentikan proses
    }

    const isSkip = document.getElementById('skipEmail').checked;
    if (isSkip) {
        alert("Success! Reviewer assigned without sending email.");
    } else {
        alert("Success! Email sent and Reviewer assigned.");
    }
    closeModal();
    // document.getElementById('assignForm').submit(); 
}
</script>

@endsection