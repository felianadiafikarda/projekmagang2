@extends('layouts.app')

@section('page_title', 'Author')
@section('page_subtitle', 'List of Uploaded Papers')

@section('content')

<h2 class="text-2xl font-semibold mb-6">List of Papers</h2>

<div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">
    <div class="flex flex-col">
        <a href="{{ route('author.kirim')}}" class="px-5 py-2 rounded-xl
            bg-teal-600 text-white font-semibold
            hover:bg-teal-700 transition shadow-sm mb-1">
            + Submit
        </a>
        <div class="text-sm text-gray-700 mt-3 font-medium">
            Showing <span class="font-bold text-red-500">{{ $papers?->count() ?? 0 }}</span>
            {{ Str::plural('paper', $papers?->count() ?? 0) }}
        </div>
    </div>

    <form action="{{ route('author.index') }}" method="GET"
        class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
        {{-- Dropdown Filter --}}
        <select name="filter_status" onchange="this.form.submit()"
            class="w-full md:w-48 border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 cursor-pointer bg-white text-gray-700">
            <option value="All Status" {{ request('filter_status') == 'All Status' ? 'selected' : '' }}>All Status
            </option>
            @foreach(['Submitted','In Review','Accept with Review','Revised','Accepted','Rejected'] as $status)
            <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                {{ $status }}
            </option>
            @endforeach
        </select>

        {{-- Input Search --}}
        <div class="relative w-full md:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                class="w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-md shadow-sm text-sm px-3 py-2 pr-10">

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
</div>


