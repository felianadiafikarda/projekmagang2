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
  <!-- Pending Card 1 - Collapsed View -->
  <div class="manuscript-card bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition">
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
      <button class="view-details-btn bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 transition">
        View Details
      </button>
      <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm hover:bg-gray-300 transition">
        Download PDF
      </button>
    </div>
  </div>

  <!-- Pending Card 1 - Expanded Detail View (Hidden by default) -->
  <div class="manuscript-detail hidden bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    <!-- Header Info -->
    <div class="bg-slate-700 text-white p-6">
      <div class="flex justify-between items-start mb-4">
        <h2 class="text-2xl font-semibold">Your review for Discover Electronics</h2>
        <button class="back-to-list-btn text-white hover:text-gray-200 text-sm flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Back to List
        </button>
      </div>
      
      <div class="bg-white/10 backdrop-blur rounded-lg p-4 space-y-2">
        <h3 class="font-semibold text-lg">Machine Learning Approaches for Climate Change Prediction</h3>
        <p class="text-sm"><strong>Authors:</strong> John Doe, Jane Smith</p>
        <p class="text-sm"><strong>Abstract:</strong> Climate change prediction using machine learning techniques has become increasingly important. This paper explores various ML approaches...</p>
        <p class="text-sm"><strong>Due date:</strong> 10 Dec 2025 
          <a href="#" class="text-blue-200 hover:text-white ml-2">Add to calendar</a>
        </p>
        <div class="flex gap-2 mt-3">
          <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded text-sm transition">
            📄 Open Quick Preview
          </button>
          <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded text-sm transition">
            Download files
          </button>
        </div>
      </div>
    </div>

    <!-- Tabs: Guidance & Your Report -->
    <div class="flex border-b border-gray-200">
      <button class="guidance-tab flex-1 py-4 px-6 text-center border-b-2 border-gray-800 font-semibold text-gray-900 flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Guidance
      </button>
      <button class="report-tab flex-1 py-4 px-6 text-center font-semibold text-gray-600 hover:text-gray-900 flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Your report
      </button>
    </div>

    <!-- Guidance Content -->
    <div class="guidance-content p-8">
      <h3 class="text-2xl font-bold text-gray-900 mb-6">Guidance</h3>
      
      <p class="text-gray-700 mb-4">Thank you for agreeing to review.</p>
      
      <h4 class="font-semibold text-gray-900 mb-2">1. Get started</h4>
      <p class="text-gray-700 mb-4">To start your review, download all the relevant files from the download link above.</p>
      
      <h4 class="font-semibold text-gray-900 mb-2">2. Writing your report</h4>
      <p class="text-gray-700 mb-3">We do not ask reviewers to assess the significance of the research and we ask that you focus on ensuring the results are accurately reported.</p>
      
      <p class="text-gray-700 mb-3">Please only request additional work where it is essential to ensure the research is sound. Such work should be expected to take no more than 10 days, and you should advise authors to:</p>
      
      <ul class="list-disc ml-6 mb-4 text-gray-700 space-y-1">
        <li>Rewrite overstated conclusions.</li>
        <li>Explain the limitations of the work.</li>
        <li>Ensure the text explicitly reports what the data show.</li>
      </ul>
      
      <p class="text-gray-700 mb-3">If you feel the study is fundamentally flawed, then please recommend reject, explaining why the manuscript does not meet our criteria for publication.</p>
      
      <p class="text-gray-700 mb-3">The feedback you include in your review report will be shared with the manuscript author(s). Please keep it constructive and professional. If you wish to keep the review anonymous, please don't include your name in the report.</p>
      
      <p class="text-gray-700 mb-3">We reserve the right to remove any inappropriate language from your report.</p>
      
      <p class="text-gray-700 mb-4">We ask peer reviewers not to upload manuscripts into generative AI tools.</p>
      
      <h4 class="font-semibold text-gray-900 mb-2">3. Submitting your report</h4>
      <p class="text-gray-700 mb-3">There's a page in the form where you can share confidential feedback with the Editor. You can use this page to address any sensitive topics you don't want to share with the author(s).</p>
      
      <p class="text-gray-700 mb-4">Before you submit your report, please review it to make sure it's a complete and thorough review.</p>
      
      <p class="text-gray-700">For further information, please see the <a href="#" class="text-blue-600 hover:underline">Discover Electronics reviewer guidelines</a>.</p>
    </div>

    <!-- Report Content (Hidden by default) -->
    <div class="report-content hidden p-8">
      <div class="flex justify-between items-start mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Your report</h3>
        <div class="flex items-center gap-2 text-green-600 text-sm">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          Last saved 2 minutes 50 second(s) ago
        </div>
      </div>

      <!-- Left Sidebar Navigation -->
      <div class="grid grid-cols-4 gap-6">
        <div class="col-span-1 space-y-2">
          <a href="#feedback-author" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded font-medium">
            Feedback for the author(s)
          </a>
          <a href="#confidential" class="block px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
            Confidential feedback for the Editor
          </a>
          <a href="#preview" class="block px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
            Preview
          </a>
        </div>

        <!-- Main Form -->
        <div class="col-span-3">
          <h4 class="text-xl font-semibold mb-4">Feedback for the author(s)</h4>
          <p class="text-sm text-gray-600 mb-1"><span class="text-red-500">*</span> Indicates a required field</p>
          
          <ul class="list-disc ml-5 mb-6 text-sm text-gray-700 space-y-1">
            <li>Your review should be constructive and focused on ensuring the results are accurately reported.</li>
            <li>Please explain how the text can be revised and what essential work is needed to prepare a revision ready for acceptance.</li>
            <li>If recommending rejection, you should explain why the submission does not meet our editorial criteria for publication.</li>
            <li>If you wish to keep your anonymity, please avoid adding personal details to your report.</li>
            <li>You can upload a file with your comments for the author(s) or include them in the text box below.</li>
          </ul>

          <!-- Review Files Upload -->
          <div class="mb-6">
            <h5 class="font-semibold text-gray-900 mb-2">Review file(s)</h5>
            <p class="text-sm text-gray-600 mb-3">
              Please, upload all the relevant review files. We accept files with a <strong>maximum size of 500MB</strong> each and in the following formats: <strong>DOC, DOCX or PDF.</strong>
            </p>
            
            <!-- Drag & Drop Upload Area -->
            <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors cursor-pointer bg-gray-50 hover:bg-gray-100">
              <input type="file" id="reviewFiles" class="hidden" accept=".doc,.docx,.pdf" multiple>
              
              <div class="flex flex-col items-center justify-center">
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                
                <p class="text-lg font-semibold text-gray-700 mb-2">
                  Drag & drop files here
                </p>
                <p class="text-sm text-gray-500 mb-4">or</p>
                
                <button type="button" onclick="document.getElementById('reviewFiles').click()" class="bg-slate-700 text-white px-6 py-3 rounded-md hover:bg-slate-600 transition font-medium">
                  <svg class="w-5 h-5 inline mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                  Browse Files
                </button>
                
                <p class="text-xs text-gray-400 mt-3">
                  Supported formats: DOC, DOCX, PDF • Max size: 500MB per file
                </p>
              </div>
            </div>
            
            <!-- File List -->
            <div id="fileList" class="mt-4 space-y-2"></div>
          </div>

          <!-- Comments Textarea -->
          <div class="mb-6">
            <h5 class="font-semibold text-gray-900 mb-2">Comments to the author(s)</h5>
            <p class="text-sm text-gray-600 mb-3">Please include your comments for the authors in the box below.</p>
            <textarea class="w-full border border-gray-300 rounded-md p-3 h-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your comments here..."></textarea>
          </div>

          <!-- Next Button -->
          <div class="flex justify-end">
            <button class="bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition font-semibold">
              Next >
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pending Card 2 - Collapsed View -->
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
  // Tab switching for main tabs (Pending, In Progress, Completed)
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

  // View Details button - Show/Hide manuscript detail
  const viewDetailsButtons = document.querySelectorAll('.view-details-btn');
  
  viewDetailsButtons.forEach((btn, index) => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.manuscript-card');
      const detail = card.nextElementSibling;
      
      if (detail && detail.classList.contains('manuscript-detail')) {
        // Toggle visibility
        if (detail.classList.contains('hidden')) {
          detail.classList.remove('hidden');
          card.classList.add('hidden');
          btn.textContent = 'Hide Details';
        } else {
          detail.classList.add('hidden');
          card.classList.remove('hidden');
          btn.textContent = 'View Details';
        }
      }
    });
  });

  // Back button - Return to card view
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('back-to-list-btn')) {
      const detail = e.target.closest('.manuscript-detail');
      const card = detail.previousElementSibling;
      
      if (card && card.classList.contains('manuscript-card')) {
        detail.classList.add('hidden');
        card.classList.remove('hidden');
      }
    }
  });

  // Tab switching for Guidance/Report
  const guidanceTabs = document.querySelectorAll('.guidance-tab');
  const reportTabs = document.querySelectorAll('.report-tab');
  
  guidanceTabs.forEach(guidanceTab => {
    guidanceTab.addEventListener('click', () => {
      const container = guidanceTab.closest('.manuscript-detail');
      const reportTab = container.querySelector('.report-tab');
      const guidanceContent = container.querySelector('.guidance-content');
      const reportContent = container.querySelector('.report-content');
      
      guidanceTab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');
      reportTab.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900');
      reportTab.classList.add('text-gray-600');
      
      guidanceContent.classList.remove('hidden');
      reportContent.classList.add('hidden');
    });
  });

  reportTabs.forEach(reportTab => {
    reportTab.addEventListener('click', () => {
      const container = reportTab.closest('.manuscript-detail');
      const guidanceTab = container.querySelector('.guidance-tab');
      const guidanceContent = container.querySelector('.guidance-content');
      const reportContent = container.querySelector('.report-content');
      
      reportTab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');
      reportTab.classList.remove('text-gray-600');
      guidanceTab.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900');
      
      reportContent.classList.remove('hidden');
      guidanceContent.classList.add('hidden');
    });
  });

  // File upload handler
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('reviewFiles');
  const fileListContainer = document.getElementById('fileList');
  
  // Prevent default drag behaviors
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
  });
  
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  
  // Highlight drop zone when dragging over it
  ['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
      dropZone.classList.add('border-slate-500', 'bg-slate-50');
    }, false);
  });
  
  ['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
      dropZone.classList.remove('border-slate-500', 'bg-slate-50');
    }, false);
  });
  
  // Handle dropped files
  dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
  }, false);
  
  // Handle file input change
  fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
  });
  
  function handleFiles(files) {
    Array.from(files).forEach(file => {
      const fileSize = (file.size / (1024 * 1024)).toFixed(2);
      const maxSize = 500;
      const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
      
      const fileItem = document.createElement('div');
      fileItem.className = 'group relative flex items-center gap-4 p-4 bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-all hover:shadow-md';
      
      // Check file type
      const isValidType = validTypes.includes(file.type) || file.name.match(/\.(doc|docx|pdf)$/i);
      
      if (!isValidType) {
        fileItem.innerHTML = `
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-600 truncate">${file.name}</p>
            <p class="text-xs text-red-500 mt-1">Invalid file type. Please upload DOC, DOCX, or PDF files only.</p>
          </div>
          <button onclick="this.parentElement.remove()" class="flex-shrink-0 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
          </button>
        `;
      } else if (fileSize > maxSize) {
        fileItem.innerHTML = `
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-600 truncate">${file.name}</p>
            <p class="text-xs text-red-500 mt-1">File too large: ${fileSize} MB (maximum 500 MB)</p>
          </div>
          <button onclick="this.parentElement.remove()" class="flex-shrink-0 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
          </button>
        `;
      } else {
        // Get file icon based on type
        let fileIcon = '';
        if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
          fileIcon = `
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
              </svg>
            </div>
          `;
        } else {
          fileIcon = `
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
              </svg>
            </div>
          `;
        }
        
        fileItem.innerHTML = `
          <div class="flex-shrink-0">
            ${fileIcon}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">${file.name}</p>
            <div class="flex items-center gap-3 mt-1">
              <span class="text-xs text-gray-500">${fileSize} MB</span>
              <span class="inline-flex items-center gap-1 text-xs text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Ready to upload
              </span>
            </div>
          </div>
          <button onclick="this.parentElement.remove()" class="flex-shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition opacity-0 group-hover:opacity-100">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
          </button>
        `;
      }
      
      fileListContainer.appendChild(fileItem);
    });
    
    // Reset input
    fileInput.value = '';
  }
</script>
@endsection
