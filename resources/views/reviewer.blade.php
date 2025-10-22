@extends('layouts.app')

@section('page_title', 'Reviewer Dashboard')
@section('page_subtitle', 'Manage and review assigned manuscripts')

@section('content')

<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Pending Reviews</p>
    <p class="text-3xl font-bold text-gray-800">2</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">In Progress</p>
    <p class="text-3xl font-bold text-gray-800">1</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Completed</p>
    <p class="text-3xl font-bold text-gray-800">1</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Total Reviews</p>
    <p class="text-3xl font-bold text-gray-800">4</p>
  </div>
</div>

<!-- TABS -->
<div class="flex gap-4 border-b border-gray-300 mb-6">
  <button id="tab-pending" class="tab-btn pb-3 px-2 font-semibold border-b-2 border-gray-800 text-gray-900">
    Pending (2)
  </button>
  <button id="tab-progress" class="tab-btn pb-3 px-2 font-semibold text-gray-500 hover:text-gray-800 transition">
    In Progress (1)
  </button>
  <button id="tab-completed" class="tab-btn pb-3 px-2 font-semibold text-gray-500 hover:text-gray-800 transition">
    Completed (1)
  </button>
</div>

<!-- ARTICLE LISTS -->
<div id="content-pending" class="space-y-4">
  <!-- Pending Card 1 -->
  <div class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition">
    <div class="flex justify-between items-start mb-3">
      <h3 class="text-lg font-semibold text-gray-900">
        Machine Learning Approaches for Climate Change Prediction
      </h3>
      <span class="bg-yellow-200 text-yellow-800 text-xs px-3 py-1 rounded-full ml-2">Pending</span>
    </div>
    <p class="text-sm text-gray-600 mb-2"><strong>Authors:</strong> John Doe, Jane Smith</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Submitted:</strong> 2025-09-15</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Deadline:</strong> 2025-10-20</p>
    <div class="flex gap-2 mt-4">
      <button class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition">
        View Details
      </button>
      <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition">
        Download PDF
      </button>
    </div>
  </div>

  <!-- Pending Card 2 -->
  <div class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition">
    <div class="flex justify-between items-start mb-3">
      <h3 class="text-lg font-semibold text-gray-900">
        Blockchain Technology in Healthcare Systems
      </h3>
      <span class="bg-yellow-200 text-yellow-800 text-xs px-3 py-1 rounded-full ml-2">Pending</span>
    </div>
    <p class="text-sm text-gray-600 mb-2"><strong>Authors:</strong> Alice Johnson, Bob Williams</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Submitted:</strong> 2025-09-20</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Deadline:</strong> 2025-10-25</p>
    <div class="flex gap-2 mt-4">
      <button class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition">
        View Details
      </button>
      <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition">
        Download PDF
      </button>
    </div>
  </div>
</div>

<!-- IN PROGRESS -->
<div id="content-progress" class="hidden space-y-4">
  <div class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition">
    <div class="flex justify-between items-start mb-3">
      <h3 class="text-lg font-semibold text-gray-900">AI-Powered Medical Diagnosis System</h3>
      <span class="bg-blue-200 text-blue-800 text-xs px-3 py-1 rounded-full ml-2">In Progress</span>
    </div>
    <p class="text-sm text-gray-600 mb-2"><strong>Authors:</strong> Sarah Connor, Michael Lee</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Started:</strong> 2025-10-01</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Deadline:</strong> 2025-10-25</p>
    <div class="flex gap-2 mt-4">
      <button class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition">
        Continue Review
      </button>
    </div>
  </div>
</div>

<!-- COMPLETED -->
<div id="content-completed" class="hidden space-y-4">
  <div class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition">
    <div class="flex justify-between items-start mb-3">
      <h3 class="text-lg font-semibold text-gray-900">Optimization Algorithms in Data Science</h3>
      <span class="bg-green-200 text-green-800 text-xs px-3 py-1 rounded-full ml-2">Completed</span>
    </div>
    <p class="text-sm text-gray-600 mb-2"><strong>Authors:</strong> Kevin Wright, Laura Green</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Reviewed:</strong> 2025-09-30</p>
    <p class="text-sm text-gray-600 mb-2"><strong>Status:</strong> Accepted</p>
    <div class="flex gap-2 mt-4">
      <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition">
        View Review
      </button>
    </div>
  </div>
</div>

<script>
  const tabs = document.querySelectorAll('.tab-btn');
  const contents = {
    'tab-pending': document.getElementById('content-pending'),
    'tab-progress': document.getElementById('content-progress'),
    'tab-completed': document.getElementById('content-completed')
  };

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900'));
      tab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');

      Object.values(contents).forEach(c => c.classList.add('hidden'));
      contents[tab.id].classList.remove('hidden');
    });
  });
</script>
@endsection