{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor – Manage Submissions')

@section('content')

{{-- TomSelect CSS & JS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

@php
use Carbon\Carbon;

// Page selector
$page = request('page') ?? ($page ?? 'list');
$idParam = request('id') ?? null;

// Dummy data Submissions
$articles = $articles ?? collect([
    (object)['id'=>1,'title'=>'Model Prediksi Co-Authorship','author_name'=>'Dewi Lestari','status'=>'in_review','created_at'=>Carbon::now()->subDays(10)],
    (object)['id'=>2,'title'=>'Analisis Sistem Informasi Akademik','author_name'=>'Rina Puspitasari','status'=>'unassigned','created_at'=>Carbon::now()->subDays(4)],
    (object)['id'=>3,'title'=>'Sistem Kendali Motor DC','author_name'=>'Andi Wijaya','status'=>'accepted','created_at'=>Carbon::now()->subDays(30)],
]);

if (!isset($article)) {
    if ($idParam) {
        $article = $articles->firstWhere('id',(int)$idParam) ?? null;
    }
    $article = $article ?? $articles->first(); 
}

// Available Reviewers
$all_reviewers = $all_reviewers ?? collect([
    (object)['id'=>1,'name'=>'Dr. Sinta Maharani'],
    (object)['id'=>2,'name'=>'Prof. Rudi Santoso'],
    (object)['id'=>3,'name'=>'Much Fuad Saifuddin'],
    (object)['id'=>4,'name'=>'Dr. Anang Masduki'],
    (object)['id'=>5,'name'=>'Dr. Budi Darmawan'],
]);

// Assigned reviewers
$assignedReviewers = $assignedReviewers ?? collect([
    (object)['id'=>1,'reviewer_id'=>1,'reviewer_name'=>'Dr. Sinta Maharani','status'=>'assigned','deadline'=>Carbon::now()->addDays(7),'recommendation'=>null],
    (object)['id'=>2,'reviewer_id'=>2,'reviewer_name'=>'Prof. Rudi Santoso','status'=>'completed','deadline'=>Carbon::now()->subDays(2),'recommendation'=>'Accept'],
]);

$editor = $editor ?? (object)['name' => (auth()->check() ? auth()->user()->name : 'Editor in Charge')];

function fmt($d) {
    return ($d instanceof \Carbon\Carbon) ? $d->format('d M Y') : (is_string($d) ? $d : (string)$d);
}
@endphp

<div class="max-w-6xl mx-auto space-y-6 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Editor Dashboard</h1>
            <p class="text-sm text-gray-600">Manage submissions & assign reviewers.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ url()->current().'?page=list' }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 transition">All Submissions</a>
        </div>
    </div>

    {{-- TABLE LIST --}}
    @if($page === 'list')
    <div class="bg-white border rounded shadow p-6">
        <h3 class="font-semibold mb-2">All Submissions</h3>

        <table class="w-full text-left border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-2 border">Title</th>
                    <th class="p-2 border">Author</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Submitted</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($articles as $a)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-2">{{ $a->title }}</td>
                    <td class="p-2">{{ $a->author_name }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 text-xs rounded font-medium 
                            {{ $a->status == 'in_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $a->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $a->status == 'unassigned' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_',' ',$a->status)) }}
                        </span>
                    </td>
                    <td class="p-2">{{ fmt($a->created_at) }}</td>

                    <td class="p-2">
                        <div class="flex gap-2">
                            <a href="{{ url()->current().'?page=assign&id='.$a->id }}" 
                                class="px-3 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300">Detail</a>
                            
                            <a href="{{ url()->current().'?page=assign&id='.$a->id }}" 
                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @endif


    {{-- ASSIGN PAGE --}}
    @if(($page === 'assign' || request('id')) && $article)
    <div class="bg-white border rounded shadow p-6 relative">

        {{-- HEADER DETAIL --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                <div class="text-sm text-gray-500 mt-1">
                    Author: <span class="font-medium text-gray-800">{{ $article->author_name }}</span> • Submitted: {{ fmt($article->created_at) }}
                </div>
            </div>

            <div class="text-right mt-1">
                <span class="text-sm font-medium px-3 py-1 rounded 
                    {{ $article->status == 'in_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-50 text-blue-700' }}">
                    {{ ucfirst(str_replace('_',' ',$article->status ?? 'unassigned')) }}
                </span>
            </div>
        </div>


        {{-- ASSIGN REVIEWER SECTION --}}
        <div class="mt-6 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Reviewer</h4>
            
            {{-- Multiselect Reviewer Dropdown --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Reviewers</label>
                <select id="reviewerSelect" name="reviewers[]" multiple placeholder="Cari dan pilih Reviewer..." autocomplete="off">
                    @foreach($all_reviewers as $rev)
                        <option value="{{ $rev->id }}" 
                            @if(in_array($rev->id, $assignedReviewers->pluck('reviewer_id')->toArray()))
                                selected
                            @endif
                        >
                            {{ $rev->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                <input type="date" class="border rounded p-2 w-full md:w-64 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="button" onclick="openAssignModal()" 
                class="mt-2 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow-sm transition flex items-center gap-2">
                Send Request & Assign Reviewers
            </button>
        </div>


        {{-- LIST ASSIGNED REVIEWERS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Reviewers</h4>

            <div class="space-y-4">
                @forelse($assignedReviewers as $ar)
                <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-lg text-gray-800">{{ $ar->reviewer_name }}</div>
                            <div class="text-sm text-gray-500">
                                Deadline: <span class="font-medium">{{ fmt($ar->deadline) }}</span>
                            </div>
                            <div class="text-sm mt-1">
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
                            @if($ar->status === 'assigned' || $ar->status === 'declined')
                                <button type="button" 
                                    onclick="openReminderModal('{{ $ar->reviewer_name }}','{{ fmt($ar->deadline) }}')"
                                    class="text-sm bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 transition">
                                    Send Reminder
                                </button>
                            @endif

                            @if($ar->status === 'completed')
                                <button class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700">
                                    Read Review
                                </button>
                            @endif

                            <button class="text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded">
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
    @endif
    

    {{-- MODAL EMAIL --}}
    <div id="emailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
            
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white text-lg font-semibold" id="modalTitle">Add Reviewer & Send Email</h3>
                <button onclick="closeModal()" class="text-white">
                    ✕
                </button>
            </div>

            <div class="p-6 space-y-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selected Reviewer(s)</label>
                    <input type="text" id="modalRecipientName" readonly class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-600">
                </div>

                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                     <input type="text" id="emailSubject" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Content</label>
                    <textarea id="emailBody" rows="8" class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm"></textarea>
                </div>

                <div class="flex items-center">
                    <input id="skipEmail" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="skipEmail" class="ml-2 text-sm text-gray-900">
                        Do not send email (Assign only).
                    </label>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded hover:bg-gray-50">Cancel</button>
                <button onclick="submitProcess()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Send & Process</button>
            </div>
        </div>
    </div>

</div>


{{-- JAVASCRIPT --}}
<script>
    const articleTitle = "{{ $article->title ?? 'Judul Artikel' }}";
    const journalName = "Jurnal Pemberdayaan: Publikasi Hasil Pengabdian Kepada Masyarakat";
    const articleUrl = "{{ url()->current().'?id='.($article->id ?? 0) }}"; 
    const editorName = "{{ $editor->name }}";
    
    let reviewerSelectInstance; 

    document.addEventListener('DOMContentLoaded', function () {

        if(document.getElementById("reviewerSelect")) {
            reviewerSelectInstance = new TomSelect("#reviewerSelect", {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: 'Cari dan pilih Reviewer...',
            });
        }
    });

    const modal = document.getElementById('emailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalRecipient = document.getElementById('modalRecipientName');
    const emailSubject = document.getElementById('emailSubject');
    const emailBody = document.getElementById('emailBody');

    function openAssignModal() {
        if(!reviewerSelectInstance) return;

        const ids = reviewerSelectInstance.items;
        const opts = reviewerSelectInstance.options;

        if (ids.length === 0) {
            alert("Please select at least one reviewer first.");
            return;
        }

        let names = ids.map(id => opts[id].text).join(", ");

        modalTitle.innerText = "Assign Reviewer & Send Invitation";
        modalRecipient.value = names;
        emailSubject.value = "Invitation to Review Manuscript";

        emailBody.value =
`Dear ${names},

I believe that you would serve as an excellent reviewer of the manuscript, "${articleTitle}" submitted to ${journalName}.

Please log into the journal website to indicate whether you will undertake the review or not.

Submission URL: ${articleUrl}

Thank you for considering this request.

${editorName}
Editor`;

        modal.classList.remove('hidden');
    }

    function openReminderModal(name, deadline) {

        modalTitle.innerText = "Send Reminder to Reviewer";
        modalRecipient.value = name;
        emailSubject.value = "Review Reminder: " + articleTitle;

        emailBody.value =
`Dear ${name},

This is a reminder regarding the manuscript "${articleTitle}" assigned to you.

Deadline: ${deadline}

Submission URL: ${articleUrl}

Best regards,
${editorName}`;

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function submitProcess() {
        const skip = document.getElementById('skipEmail').checked;

        alert(skip 
            ? "Success! Reviewer assigned without email." 
            : "Success! Email sent and Reviewer assigned.");

        closeModal();
    }
</script>

@endsection
