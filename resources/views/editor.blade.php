{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor – Manage Submissions')
@section('content')

@php
use Carbon\Carbon;

// page selector: prefer request('page'), otherwise existing $page, otherwise 'list'
$page = request('page') ?? ($page ?? 'list');
$idParam = request('id') ?? null;

// safe defaults: if controller didn't provide arrays/objects, create harmless dummy data
$articles = $articles ?? collect([
    (object)[
        'id' => 1,
        'title' => 'Model Prediksi Co-Authorship',
        'author_name' => 'Dewi Lestari',
        'status' => 'in_review',
        'created_at' => Carbon::now()->subDays(10),
    ],
    (object)[
        'id' => 2,
        'title' => 'Analisis Sistem Informasi Akademik',
        'author_name' => 'Rina Puspitasari',
        'status' => 'unassigned',
        'created_at' => Carbon::now()->subDays(4),
    ],
    (object)[
        'id' => 3,
        'title' => 'Sistem Kendali Motor DC',
        'author_name' => 'Andi Wijaya',
        'status' => 'accepted',
        'created_at' => Carbon::now()->subDays(30),
    ],
]);

// article selected for detail/assign view: use provided $article if exists, else find by idParam, else first article
if (!isset($article)) {
    if ($idParam) {
        $article = $articles->firstWhere('id', (int)$idParam) ?? null;
    }
    $article = $article ?? $articles->first();
}

// reviewers defaults (available reviewers to choose from)
$all_reviewers = $all_reviewers ?? collect([
    (object)['id'=>1,'name'=>'Dr. Sinta Maharani'],
    (object)['id'=>2,'name'=>'Prof. Rudi Santoso'],
    (object)['id'=>3,'name'=>'Much Fuad Saifuddin'],
    (object)['id'=>4,'name'=>'Dr. Anang Masduki'],
]);

// assigned reviewer records for the selected article (pivot-like objects)
// If controller provided $assignedReviewers, use it. Otherwise create sample assignments.
$assignedReviewers = $assignedReviewers ?? collect([
    (object)[
        'id' => 1,
        'reviewer_id' => 1,
        'reviewer_name' => 'Dr. Sinta Maharani',
        'status' => 'assigned', // assigned | accept | decline | completed
        'deadline' => Carbon::now()->addDays(7),
        'recommendation' => null,
    ],
    (object)[
        'id' => 2,
        'reviewer_id' => 2,
        'reviewer_name' => 'Prof. Rudi Santoso',
        'status' => 'completed',
        'deadline' => Carbon::now()->subDays(2),
        'recommendation' => 'Accept',
    ],
]);

// editor info fallback
$editor = $editor ?? (object)['name' => (auth()->check() ? auth()->user()->name : 'Editor in Charge')];

// helper: format date safely
function fmt($d) {
    return ($d instanceof \Carbon\Carbon) ? $d->format('d M Y') : (is_string($d) ? $d : (string)$d);
}
@endphp

<div class="max-w-6xl mx-auto space-y-6 py-6">

    {{-- ---------- Top: breadcrumbs / quick actions ---------- --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Editor Dashboard</h1>
            <p class="text-sm text-gray-600">Manage submissions — assign reviewers, monitor progress, make editorial decisions.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url()->current().'?page=list' }}" class="px-3 py-2 bg-gray-100 rounded">All Submissions</a>
            <a href="{{ url()->current().'?page=assign&id='.($article->id ?? '') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Manage Current</a>
        </div>
    </div>

    {{-- =========================
         MAIN LAYOUT: left list (col) + right content (col)
         ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- LEFT: Article list --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white border rounded shadow p-4">
                <h2 class="font-semibold mb-2">Submitted Articles</h2>
                <div class="text-sm text-gray-500 mb-3">Click an item to manage reviewers & decisions.</div>

                <div class="space-y-2">
                    @foreach($articles as $a)
                        <a href="{{ url()->current().'?page=assign&id='.$a->id }}" class="block p-3 rounded border hover:bg-gray-50">
                            <div class="flex justify-between">
                                <div>
                                    <div class="font-medium">{{ $a->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $a->author_name }}</div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $s = $a->status ?? 'unassigned';
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded
                                        @if($s=='unassigned') bg-gray-200 text-gray-700
                                        @elseif($s=='in_review') bg-blue-100 text-blue-700
                                        @elseif($s=='accept_revision') bg-yellow-100 text-yellow-700
                                        @elseif($s=='accepted') bg-green-100 text-green-700
                                        @elseif($s=='rejected') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst(str_replace('_',' ',$s)) }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">{{ fmt($a->created_at) }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: content area (list / assign / detail) --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- PAGE: LIST (if explicit) --}}
            @if($page === 'list')
                <div class="bg-white border rounded shadow p-6">
                    <h3 class="font-semibold mb-2">All Submissions</h3>
                    <p class="text-sm text-gray-500 mb-4">Select a submission to manage reviewers and make decisions.</p>

                    <table class="w-full text-left border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-2 border">Title</th>
                                <th class="p-2 border">Author</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $a)
                                <tr class="border-t">
                                    <td class="p-2">{{ $a->title }}</td>
                                    <td class="p-2">{{ $a->author_name }}</td>
                                    <td class="p-2">{{ ucfirst(str_replace('_',' ', $a->status)) }}</td>
                                    <td class="p-2">{{ fmt($a->created_at) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- PAGE: ASSIGN (default when id provided or page==assign) --}}
            @if($page === 'assign' || request('id'))
                <div class="bg-white border rounded shadow p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                            <div class="text-sm text-gray-500">Author: {{ $article->author_name }} • Submitted: {{ fmt($article->created_at) }}</div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-600">Current status</div>
                            <div class="mt-1 text-sm font-medium px-3 py-1 rounded bg-blue-50 text-blue-700">
                                {{ ucfirst(str_replace('_',' ', $article->status ?? 'unassigned')) }}
                            </div>
                        </div>
                    </div>

                    {{-- Assign form --}}
                    <div class="mt-6 border-t pt-4">
                        <h4 class="font-semibold mb-2">Assign Reviewer</h4>

                        {{-- note: this is view-only; form action left intentionally '#', replace later --}}
                        <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3" id="assignReviewerForm">
                            <div>
                                <label class="text-sm font-medium">Select reviewer</label>
                                <select class="w-full border rounded p-2" id="reviewerSelect">
                                    @foreach($all_reviewers as $rev)
                                        <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Deadline</label>
                                <input type="date" class="w-full border rounded p-2" id="deadlineInput">
                            </div>

                            <div class="flex items-end">
                                <button type="button" onclick="openEmailPreview()" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Send Request</button>
                            </div>
                        </form>
                    </div>

                    {{-- Assigned reviewers list --}}
                    <div class="mt-6">
                        <h4 class="font-semibold mb-3">Assigned Reviewers</h4>

                        <div class="space-y-3">
                            @forelse($assignedReviewers as $ar)
                                <div class="border rounded p-3 flex justify-between items-start">
                                    <div>
                                        <div class="font-medium">{{ $ar->reviewer_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Deadline: {{ fmt($ar->deadline) }}
                                        </div>

                                        <div class="text-xs mt-1
                                            @if($ar->status === 'assigned') text-gray-600
                                            @elseif($ar->status === 'accept') text-green-700
                                            @elseif($ar->status === 'decline') text-red-700
                                            @elseif($ar->status === 'completed') text-blue-700
                                            @endif">
                                            {{ ucfirst(str_replace('_',' ', $ar->status)) }}
                                        </div>

                                        @if($ar->status === 'completed' && isset($ar->recommendation))
                                            <div class="text-xs mt-1 text-gray-700">Recommendation: <strong>{{ $ar->recommendation }}</strong></div>
                                        @endif
                                    </div>

                                    <div class="text-sm space-y-2">
                                        @if($ar->status === 'assigned')
                                            <button onclick="openReminderPreview('{{ $ar->reviewer_name }}', '{{ fmt($ar->deadline) }}')" class="px-3 py-1 border rounded text-yellow-700">Send Reminder</button>
                                            <button class="px-3 py-1 border rounded text-red-600">Unassign</button>
                                        @elseif($ar->status === 'accept')
                                            <button onclick="openReminderPreview('{{ $ar->reviewer_name }}', '{{ fmt($ar->deadline) }}')" class="px-3 py-1 border rounded text-yellow-700">Send Reminder</button>
                                        @elseif($ar->status === 'completed')
                                            <button class="px-3 py-1 border rounded text-blue-700">Read Review</button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">No reviewers assigned yet.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Decision area --}}
                    <div class="mt-6 border-t pt-4">
                        <h4 class="font-semibold mb-2">Editor Decision</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <button class="px-4 py-2 bg-yellow-600 text-white rounded">Request Revision</button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded">Accept Submission</button>
                            <button class="px-4 py-2 bg-red-600 text-white rounded">Reject Submission</button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- PAGE: DETAIL (if explicitly requested) --}}
            @if($page === 'detail')
                <div class="bg-white border rounded shadow p-6">
                    <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $article->author_name }} • Submitted: {{ fmt($article->created_at) }}</p>

                    <div class="mt-4">
                        <h4 class="font-semibold">Article Status</h4>
                        <div class="p-3 bg-gray-50 rounded mt-2">{{ ucfirst(str_replace('_',' ', $article->status ?? 'unassigned')) }}</div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ url()->current().'?page=assign&id='.$article->id }}" class="px-4 py-2 bg-blue-600 text-white rounded">Manage Reviewers</a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Email Preview Modal --}}
    <div id="emailPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeEmailPreview(event)">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            {{-- Modal Header --}}
            <div class="bg-blue-500 text-white px-6 py-4">
                <h3 class="text-lg font-semibold">Add Reviewer</h3>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                {{-- Selected Reviewer Info --}}
                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700">Selected Reviewer</label>
                    <div class="mt-1 p-2 bg-gray-50 rounded" id="selectedReviewerName">-</div>
                </div>

                {{-- Email Content --}}
                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700">Email to be sent to reviewer</label>
                    <div class="mt-2 border rounded p-4 bg-gray-50 text-sm space-y-3">
                        <div>
                            <strong>Subject:</strong> Invitation to Review Manuscript
                        </div>
                        
                        <div class="border-t pt-3">
                            <p class="mb-2">Dear <span id="previewReviewerName">Dr. Reviewer</span>,</p>
                            
                            <p class="mb-2">I believe that you would serve as an excellent reviewer of the manuscript, "<strong>{{ $article->title ?? 'Manuscript Title' }}</strong>," which has been submitted to <strong>Jurnal Pemberdayaan: Publikasi Hasil Pengabdian Kepada Masyarakat</strong> on the section <strong>Research Article</strong>.</p>
                            
                            <p class="mb-2">The submission's abstract is inserted below, and I hope that you will consider undertaking this important task for us.</p>
                            
                            <p class="mb-2">Please log into the journal web site by <strong><span id="previewDeadline">RESPONSE DUE DATE</span></strong> to indicate whether you will undertake the review or not, as well as to access the submission and to record your review and recommendation.</p>
                            
                            <p class="mb-2">The review itself is due: <strong><span id="previewReviewDeadline">REVIEW DUE DATE</span></strong></p>
                            
                            <p class="mb-2"><strong>Submission URL:</strong> <span class="text-blue-600">URL</span></p>
                            
                            <p class="mb-2">Thank you for considering this request.</p>
                            
                            <p class="mt-4">
                                {{ $editor->name ?? 'Editor Name' }}<br>
                                Universitas Ahmad Dahlan
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Checkbox Option --}}
                <div class="flex items-center">
                    <input type="checkbox" id="skipEmailCheckbox" class="mr-2">
                    <label for="skipEmailCheckbox" class="text-sm text-gray-700">Do not send email to Reviewer.</label>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="border-t px-6 py-4 flex justify-end gap-3">
                <button onclick="closeEmailPreview()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                <button onclick="confirmSendReview()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Send</button>
            </div>
        </div>
    </div>

    {{-- Reminder Email Modal --}}
    <div id="reminderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeReminderPreview(event)">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            {{-- Modal Header --}}
            <div class="bg-yellow-500 text-white px-6 py-4">
                <h3 class="text-lg font-semibold">Send Reminder</h3>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                {{-- Selected Reviewer Info --}}
                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700">Reviewer</label>
                    <div class="mt-1 p-2 bg-gray-50 rounded" id="reminderReviewerName">-</div>
                </div>

                {{-- Email Content --}}
                <div class="mb-4">
                    <label class="text-sm font-semibold text-gray-700">Reminder email to be sent</label>
                    <div class="mt-2 border rounded p-4 bg-gray-50 text-sm space-y-3">
                        <div>
                            <strong>Subject:</strong> Review Reminder - {{ $article->title ?? 'Manuscript Title' }}
                        </div>
                        
                        <div class="border-t pt-3">
                            <p class="mb-2">Dear <span id="reminderPreviewReviewerName">Dr. Reviewer</span>,</p>
                            
                            <p class="mb-2">This is a friendly reminder regarding your review assignment for the manuscript "<strong>{{ $article->title ?? 'Manuscript Title' }}</strong>" submitted to <strong>Jurnal Pemberdayaan: Publikasi Hasil Pengabdian Kepada Masyarakat</strong>.</p>
                            
                            <p class="mb-2">We kindly remind you that the review deadline is: <strong><span id="reminderDeadlineText">DEADLINE</span></strong></p>
                            
                            <p class="mb-2">If you have already completed the review, please disregard this message. If you need an extension or have any questions, please don't hesitate to contact us.</p>
                            
                            <p class="mb-2">You can access the submission and submit your review through the journal website.</p>
                            
                            <p class="mb-2"><strong>Submission URL:</strong> <span class="text-blue-600">URL</span></p>
                            
                            <p class="mb-2">We greatly appreciate your time and expertise in reviewing this manuscript.</p>
                            
                            <p class="mt-4">
                                Best regards,<br>
                                {{ $editor->name ?? 'Editor Name' }}<br>
                                Universitas Ahmad Dahlan
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Checkbox Option --}}
                <div class="flex items-center">
                    <input type="checkbox" id="skipReminderCheckbox" class="mr-2">
                    <label for="skipReminderCheckbox" class="text-sm text-gray-700">Do not send reminder email to Reviewer.</label>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="border-t px-6 py-4 flex justify-end gap-3">
                <button onclick="closeReminderPreview()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                <button onclick="confirmSendReminder()" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Send Reminder</button>
            </div>
        </div>
    </div>

