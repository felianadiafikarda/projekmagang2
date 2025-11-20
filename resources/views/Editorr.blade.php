{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor – Manage Submissions')
@section('content')

@php
use Carbon\Carbon;

// Page selector
$page = request('page') ?? ($page ?? 'list');
$idParam = request('id') ?? null;

// Dummy data (can be replaced with database)
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

// Reviewers
$all_reviewers = $all_reviewers ?? collect([
    (object)['id'=>1,'name'=>'Dr. Sinta Maharani'],
    (object)['id'=>2,'name'=>'Prof. Rudi Santoso'],
    (object)['id'=>3,'name'=>'Much Fuad Saifuddin'],
    (object)['id'=>4,'name'=>'Dr. Anang Masduki'],
]);

// Section editors
$all_section_editors = $all_section_editors ?? collect([
    (object)['id'=>1,'name'=>'Dr. Indah Pertiwi'],
    (object)['id'=>2,'name'=>'Prof. Budi Santoso'],
]);

// Assigned reviewers
$assignedReviewers = $assignedReviewers ?? collect([
    (object)['id'=>1,'reviewer_id'=>1,'reviewer_name'=>'Dr. Sinta Maharani','status'=>'assigned','deadline'=>Carbon::now()->addDays(7),'recommendation'=>null],
    (object)['id'=>2,'reviewer_id'=>2,'reviewer_name'=>'Prof. Rudi Santoso','status'=>'completed','deadline'=>Carbon::now()->subDays(2),'recommendation'=>'Accept'],
]);

// Assigned section editors
$assignedSectionEditors = $assignedSectionEditors ?? collect([
    (object)['id'=>1,'editor_id'=>1,'editor_name'=>'Dr. Indah Pertiwi'],
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
            <p class="text-sm text-gray-600">Manage submissions, assign reviewers & section editors.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url()->current().'?page=list' }}" class="px-3 py-2 bg-gray-100 rounded">All Submissions</a>
        </div>
    </div>


    {{-- ALL SUBMISSIONS TABLE --}}
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
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2">{{ $a->title }}</td>
                    <td class="p-2">{{ $a->author_name }}</td>
                    <td class="p-2">{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
                    <td class="p-2">{{ fmt($a->created_at) }}</td>

                    <td class="p-2">
                        <div class="flex gap-2">
                            <a href="{{ url()->current().'?page=assign&id='.$a->id }}"
                                class="px-3 py-1 text-sm bg-gray-200 rounded">Detail</a>

                            <a href="{{ url()->current().'?page=assign&id='.$a->id }}"
                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @endif



    {{-- ASSIGN PAGE --}}
    @if($page === 'assign' || request('id'))
    <div class="bg-white border rounded shadow p-6">

        {{-- HEADER DETAIL --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                <div class="text-sm text-gray-500">
                    Author: {{ $article->author_name }} • Submitted: {{ fmt($article->created_at) }}
                </div>
            </div>

            <div class="text-right mt-1">
                <span class="text-sm font-medium px-3 py-1 rounded bg-blue-50 text-blue-700">
                    {{ ucfirst(str_replace('_',' ',$article->status ?? 'unassigned')) }}
                </span>
            </div>
        </div>

        {{-- ASSIGN REVIEWER --}}
        <div class="mt-6 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Reviewer</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($all_reviewers as $rev)
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-blue-50 transition shadow-sm">
                    <input type="checkbox" name="reviewers[]" value="{{ $rev->id }}"
                        class="w-5 h-5 text-blue-600">

                    <div>
                        <div class="font-medium">{{ $rev->name }}</div>
                        <div class="text-xs text-gray-500">Available Reviewer</div>
                    </div>
                </label>
                @endforeach
            </div>

            {{-- Deadline --}}
            <div class="mt-5">
                <label class="text-sm font-medium">Deadline for all selected reviewers</label>
                <input type="date" class="border rounded p-2 w-full md:w-64 mt-1">
            </div>

            <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Assign Selected Reviewers
            </button>
        </div>


        {{-- ASSIGN SECTION EDITOR --}}
        <div class="mt-10 border-t pt-6">
            <h4 class="font-semibold mb-4 text-lg">Assign Section Editor</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($all_section_editors as $se)
                <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-green-50 transition shadow-sm">
                    <input type="checkbox" name="section_editors[]" value="{{ $se->id }}"
                        class="w-5 h-5 text-green-600">

                    <div class="font-medium">{{ $se->name }}</div>
                </label>
                @endforeach
            </div>

            <button class="mt-4 bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Assign Selected Editors
            </button>
        </div>


        {{-- ASSIGNED REVIEWERS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Reviewers</h4>

            <div class="space-y-4">
                @forelse($assignedReviewers as $ar)
                <div class="border rounded p-4 shadow-sm">
                    <div class="font-medium">{{ $ar->reviewer_name }}</div>
                    <div class="text-xs text-gray-500">
                        Deadline: {{ fmt($ar->deadline) }}
                    </div>
                    <div class="text-xs mt-1">{{ ucfirst($ar->status) }}</div>

                    @if($ar->status==='completed' && $ar->recommendation)
                    <div class="text-xs mt-1 text-gray-700">
                        Recommendation: <strong>{{ $ar->recommendation }}</strong>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-sm text-gray-500">No reviewers assigned yet.</div>
                @endforelse
            </div>
        </div>


        {{-- ASSIGNED SECTION EDITORS --}}
        <div class="mt-10">
            <h4 class="font-semibold mb-3 text-lg">Assigned Section Editors</h4>

            <div class="space-y-2">
                @forelse($assignedSectionEditors as $ase)
                <div class="border rounded p-3 shadow-sm">
                    {{ $ase->editor_name }}
                </div>
                @empty
                <div class="text-sm text-gray-500">No section editors assigned yet.</div>
                @endforelse
            </div>
        </div>

    </div>
    @endif

</div>

@endsection
