{{-- resources/views/sectionEditor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Section Editor Dashboard')

@section('content')

{{-- TomSelect CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="max-w-7xl rounded-xl mx-auto space-y-6 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('section_editor.index') }}?page=list"
                class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 transition">All Submissions</a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        {{ session('error') }}
    </div>
    @endif

    {{-- TABLE LIST --}}
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
                    <td class="p-2 border text-center">
                        <span class="px-2 py-1 rounded text-sm font-semibold {{ $statusColor[$p->status] ?? 'bg-gray-200 text-gray-700' }}">
                            {{ ucfirst(str_replace('_',' ', $p->status)) }}
                        </span>
                    </td>
                    <td class="p-2 border text-center">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="p-2 border text-center">
                        <a href="{{ route('section_editor.detail', $p->id) }}"
                            class="px-3 py-1 bg-gray-300 text-gray-800 rounded hover:bg-gray-500 mr-2">
                            Detail
                        </a>

                        <a href="{{ route('section_editor.index') }}?page=assign&id={{ $p->id }}"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Edit
                        </a>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">No papers assigned to you yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    {{-- ASSIGN PAGE --}}
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

            <form id="assignForm" method="POST" action="{{ route('section_editor.assignReviewers', $paper->id) }}">
                @csrf
                <input type="hidden" id="subjectInput" name="subject">
                <input type="hidden" id="bodyInput" name="email_body">
                <input type="hidden" id="reviewersInput" name="reviewers">
                <input type="hidden" id="deadlineInput" name="deadline">
                <input type="hidden" id="sendEmailInput" name="send_email" value="1">

                {{-- Multiselect Reviewer Dropdown --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Reviewers</label>
                    <select id="reviewerSelect" name="reviewers[]" multiple placeholder="Cari dan pilih Reviewer..." autocomplete="off">
                        @foreach($all_reviewers as $rev)
                        <option value="{{ $rev->id }}" 
                            data-active="{{ $rev->active_papers }}"
                            @if(in_array($rev->id, $assignedReviewers->pluck('id')->toArray())) selected @endif>
                            {{ $rev->first_name . ' ' . $rev->last_name }} (Active Reviews : {{ $rev->active_papers }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline for selected reviewers</label>
                    <input type="date" id="deadlineDate" name="deadline"
                        class="border rounded p-2 w-full md:w-64 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- TOMBOL UPDATE: Memicu Modal Assign --}}
                <button type="button" onclick="openAssignModal()"
                    class="mt-2 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-sm transition duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Send Request & Assign Reviewers
                </button>
            </form>
        </div>

        {{-- LIST ASSIGNED REVIEWERS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Reviewers</h4>

            <div class="space-y-4">
                @forelse($assignedReviewers as $ar)
                <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            {{-- NAMA REVIEWER --}}
                            <div class="font-medium text-lg text-gray-800">
                                {{ $ar->first_name . ' ' . $ar->last_name }}
                            </div>

                            {{-- DEADLINE --}}
                            <div class="text-sm text-gray-500">
                                Deadline:
                                <span class="font-medium">
                                    {{ date('d M Y', strtotime($ar->pivot->deadline)) }}
                                </span>
                            </div>

                            {{-- STATUS --}}
                            <div class="text-sm mt-1 flex items-center gap-2">
                                Status:

                                @php $status = $ar->pivot->status; @endphp

                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                    @if($status === 'completed') bg-green-100 text-green-700 @endif
                                    @if($status === 'assigned') bg-blue-100 text-blue-700 @endif
                                    @if($status === 'accepted') bg-indigo-100 text-indigo-700 @endif
                                    @if($status === 'declined') bg-red-100 text-red-700 @endif
                                ">
                                    {{ ucwords(str_replace('_',' ', $status)) }}
                                </span>
                            </div>

                            {{-- RECOMMENDATION DARI REVIEWER --}}
                            @if($status === 'completed' && $ar->pivot->recommendation)
                            <div class="text-sm mt-2 p-2 bg-gray-50 rounded text-gray-700 border border-gray-200">
                                Recommendation:
                                <strong>{{ $ar->pivot->recommendation }}</strong>
                            </div>
                            @endif
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-col gap-2 items-end">

                            {{-- TOMBOL REMINDER --}}
                            @if(in_array($status, ['assigned', 'accepted', 'declined']))
                            <button type="button"
                                onclick="openReminderModal('{{ $ar->id }}', '{{ $ar->first_name . ' ' . $ar->last_name }}', '{{ $ar->pivot->deadline }}')"
                                class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 transition shadow-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                </svg>
                                Send Reminder
                            </button>
                            @endif

                            {{-- TOMBOL READ REVIEW --}}
                            @if($status === 'completed')
                            <button class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 transition shadow-sm">
                                Read Review
                            </button>
                            @endif

                            {{-- TOMBOL UNASSIGN --}}
                            <button onclick="unassignReviewer('{{ $ar->id }}')"
                                class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded transition">
                                Unassign
                            </button>
                        </div>
                    </div>
                </div>

                @empty
                <div class="text-sm text-gray-500 p-4 border border-dashed rounded text-center">
                    No reviewers assigned yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ route('section_editor.index') }}?page=list"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Back
        </a>
    </div>

    @endif

</div>

{{-- MODAL EMAIL (Hidden by default) --}}
<div id="emailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
        {{-- Modal Header --}}
        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white text-lg font-semibold" id="modalTitle">Add Reviewer & Send Email</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 space-y-4">

            {{-- Recipient Display --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selected Reviewer(s)</label>
                <input type="text" id="modalRecipient" readonly
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
            <div class="flex items-center mb-4">
                <input type="checkbox" id="skipEmail" name="send_email" value="0"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                    onclick="document.getElementById('sendEmailInput').value = this.checked ? 0 : 1">

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
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow-sm transition">Send & Process</button>
        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
// --- Variabel Data dari PHP ke JS ---
const articleTitle = "{{ $paper->judul ?? 'Judul Artikel' }}";
const articleUrl = "{{ $articleUrl ?? '#' }}";
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
                    
                    return `<div class="py-2 px-3 hover:bg-blue-50 border-b border-gray-100 last:border-0">
                                <div class="font-medium text-gray-800">${escape(data.text.split(' (')[0])}</div>
                                <div class="text-xs text-green-600">Active Reviews : ${activePapers}</div>
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

    const selectedItems = reviewerSelectInstance.items;
    const selectedOptions = reviewerSelectInstance.options;

    if (selectedItems.length === 0) {
        alert("Please select at least one reviewer first.");
        return;
    }

    const deadlineInputEl = document.getElementById('deadlineDate');
    const deadline = deadlineInputEl ? deadlineInputEl.value : '';
    if (!deadline) {
        alert('Please set a deadline.');
        deadlineInputEl.focus();
        return;
    }

    let names = selectedItems.map(id => selectedOptions[id].text.split(' (')[0]).join(", ");

    modalTitle.innerText = "Assign Reviewer & Send Invitation";
    modalRecipient.value = names;
    emailSubject.value = "Invitation to Review Manuscript";

    const template = `Dear ${names},

I believe that you would serve as an excellent reviewer of the manuscript, "${articleTitle}".

Please log into the journal web site to indicate whether you will undertake the review or not.

Submission URL: ${articleUrl}

Thank you for considering this request.

${editorName}`;

    emailBody.value = template;

    modal.classList.remove('hidden');
}

// 2. Fungsi Buka Modal Reminder
function openReminderModal(reviewerId, reviewerName, deadline) {
    window.selectedReviewerId = reviewerId;
    modalMode = "reminder";
    
    modalTitle.innerText = "Send Reminder to Reviewer";
    modalRecipient.value = reviewerName;
    emailSubject.value = "Review Reminder: " + articleTitle;

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

// 4. Submit Process
function submitProcess() {
    if (modalMode === "assign") {
        const deadlineInputEl = document.querySelector('input[type="date"]');
        const deadlineValue = deadlineInputEl ? deadlineInputEl.value : '';

        if (!deadlineValue) {
            alert("Please select a deadline before proceeding.");
            deadlineInputEl.focus();
            return;
        }

        document.getElementById('reviewersInput').value = reviewerSelectInstance.items.join(',');
        document.getElementById('deadlineInput').value = deadlineValue;
        document.getElementById('subjectInput').value = emailSubject.value;
        document.getElementById('bodyInput').value = emailBody.value;
        document.getElementById('sendEmailInput').value = document.getElementById('skipEmail')?.checked ? 0 : 1;

        document.getElementById('assignForm').submit();
        return;
    }

    // --- PROCESS REMINDER EMAIL ---
    if (modalMode === "reminder") {
        const form = document.createElement('form');
        form.method = "POST";
        form.action = "/section-editor/" + paperId + "/send-reminder";

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
    form.action = "/section-editor/" + paperId + "/unassign-reviewer";

    const csrf = document.createElement('input');
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";

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
