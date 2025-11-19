@extends('layouts.app')

@section('page_title', 'Halaman Depan')
@section('page_subtitle', 'Daftar Paper yang Diupload')

@section('content')

@php
// Dummy data untuk tampilan (tidak pakai controller)
$papers = [
    ['id'=>1,  'title'=>'Analisis Jaringan Sosial','author'=>'Aldi', 'status'=>'Unassigned', 'abstract'=>'Abstrak contoh untuk Analisis Jaringan Sosial.','file'=>'paper_1.pdf'],
    ['id'=>2,  'title'=>'Machine Learning for Finance','author'=>'Rani', 'status'=>'In Review', 'abstract'=>'Abstrak contoh untuk ML in Finance.','file'=>'paper_2.pdf'],
    ['id'=>3,  'title'=>'Optimasi Sistem Informasi','author'=>'Gea', 'status'=>'Rejected', 'abstract'=>'Abstrak contoh untuk Optimasi Sistem Informasi.','file'=>'paper_3.pdf'],
    ['id'=>4,  'title'=>'Blockchain for Security','author'=>'Anisa','status'=>'Accept with Review', 'abstract'=>'Abstrak contoh untuk Blockchain for Security.','file'=>'paper_4.pdf'],
    ['id'=>5,  'title'=>'Deep Learning Image Processing','author'=>'Felia','status'=>'Accepted', 'abstract'=>'Abstrak contoh untuk DL Image Processing.','file'=>'paper_5.pdf'],
    ['id'=>6,  'title'=>'Text Mining Sentiment Analysis','author'=>'Budi','status'=>'In Review', 'abstract'=>'Abstrak contoh untuk Text Mining.','file'=>'paper_6.pdf'],
    ['id'=>7,  'title'=>'IoT Smart Home Automation','author'=>'Sinta','status'=>'Unassigned', 'abstract'=>'Abstrak contoh untuk IoT Smart Home.','file'=>'paper_7.pdf'],
    ['id'=>8,  'title'=>'Sistem Rekomendasi E-Commerce','author'=>'Rara','status'=>'Accepted', 'abstract'=>'Abstrak contoh untuk Recommender Systems.','file'=>'paper_8.pdf'],
    ['id'=>9,  'title'=>'Data Warehouse Modern','author'=>'Yuda','status'=>'Rejected', 'abstract'=>'Abstrak contoh untuk Data Warehouse.','file'=>'paper_9.pdf'],
    ['id'=>10, 'title'=>'Cloud Computing Optimization','author'=>'Mira','status'=>'Accept with Review', 'abstract'=>'Abstrak contoh untuk Cloud Optimization.','file'=>'paper_10.pdf'],
];
@endphp

<h2 class="text-2xl font-semibold mb-6">Daftar Paper</h2>

<div class="flex justify-between items-center mb-4">
  <a href="{{ route('author.kirim')}}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
      + Kirim Artikel Baru
  </a>

  <div class="text-sm text-gray-600">
      Menampilkan {{ count($papers) }} paper
  </div>
</div>

<div class="border p-4 rounded-lg shadow-sm overflow-x-auto">
    <table class="w-full text-left border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Judul Paper</th>
                <th class="p-2 border">Author</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($papers as $p)
            <tr class="odd:bg-white even:bg-gray-50">
                <td class="p-2 border text-center" style="width:60px;">{{ $p['id'] }}</td>

                <td class="p-2 border">
                    <div class="font-semibold text-gray-800">{{ $p['title'] }}</div>
                </td>

                <td class="p-2 border">{{ $p['author'] }}</td>

                <td class="p-2 border">
                    @php
                        $color = match($p['status']) {
                            'Unassigned' => 'text-gray-600',
                            'In Review' => 'text-blue-600',
                            'Rejected' => 'text-red-600',
                            'Accepted' => 'text-green-600',
                            'Accept with Review' => 'text-yellow-600',
                            default => 'text-gray-600'
                        };
                    @endphp
                    <span class="font-semibold {{ $color }}">{{ $p['status'] }}</span>
                </td>

                <td class="p-2 border text-center">
                    <button
                        class="btn-detail px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                        data-id="{{ $p['id'] }}"
                        data-title="{{ e($p['title']) }}"
                        data-author="{{ e($p['author']) }}"
                        data-status="{{ $p['status'] }}"
                        data-abstract="{{ e($p['abstract']) }}"
                        data-file="{{ $p['file'] }}"
                    >
                        Lihat Detail
                    </button>
                </td>
            </tr>
            @endforeach
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
      <div><strong>Judul:</strong> <span id="mTitle" class="text-gray-800"></span></div>
      <div><strong>Author:</strong> <span id="mAuthor"></span></div>
      <div><strong>Status:</strong> <span id="mStatus" class="font-semibold"></span></div>

      <div>
        <strong>Abstrak:</strong>
        <p id="mAbstract" class="mt-2"></p>
      </div>

      <div>
        <strong>File:</strong>
        <div id="mFile" class="mt-2"></div>
      </div>

      <div class="mt-4 text-right">
        <button id="modalCloseBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
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
        }[data.status] || "text-gray-600");

        mAbstract.textContent = data.abstract;
        mFile.innerHTML = `<a href="#" class="text-blue-600 underline">${data.file}</a>`;

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
    modal.addEventListener("click", e => { if (e.target === modal) closeModal(); });
});
</script>

@endsection
