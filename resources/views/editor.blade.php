@extends('layouts.app')

@section('page_title', 'Editor Dashboard')
@section('page_subtitle', 'Kelola Artikel dan Keputusan Editorial')

@section('content')
<!-- KONTEN UTAMA -->
<h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Panel Editor</h1>

<!-- Daftar Artikel -->
<section class="mb-10">
  <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Daftar Artikel Masuk</h2>

  <div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg text-sm">
      <thead class="bg-white">
        <tr>
          <th class="border p-2">#</th>
          <th class="border p-2 text-left">Judul Artikel</th>
          <th class="border p-2 text-left">Author</th>
          <th class="border p-2 text-left">Status</th>
          <th class="border p-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-gray-50">
        <tr class="hover:bg-gray-50">
          <td class="border p-2 text-center">1</td>
          <td class="border p-2">Analisis Sistem Informasi Akademik</td>
          <td class="border p-2">Rina Puspitasari</td>
          <td class="border p-2">
            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">
              Menunggu Reviewer
            </span>
          </td>
          <td class="border p-2 text-center">
            <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-md" onclick="openAssignModal()">
              Tugaskan Reviewer
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<!-- Kirim Keputusan -->
<section>
  <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Kirim Keputusan Artikel</h2>

  <form class="bg-gray-50 p-4 rounded-md border border-gray-200 space-y-4">
    <div>
      <label class="block font-medium mb-1">Judul Artikel</label>
      <input type="text" placeholder="Masukkan judul artikel..." class="w-full border rounded-md p-2">
    </div>

    <div>
      <label class="block font-medium mb-1">Keputusan</label>
      <select class="w-full border rounded-md p-2">
        <option value="">-- Pilih Keputusan --</option>
        <option value="accepted">Diterima</option>
        <option value="rejected">Ditolak</option>
        <option value="revision">Perlu Revisi</option>
      </select>
    </div>

    <div>
      <label class="block font-medium mb-1">Catatan untuk Author</label>
      <textarea rows="3" class="w-full border rounded-md p-2" placeholder="Tulis catatan keputusan..."></textarea>
    </div>

    <div class="flex justify-end">
      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition">
        Kirim Keputusan
      </button>
    </div>
  </form>
</section>
</div>

<!-- Modal Tugaskan Reviewer -->
<div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white w-96 p-6 rounded-md shadow-lg">
    <h3 class="text-lg font-semibold mb-4">Tugaskan Reviewer</h3>
    <form class="space-y-3">
      <div>
        <label class="block font-medium mb-1">Pilih Reviewer</label>
        <select class="w-full border rounded-md p-2">
          <option value="">-- Pilih Reviewer --</option>
          <option>Dr. Sinta Maharani</option>
          <option>Prof. Rudi Santoso</option>
          <option>Dr. Ahmad Rasyid</option>
        </select>
      </div>
      <div class="flex justify-end space-x-2 pt-3">
        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md" onclick="closeAssignModal()">Batal</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Script Modal -->
<script>
  function openAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
  }

  function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
  }
</script>
@endsection