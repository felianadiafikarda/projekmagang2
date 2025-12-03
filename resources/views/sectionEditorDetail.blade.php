@extends('layouts.app')

@section('page_title', 'Paper Detail')

@section('content')

<div class="max-w-7xl mx-auto py-8 readonly">

    <div class="bg-white shadow-lg rounded-xl p-14 border">

        <h2 class="text-2xl font-bold mb-6">Paper Details</h2>

        <div class="space-y-4">

            {{-- JUDUL --}}
            <div>
                <h4 class="font-semibold text-gray-700">Title</h4>
                <p class="text-gray-900 text-lg">{{ $paper->judul }}</p>
            </div>

            {{-- AUTHORS --}}
            <div>
                <h4 class="font-semibold text-gray-700">Authors</h4>
                <ul class="list-disc list-inside text-gray-800">
                    @foreach($paper->authors as $author)
                    <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- ABSTRACT --}}
            <div>
                <h4 class="font-semibold text-gray-700">Abstract</h4>
                <p class="text-gray-700">{{ $paper->abstrak }}</p>
            </div>

            {{-- KATA KUNCI --}}
            <div>
                <h4 class="font-semibold text-gray-700">Keywords</h4>
                <p class="text-gray-700">{{ $paper->keywords ?? '-' }}</p>
            </div>

        </div>

        {{-- ================= BOX FOR REVIEWERS & SECTION EDITORS ================= --}}
        <div class="mt-10 p-6 bg-gray-50 rounded-xl border shadow-sm">

            {{-- SECTION EDITORS --}}
            <h3 class="text-xl font-semibold mb-4">Assigned Section Editors</h3>

            <div class="space-y-2">
                @forelse($paper->sectionEditors as $ase)
                <div class="p-3 bg-white border rounded shadow-sm">
                    {{ $ase->first_name }} {{ $ase->last_name }}
                </div>
                @empty
                <p class="text-gray-500 text-sm italic">No section editors assigned.</p>
                @endforelse
            </div>

            {{-- LIST REVIEWERS --}}
            <h3 class="text-xl font-semibold mt-8 mb-4">Assigned Reviewers</h3>

            <div class="space-y-4">
                @forelse($assignedReviewers as $ar)
                <div class="border rounded-lg p-4 shadow bg-white">
                    <div class="font-medium text-gray-800 text-lg">
                        {{ $ar->first_name }} {{ $ar->last_name }}
                    </div>

                    <div class="text-sm text-gray-500">
                        Deadline:
                        <span class="font-medium">
                            {{ date('d M Y', strtotime($ar->pivot->deadline)) }}
                        </span>
                    </div>

                    <div class="text-sm mt-1">
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
                    <div class="text-sm mt-2 p-2 bg-gray-50 border rounded">
                        Recommendation: <strong>{{ $ar->pivot->recommendation }}</strong>
                    </div>
                    @endif

                </div>
                @empty
                <p class="text-gray-500 text-sm italic">No reviewers assigned.</p>
                @endforelse
            </div>

        </div>

    </div>

    {{-- BUTTON BACK --}}
    <div class="flex justify-end mt-6">
        <a href="{{ route('section_editor.index') }}"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Back
        </a>
    </div>

</div>

@endsection