<div class="bg-white border p-4 rounded-lg shadow-sm overflow-x-auto">
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

                <td class="p-2 border text-center">
                    @php
                    $statusColor = [
                    'submitted' => 'bg-gray-300 text-gray-800',
                    'in_review' => 'bg-yellow-300 text-yellow-900',
                    'accept_with_review' => 'bg-blue-300 text-blue-800',
                    'revised' => 'bg-orange-300 text-orange-900',
                    'accepted' => 'bg-green-300 text-green-900',
                    'rejected' => 'bg-red-300 text-red-900',
                    ];

                    @endphp

                    <span class="px-2 py-1 rounded font-semibold
    {{ $statusColor[strtolower(str_replace(' ', '_', $p->author_status))] 
        ?? 'bg-gray-200 text-gray-700' }}">
                        {{ $p->author_status }}
                    </span>



                </td>
                <td class="p-2 border text-center">
                    <button class="btn-detail px-3 py-1 bg-gray-900 text-white rounded hover:bg-gray-700"
                        data-id="{{ $p->id }}" data-title="{{ e($p->judul) }}" data-author="{{ e($allAuthors) }}"
                        data-status="{{ $p->status }}" data-abstract="{{ e($p->abstrak) }}"
                        data-keywords="{{ e($p->keywords) }}" data-references="{{ e($p->paper_references) }}"
                        data-file="{{ asset('storage/'.$p->file_path) }}">
                        Detail
                    </button>
                    @if($p->status === 'accept_with_review')
                    <a href="{{ route('author.revision', $p->id) }}"
                        class="px-3 py-1 rounded bg-orange-600 text-white hover:bg-orange-700">
                        Revision
                    </a>
                    @endif
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
<div id="detailModal"
    class="fixed inset-0 bg-black/70 backdrop-blur-md hidden z-50 flex items-start justify-center p-4">

    <div class="relative bg-gradient-to-br from-white to-slate-50 w-full max-w-5xl mt-10 rounded-[2rem]
        shadow-[0_50px_150px_-40px_rgba(139,92,246,0.4),0_25px_80px_-20px_rgba(0,0,0,0.3)]
        overflow-hidden max-h-[85vh] flex flex-col
        border border-white/20 animate-[fadeIn_0.3s_ease-out]" onclick="event.stopPropagation()">

        <!-- Decorative -->
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-3xl -z-10">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-blue-400/20 to-cyan-400/20 rounded-full blur-3xl -z-10">
        </div>

        <!-- Header -->
        <div
            class="relative px-8 py-5 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 text-white flex justify-between items-center">

            <div>
                <h3 class="text-2xl font-bold tracking-tight">Paper Detail</h3>
                <p class="text-sm text-slate-300">Complete manuscript information</p>
            </div>

            <button id="modalClose" class="w-12 h-12 flex items-center justify-center rounded-2xl
                bg-white/10 hover:bg-white/20 transition text-xl">
                ✕
            </button>
        </div>

        <!-- Content -->
        <div class="px-10 py-8 overflow-y-auto space-y-6 bg-gradient-to-b from-slate-50 via-white to-slate-50">

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-500">Title</p>
                    <p id="mTitle" class="font-semibold text-slate-800"></p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-500">Author</p>
                    <p id="mAuthor" class="text-slate-700"></p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-500">Status</p>
                    <span id="mStatus"
                        class="inline-block mt-1 px-3 py-1 rounded-lg bg-slate-200 text-slate-700 text-sm font-semibold"></span>
                </div>
            </div>

            <!-- ABSTRACT -->
            <div>
                <h4 class="font-bold text-slate-700 mb-2">Abstract</h4>
                <p id="mAbstract" class="text-slate-700 leading-relaxed overflow-hidden transition-all"
                    style="max-height: 3em;"></p>

                <button id="toggleAbstract" class="mt-1 text-sm text-blue-600 hover:underline hidden">
                    View detail
                </button>
            </div>


            <div>
                <h4 class="font-bold text-slate-700 mb-1">Keywords</h4>
                <p id="mKeywords" class="text-slate-600"></p>
            </div>

            <!-- REFERENCES -->
            <div>
                <h4 class="font-bold text-slate-700 mb-2">References</h4>
                <pre id="mReferences" class="text-sm text-slate-700 whitespace-pre-wrap overflow-hidden transition-all"
                    style="max-height: 3em;"></pre>

                <button id="toggleReferences" class="mt-1 text-sm text-blue-600 hover:underline hidden">
                    View detail
                </button>
            </div>

            <div>
                <h4 class="font-bold text-slate-700 mb-1">File</h4>
                <div id="mFile"></div>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-8 py-4 border-t bg-white text-right">
            <button id="modalCloseBtn" class="px-5 py-2 rounded-xl bg-slate-700 text-white hover:bg-slate-800">
                Close
            </button>
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
    const mReferences = document.getElementById("mReferences");
    const mKeywords = document.getElementById("mKeywords");
    const mFile = document.getElementById("mFile");

    const toggleAbstract = document.getElementById("toggleAbstract");
    const toggleReferences = document.getElementById("toggleReferences");

    const COLLAPSED_HEIGHT = "3em";
    const statusColorMap = {
        submitted: 'bg-gray-300 text-gray-800',
        in_review: 'bg-yellow-300 text-yellow-900',
        accept_with_review: 'bg-blue-300 text-blue-800',
        revised: 'bg-orange-300 text-orange-900',
        accepted: 'bg-green-300 text-green-900',
        rejected: 'bg-red-300 text-red-900',
    };



    function setupToggle(contentEl, buttonEl) {
        contentEl.style.maxHeight = COLLAPSED_HEIGHT;
        contentEl.style.overflow = "hidden";
        buttonEl.classList.add("hidden");
        buttonEl.textContent = "View detail";

        requestAnimationFrame(() => {
            if (contentEl.scrollHeight > contentEl.clientHeight + 5) {
                buttonEl.classList.remove("hidden");
            }
        });

        buttonEl.onclick = () => {
            const collapsed = contentEl.style.maxHeight === COLLAPSED_HEIGHT;
            contentEl.style.maxHeight = collapsed ? "none" : COLLAPSED_HEIGHT;
            buttonEl.textContent = collapsed ? "Show less" : "View detail";
        };
    }

    function openModal(data) {
        mTitle.textContent = data.title;
        mAuthor.textContent = data.author;

        mStatus.className = 'inline-block mt-1 px-3 py-1 rounded-lg text-sm font-semibold';

        const statusClass = statusColorMap[data.status] ?? 'bg-gray-200 text-gray-700';
        mStatus.classList.add(...statusClass.split(' '));

        mStatus.textContent = data.status
            .replace(/_/g, ' ')
            .replace(/\b\w/g, c => c.toUpperCase());


        mAbstract.textContent = data.abstract || "-";
        mReferences.textContent = data.references || "-";
        mKeywords.textContent = data.keywords || "-";

        setupToggle(mAbstract, toggleAbstract);
        setupToggle(mReferences, toggleReferences);

        if (data.file) {
            const fileName = data.file.split('/').pop();
            mFile.innerHTML = `
            <a href="${data.file}" target="_blank"
            class="text-blue-600 hover:underline font-medium">
            ${fileName}
            </a>`;

        } else {
            mFile.innerHTML = `<span class="text-red-500">No file</span>`;
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
                title: this.dataset.title,
                author: this.dataset.author,
                status: this.dataset.status,
                abstract: this.dataset.abstract,
                references: this.dataset.references,
                keywords: this.dataset.keywords,
                file: this.dataset.file
            });
        });
    });

    document.getElementById("modalClose").onclick = closeModal;
    document.getElementById("modalCloseBtn").onclick = closeModal;
    modal.onclick = e => e.target === modal && closeModal();
});
</script>


@endsection