</div>

<script>
function openEmailPreview() {
    const reviewerSelect = document.getElementById('reviewerSelect');
    const deadlineInput = document.getElementById('deadlineInput');
    const reviewerName = reviewerSelect.options[reviewerSelect.selectedIndex].text;
    
    // Update preview content
    document.getElementById('selectedReviewerName').textContent = reviewerName;
    document.getElementById('previewReviewerName').textContent = reviewerName;
    
    if (deadlineInput.value) {
        const deadline = new Date(deadlineInput.value);
        const formattedDeadline = deadline.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('previewDeadline').textContent = formattedDeadline;
        document.getElementById('previewReviewDeadline').textContent = formattedDeadline;
    }
    
    // Show modal
    const modal = document.getElementById('emailPreviewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEmailPreview(event) {
    if (!event || event.target.id === 'emailPreviewModal') {
        const modal = document.getElementById('emailPreviewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function confirmSendReview() {
    const skipEmail = document.getElementById('skipEmailCheckbox').checked;
    
    if (skipEmail) {
        alert('Reviewer assigned without sending email.');
    } else {
        alert('Review request sent successfully!');
    }
    
    closeEmailPreview();
    // Here you would submit the actual form
    // document.getElementById('assignReviewerForm').submit();
}

function openReminderPreview(reviewerName, deadline) {
    // Update reminder preview content
    document.getElementById('reminderReviewerName').textContent = reviewerName;
    document.getElementById('reminderPreviewReviewerName').textContent = reviewerName;
    document.getElementById('reminderDeadlineText').textContent = deadline;
    
    // Show reminder modal
    const modal = document.getElementById('reminderModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReminderPreview(event) {
    if (!event || event.target.id === 'reminderModal') {
        const modal = document.getElementById('reminderModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function confirmSendReminder() {
    const skipReminder = document.getElementById('skipReminderCheckbox').checked;
    
    if (skipReminder) {
        alert('Reminder not sent.');
    } else {
        alert('Reminder email sent successfully!');
    }
    
    closeReminderPreview();
    // Here you would submit the reminder request
}
</script>

@endsection