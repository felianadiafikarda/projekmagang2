{{-- resources/views/editor.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Editor – Manage Submissions')
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


