@extends('layouts.app')

@section('page_title', 'Reviewer Dashboard')

@section('content')

{{-- Alert Messages --}}
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    {{ session('error') }}
</div>
@endif

@if(session('info'))
<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6" role="alert">
    {{ session('info') }}
</div>
@endif

<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Pending Reviews</p>
    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">In Progress</p>
    <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Completed</p>
    <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Declined</p>
    <p class="text-3xl font-bold text-red-600">{{ $stats['declined'] }}</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Total Assigned</p>
    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
  </div>
</div>

<!-- ARTICLE LISTS -->
<div class="space-y-4">
  @forelse($papers as $paper)
  @php
    $statusColors = [
      'assigned' => 'bg-yellow-200 text-yellow-800',
      'accept_to_review' => 'bg-blue-200 text-blue-800',
      'completed' => 'bg-green-200 text-green-800',
      'decline_to_review' => 'bg-red-200 text-red-800',
    ];
    $statusLabels = [
      'assigned' => 'Awaiting Response',
      'accept_to_review' => 'In Progress',
      'completed' => 'Completed',
      'decline_to_review' => 'Declined',
    ];
    $paperStatusColors = [
      'Unassign' => 'bg-gray-100 text-gray-600',
      'In Review' => 'bg-yellow-100 text-yellow-700',
      'Rejected' => 'bg-red-100 text-red-700',
      'Accept with Review' => 'bg-orange-100 text-orange-700',
      'Accepted' => 'bg-green-100 text-green-700',
    ];
  @endphp
  
  <div class="manuscript-card bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition" 
       data-status="{{ $paper->review_status }}"
       data-paper-id="{{ $paper->id }}">
    
    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-4">
      <h3 class="text-xl font-semibold text-gray-900">{{ $paper->judul }}</h3>
      @php
        $badgeDisplay = [
          'assigned' => ['class' => 'bg-yellow-200 text-yellow-800', 'text' => 'Awaiting Response'],
          'accept_to_review' => ['class' => 'bg-green-200 text-green-800', 'text' => 'Accepted'],
          'decline_to_review' => ['class' => 'bg-red-200 text-red-800', 'text' => 'Declined'],
          'completed' => ['class' => 'bg-blue-200 text-blue-800', 'text' => 'Completed'],
        ];
        $currentBadge = $badgeDisplay[$paper->review_status] ?? ['class' => 'bg-gray-200 text-gray-800', 'text' => ucfirst($paper->review_status)];
      @endphp
      <span class="{{ $currentBadge['class'] }} text-xs px-3 py-1 rounded-full ml-2">{{ $currentBadge['text'] }}</span>
    </div>
    
    {{-- ABSTRACT --}}
    <div class="mb-4 pb-4 border-b border-gray-200">
      <p class="text-sm text-gray-700 mb-3"><strong>Abstract:</strong></p>
      <div class="text-sm text-gray-600 leading-relaxed">
        <span id="abstract-short-{{ $paper->id }}" class="abstract-short">
          {{ Str::limit($paper->abstrak, 300) }}
        </span>
        <span id="abstract-full-{{ $paper->id }}" class="abstract-full hidden">
          {{ $paper->abstrak }}
        </span>
        @if(strlen($paper->abstrak) > 300)
        <button onclick="toggleAbstract({{ $paper->id }})" id="abstract-btn-{{ $paper->id }}" class="text-blue-600 hover:text-blue-800 hover:underline ml-1 font-medium">
          details
        </button>
        @endif
      </div>
    </div>
    
    {{-- AUTHORS --}}
    <div class="mb-4 pb-4 border-b border-gray-200">
      <p class="text-sm text-gray-700"><strong>Authors:</strong> {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?: 'N/A' }}</p>
    </div>
    
    {{-- DATE INFO & ACTIONS --}}
    <div class="flex flex-col md:flex-row gap-4 mt-6">
      {{-- Dates Column --}}
      <div class="flex-1 grid grid-cols-1 gap-3">
        <div>
          <p class="text-xs text-gray-500 mb-1">Submitted</p>
          <p class="text-sm font-medium text-gray-900">{{ $paper->created_at->format('F d, Y') }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-500 mb-1">Review Due Date</p>
          <p class="text-sm font-medium text-red-600">
            {{ $paper->review_deadline ? \Carbon\Carbon::parse($paper->review_deadline)->format('F d, Y') : '-' }}
          </p>
        </div>
      </div>
      
      {{-- Actions Column --}}
      <div class="flex flex-col gap-3 md:w-64">
        @if($paper->review_status === 'assigned')
          {{-- Accept/Decline buttons for pending reviews --}}
          <form action="{{ route('reviewer.accept', $paper->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-md text-sm font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Accept Review
            </button>
          </form>
          <form action="{{ route('reviewer.decline', $paper->id) }}" method="POST">
            @csrf
            <button type="submit" onclick="return confirm('Are you sure you want to decline this review?')" 
                    class="w-full bg-red-600 text-white px-6 py-3 rounded-md text-sm font-semibold hover:bg-red-700 transition flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              Decline Review
            </button>
          </form>
        @elseif($paper->review_status === 'accept_to_review')
          {{-- Start Review button for accepted reviews --}}
          <a href="{{ route('reviewer.review', $paper->id) }}" 
             class="w-full bg-gray-800 text-white px-6 py-3 rounded-md text-sm font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Start Review
          </a>
        @elseif($paper->review_status === 'completed')
          {{-- View Review button for completed reviews --}}
          <a href="{{ route('reviewer.review', $paper->id) }}" 
             class="w-full bg-blue-600 text-white px-6 py-3 rounded-md text-sm font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            View Review
          </a>
        @elseif($paper->review_status === 'decline_to_review')
          {{-- Declined status --}}
          <div class="w-full bg-gray-100 text-gray-500 px-6 py-3 rounded-md text-sm text-center">
            Review Declined
          </div>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <h3 class="text-lg font-semibold text-gray-600 mb-2">No Papers Assigned</h3>
    <p class="text-gray-500">You don't have any papers assigned for review yet.</p>
  </div>
  @endforelse
</div>

<script>
  function toggleAbstract(paperId) {
    const shortAbstract = document.getElementById('abstract-short-' + paperId);
    const fullAbstract = document.getElementById('abstract-full-' + paperId);
    const btn = document.getElementById('abstract-btn-' + paperId);
    
    if (shortAbstract && fullAbstract && btn) {
      if (shortAbstract.classList.contains('hidden')) {
        // Collapse
        shortAbstract.classList.remove('hidden');
        fullAbstract.classList.add('hidden');
        btn.textContent = 'details';
      } else {
        // Expand
        shortAbstract.classList.add('hidden');
        fullAbstract.classList.remove('hidden');
        btn.textContent = 'hide';
      }
    }
  }
</script>

@endsection
