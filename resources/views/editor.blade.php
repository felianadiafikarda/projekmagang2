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
                        <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="text-sm font-medium">Select reviewer</label>
                                <select class="w-full border rounded p-2">
                                    @foreach($all_reviewers as $rev)
                                        <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Deadline</label>
                                <input type="date" class="w-full border rounded p-2">
                            </div>

                            <div class="flex items-end">
                                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded">Send Request</button>
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
                                            <button class="px-3 py-1 border rounded text-yellow-700">Send Reminder</button>
                                            <button class="px-3 py-1 border rounded text-red-600">Unassign</button>
                                        @elseif($ar->status === 'accept')
                                            <button class="px-3 py-1 border rounded text-yellow-700">Send Reminder</button>
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

</div>

@endsection