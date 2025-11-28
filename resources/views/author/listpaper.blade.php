@extends('layouts.app')

@section('page_title', 'Author')
@section('page_subtitle', 'List of Uploaded Papers')

@section('content')

<h2 class="text-2xl font-semibold mb-6">List of Papers</h2>

<div class="flex justify-between items-center mb-4">
    <a href="{{ route('author.kirim')}}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
        + Submit a new paper
    </a>

    <div class="text-sm text-gray-600">
        Showing{{ $papers?->count() ?? 0 }} paper
    </div>
</div>

<div class="border p-4 rounded-lg shadow-sm overflow-x-auto">
    <table class="w-full text-left border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border text-center">No</th>
                <th class="p-2 border text-center">Title</th>
                <th class="p-2 border text-center">Author</th>
                <th class="p-2 border text-center">Status</th>
                <th class="p-2 border text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($papers as $p)
            @php
            $authors = $p->authors->map(function($a) {
            return $a->first_name . ' ' . $a->last_name;
            })->toArray();

            $allAuthors = count($authors) ? implode(', ', $authors) : '-';
            @endphp


            <tr class="odd:bg-white even:bg-gray-50">

                <td class="p-2 border text-center">{{ $loop->iteration }}</td>

                <td class="p-2 border">
                    <div class="font-semibold text-gray-800">{{ $p->judul }}</div>
                </td>

                <td class="p-2 border">
                    {{ $allAuthors }}
                </td>

                <td class="p-2 border">
                    @php
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

                    <span class="px-2 py-1 rounded font-semibold 
        {{ $statusColor[$p->status] ?? 'bg-gray-200 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                    </span>
                </td>


                <td class="p-2 border text-center">
                    <button class="btn-detail px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                        data-id="{{ $p->id }}" data-title="{{ e($p->judul) }}" data-author="{{ e($allAuthors) }}"
                        data-status="Unassigned" data-abstract="{{ e($p->abstrak) }}" data-file="{{ $p->file_path }}">
                        Detail
                    </button>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">No data available</td>
            </tr>
            @endforelse
        </tbody>


    </table>
</div>

<!-- ================================= MODAL ================================= -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white w-11/12 md:w-2/3 lg:w-1/2 rounded-lg shadow-lg overflow-hidden">

        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h3 class="text-lg font-semibold">Detail Paper</h3>
            <button id="modalClose" class="text-gray-600 hover:text-gray-800">✖</button>
        </div>

        <div class="p-4 space-y-3">
            <div><strong>Title:</strong> <span id="mTitle" class="text-gray-800"></span></div>
            <div><strong>Author:</strong> <span id="mAuthor"></span></div>
            <div><strong>Status:</strong> <span id="mStatus" class="font-semibold"></span></div>

            <div>
                <strong>Abstract:</strong>
                <p id="mAbstract" class="mt-2"></p>
            </div>

            <div>
                <strong>File:</strong>
                <div id="mFile" class="mt-2"></div>
            </div>

            <div class="mt-4 text-right">
                <button id="modalCloseBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT MODAL -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("detailModal");
    const mTitle = document.getElementById("mTitle");
    const mAuthor = document.getElementById("mAuthor");
    const mStatus = document.getElementById("mStatus");
    const mAbstract = document.getElementById("mAbstract");
    const mFile = document.getElementById("mFile");

    function openModal(data) {
        mTitle.textContent = data.title;
        mAuthor.textContent = data.author;
        mStatus.textContent = data.status;

        mStatus.className = "font-semibold " + ({
            "In Review": "text-blue-600",
            "Rejected": "text-red-600",
            "Accepted": "text-green-600",
            "Accept with Review": "text-yellow-600",
            "Unassigned": "text-gray-600"
        } [data.status] || "text-gray-600");

        mAbstract.textContent = data.abstract;

        if (data.file) {
            const fileUrl = `/storage/${data.file}`;
            mFile.innerHTML = `
                <a href="${fileUrl}" 
                target="_blank" 
                class="text-blue-600 underline hover:text-blue-800">
                Buka File Paper
                </a>
            `;

        } else {
            mFile.innerHTML = `<span class="text-red-600">Tidak ada file</span>`;
        }


        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }

    function closeModal() {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }

    document.querySelectorAll(".btn-detail").forEach(btn => {
        btn.addEventListener("click", function() {
            openModal({
                id: this.dataset.id,
                title: this.dataset.title,
                author: this.dataset.author,
                status: this.dataset.status,
                abstract: this.dataset.abstract,
                file: this.dataset.file
            });
        });
    });

    document.getElementById("modalClose").addEventListener("click", closeModal);
    document.getElementById("modalCloseBtn").addEventListener("click", closeModal);
    modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });
});
</script>

@endsection