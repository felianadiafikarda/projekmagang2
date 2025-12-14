@extends('layouts.app')

@section('page_title', 'Review Manuscript')

@section('content')

<div class="max-w-7xl mx-auto">
  {{-- BACK BUTTON --}}
  <div class="mb-6">
    <a href="{{ route('reviewer.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Back to Dashboard
    </a>
  </div>

  <!-- Manuscript Detail View -->
  <div class="manuscript-detail bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    <!-- Header Info -->
    <div class="bg-slate-700 text-white p-6">
      <div class="flex justify-between items-start mb-4">
        <h2 class="text-2xl font-semibold">Your review for Discover Electronics</h2>
      </div>
      
      <div class="bg-white/10 backdrop-blur rounded-lg p-4 space-y-2">
        <h3 class="font-semibold text-lg">{{ $paper->judul }}</h3>
        <p class="text-sm"><strong>Authors:</strong> {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') }}</p>
        <p class="text-sm"><strong>Abstract:</strong> {{ Str::limit($paper->abstrak, 150) }}</p>
        <p class="text-sm"><strong>Due date:</strong> {{ \Carbon\Carbon::parse($deadline)->format('d M Y') }} 
          <a href="#" class="text-blue-200 hover:text-white ml-2">Add to calendar</a>
        </p>
        <div class="flex gap-2 mt-3">
          @if($paper->file_path)
          <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded text-sm transition">
            Open Quick Preview
          </a>
          <a href="{{ asset('storage/' . $paper->file_path) }}" download class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded text-sm transition">
            Download files
          </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Tabs: Guidance & Your Report -->
    <div class="flex border-b border-gray-200">
      <button type="button" onclick="switchToGuidance()" class="guidance-tab flex-1 py-4 px-6 text-center border-b-2 border-gray-800 font-semibold text-gray-900 flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Guidance
      </button>
      <button type="button" onclick="switchToReport()" class="report-tab flex-1 py-4 px-6 text-center font-semibold text-gray-600 hover:text-gray-900 flex items-center justify-center gap-2">
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
      <form action="{{ route('reviewer.submitReview', $paper->id) }}" method="POST" enctype="multipart/form-data" id="reviewForm">
        @csrf
        <div class="flex justify-between items-start mb-6">
          <h3 class="text-2xl font-bold text-gray-900">Your report</h3>
          <div class="flex items-center gap-2 text-green-600 text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span id="lastSavedText">Last saved just now</span>
          </div>
        </div>

        <!-- Sub-tabs for Your Report -->
        <div class="flex gap-4 border-b border-gray-200 mb-6">
          <button type="button" class="report-subtab active pb-3 px-2 font-medium text-sm border-b-2 border-teal-600 text-teal-600 transition-colors" data-tab="feedback">
            Feedback for the author(s)
          </button>
          <button type="button" class="report-subtab pb-3 px-2 font-medium text-sm text-gray-500 hover:text-gray-700 transition-colors" data-tab="confidential">
            Confidential feedback for the Editor
          </button>
          <button type="button" class="report-subtab pb-3 px-2 font-medium text-sm text-gray-500 hover:text-gray-700 transition-colors" data-tab="preview">
            Preview
          </button>
        </div>

        <!-- Feedback Tab Content -->
        <div id="feedback-tab" class="report-tab-content">
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
              <input type="file" id="reviewFiles" name="review_file" class="hidden" accept=".doc,.docx,.pdf" multiple>
              
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
            <textarea name="comments_for_author" id="commentsTextarea" required class="w-full border border-gray-300 rounded-md p-3 h-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your comments here...">{{ old('comments_for_author') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <button type="button" onclick="saveDraft()" class="bg-slate-600 text-white px-8 py-3 rounded-md hover:bg-slate-500 transition font-semibold">
              Save Draft
            </button>
            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition font-semibold">
              Submit Review
            </button>
          </div>
        </div>

        <!-- Confidential Tab Content -->
        <div id="confidential-tab" class="report-tab-content hidden">
          <h4 class="text-xl font-semibold mb-4">Confidential feedback for the Editor</h4>
          <p class="text-sm text-gray-600 mb-4">
            <span class="text-red-500">*</span> Indicates a required field
          </p>
          <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Confidential Information</p>
                <p class="text-sm text-blue-700 mt-1">
                  This information will only be shared with the Editor and will not be visible to the authors.
                </p>
              </div>
            </div>
          </div>

          <!-- Recommendation -->
          <div class="mb-6">
            <label class="block font-semibold text-gray-900 mb-3">
              <span class="text-red-500">*</span> Your recommendation
            </label>
            <div class="space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="accept" class="w-4 h-4" required>
                <span class="text-sm text-gray-700">Accept</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="reject" class="w-4 h-4">
                <span class="text-sm text-gray-700">Reject</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="minor" class="w-4 h-4">
                <span class="text-sm text-gray-700">Return for minor revisions</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="major" class="w-4 h-4">
                <span class="text-sm text-gray-700">Return for major revisions</span>
              </label>
            </div>
            <p class="text-sm text-gray-600 mt-3">
              If recommending a revision, please give clear and constructive advice that enables the authors to prepare their manuscript so that it's ready for acceptance - without requiring multiple rounds of revision.
            </p>
          </div>

          <!--Q1-->
          <div class="mb-6">
            <label class="block font-semibold text-gray-900 mb-3">
              <span class="text-red-500">*</span> Is the presentation of the work clear?
            </label>
            <div class="space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q1" value="yes" class="w-4 h-4">
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q1" value="no1" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, it's not suitable for publication unless extencively edited</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q1" value="no2" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, it needs some language corrections before being published</span>
              </label>
            </div>
          </div>

          <!--Q2-->
          <div class="mb-6">
            <label class="block font-semibold text-gray-900 mb-3">
              <span class="text-red-500">*</span> Is the study design appropriate to answer the research question (including the use of appropriate controls), and are the conclusions supported by the evidence presented?
            </label>
            <div class="space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q2" value="yes" class="w-4 h-4">
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q2" value="no1" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, but these points can be addressed with revisions</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q2" value="no2" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, and there are fundamental issues that cannot be addressed</span>
              </label>
            </div>
          </div>

          <!--Q3-->
          <div class="mb-6">
            <label class="block font-semibold text-gray-900 mb-3">
              <span class="text-red-500">*</span> Are the methods sufficiently described to allow the study to be repeated?
            </label>
            <div class="space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q3" value="yes" class="w-4 h-4">
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q3" value="no1" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, but these points can be addressed with revisions</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q3" value="no2" class="w-4 h-4">
                <span class="text-sm text-gray-700">No, and there are fundamental issues that cannot be addressed</span>
              </label>
            </div>
          </div>

          <div class="mb-6">
            <h5 class="font-semibold text-gray-900 mb-2">Confidential to the Editor (optional)</h5>
            <p class="text-sm text-gray-600 mb-3">
              Please note, comments added to this box are confidential and will not be shared with the author
            </p>
            <ul class="list-disc ml-5 mb-6 text-sm text-gray-700 space-y-1">
              <li>Ethical concern regarding experiments</li>
              <li>Concerns regarding any disclosed conflict of interest</li>
              <li>Concern regarding plagiarism or publication ethics</li>
              <li>Any additional comments to enable the Editor to assess the revions you have requested</li>
            </ul>
            <textarea name="comments_for_editor" id="confidentialTextarea" class="w-full border border-gray-300 rounded-md p-3 h-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your confidential comments here...">{{ old('comments_for_editor') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <button type="button" onclick="saveDraft()" class="bg-slate-600 text-white px-8 py-3 rounded-md hover:bg-slate-500 transition font-semibold">
              Save Draft
            </button>
            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition font-semibold">
              Submit Review
            </button>
          </div>
        </div>
      </form>

      <!-- Preview Tab Content -->
      <div id="preview-tab" class="report-tab-content hidden">
        <div class="mb-6">
          <h4 class="text-xl font-semibold mb-2">Preview Your Report</h4>
          <p class="text-sm text-gray-600">Please review your report before submitting</p>
        </div>

        <div id="previewContent" class="space-y-6">
          <!-- Feedback Preview -->
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-8">
            <h4 class="text-xl font-semibold text-gray-900 mb-6 pb-3 border-b border-gray-200">
              Feedback for the author(s)
            </h4>
            
            <div class="mb-6" id="previewRecommendation">
              <h5 class="font-medium text-gray-900 mb-3">Your Recommendation:</h5>
              <div class="bg-white border-2 border-gray-200 rounded-lg p-4">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800" id="recommendationValue">
                  Not selected
                </span>
              </div>
            </div>

            <div class="mb-6 hidden" id="previewFiles">
              <h5 class="font-medium text-gray-900 mb-3">Uploaded Files:</h5>
              <ul class="space-y-2" id="previewFilesList"></ul>
            </div>
            
            <div>
              <h5 class="font-medium text-gray-900 mb-3">Comments to the author(s):</h5>
              <div class="bg-white border-2 border-gray-200 rounded-lg p-4 text-gray-700 whitespace-pre-wrap min-h-[100px]" id="previewComments">
                <span class="text-gray-400 italic">No comments provided yet</span>
              </div>
            </div>
          </div>

          <!-- Confidential Preview -->
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-8 hidden" id="previewConfidentialSection">
            <h4 class="text-xl font-semibold text-gray-900 mb-6 pb-3 border-b border-gray-200">
              Confidential feedback for the Editor
            </h4>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-4 text-gray-700 whitespace-pre-wrap min-h-[100px]" id="previewConfidential">
            </div>
          </div>

          <!-- Info Box -->
          <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Review your information carefully</p>
                <p class="text-sm text-blue-700 mt-1">
                  If everything looks correct, you can go back to the previous tabs to make edits or submit your review.
                </p>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <button type="button" onclick="switchReportTab('feedback')" class="bg-slate-600 text-white px-8 py-3 rounded-md hover:bg-slate-500 transition font-semibold">
              Back to Edit
            </button>
            <button type="submit" form="reviewForm" class="bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition font-semibold">
              Submit Review
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Last Saved functionality
  const paperId = {{ $paper->id }};
  const lastSavedKey = 'review_last_saved_' + paperId;
  let lastSavedTime = null;
  let saveTimeout = null;

  // Initialize last saved time
  function initLastSaved() {
    const saved = localStorage.getItem(lastSavedKey);
    if (saved) {
      lastSavedTime = new Date(saved);
    } else {
      lastSavedTime = new Date();
      localStorage.setItem(lastSavedKey, lastSavedTime.toISOString());
    }
    updateLastSavedText();
  }

  // Update last saved text
  function updateLastSavedText() {
    const lastSavedElement = document.getElementById('lastSavedText');
    if (!lastSavedElement || !lastSavedTime) return;

    const now = new Date();
    const diffMs = now - lastSavedTime;
    const diffMinutes = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);

    let text = 'Last saved ';
    
    if (diffMinutes < 1) {
      text += 'just now';
    } else if (diffMinutes < 60) {
      text += diffMinutes === 1 ? '1 minute ago' : diffMinutes + ' minutes ago';
    } else {
      text += diffHours === 1 ? '1 hour ago' : diffHours + ' hours ago';
    }

    lastSavedElement.textContent = text;
  }

  // Save draft to localStorage
  function saveDraft() {
    const formData = {
      comments_for_author: document.getElementById('commentsTextarea')?.value || '',
      comments_for_editor: document.getElementById('confidentialTextarea')?.value || '',
      recommendation: document.querySelector('input[name="recommendation"]:checked')?.value || '',
      Q1: document.querySelector('input[name="Q1"]:checked')?.value || '',
      Q2: document.querySelector('input[name="Q2"]:checked')?.value || '',
      Q3: document.querySelector('input[name="Q3"]:checked')?.value || '',
    };
    
    localStorage.setItem('review_draft_' + paperId, JSON.stringify(formData));
    lastSavedTime = new Date();
    localStorage.setItem(lastSavedKey, lastSavedTime.toISOString());
    updateLastSavedText();
  }

  // Auto-save on input (debounced)
  function setupAutoSave() {
    const inputs = document.querySelectorAll('input[type="radio"], textarea');

    inputs.forEach(input => {
      input.addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveDraft, 2000); // Save 2 seconds after user stops typing
      });

      input.addEventListener('change', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveDraft, 500);
      });
    });
  }

  // Load draft from localStorage
  function loadDraft() {
    const draft = localStorage.getItem('review_draft_' + paperId);
    if (draft) {
      try {
        const formData = JSON.parse(draft);
        if (formData.comments_for_author && document.getElementById('commentsTextarea')) {
          document.getElementById('commentsTextarea').value = formData.comments_for_author;
        }
        if (formData.comments_for_editor && document.getElementById('confidentialTextarea')) {
          document.getElementById('confidentialTextarea').value = formData.comments_for_editor;
        }
        if (formData.recommendation) {
          const radio = document.querySelector(`input[name="recommendation"][value="${formData.recommendation}"]`);
          if (radio) radio.checked = true;
        }
        ['Q1', 'Q2', 'Q3'].forEach(name => {
          if (formData[name]) {
            const radio = document.querySelector(`input[name="${name}"][value="${formData[name]}"]`);
            if (radio) radio.checked = true;
          }
        });
      } catch (e) {
        console.error('Error loading draft:', e);
      }
    }
  }

  // Tab switching functions
  function switchToGuidance() {
    const guidanceTab = document.querySelector('.guidance-tab');
    const reportTab = document.querySelector('.report-tab');
    const guidanceContent = document.querySelector('.guidance-content');
    const reportContent = document.querySelector('.report-content');
    
    if (guidanceTab && reportTab && guidanceContent && reportContent) {
      guidanceTab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');
      guidanceTab.classList.remove('text-gray-600');
      reportTab.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900');
      reportTab.classList.add('text-gray-600');
      
      guidanceContent.classList.remove('hidden');
      reportContent.classList.add('hidden');
    }
  }

  function switchToReport() {
    const guidanceTab = document.querySelector('.guidance-tab');
    const reportTab = document.querySelector('.report-tab');
    const guidanceContent = document.querySelector('.guidance-content');
    const reportContent = document.querySelector('.report-content');
    
    if (guidanceTab && reportTab && guidanceContent && reportContent) {
      reportTab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');
      reportTab.classList.remove('text-gray-600');
      guidanceTab.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900');
      guidanceTab.classList.add('text-gray-600');
      
      reportContent.classList.remove('hidden');
      guidanceContent.classList.add('hidden');
    }
  }

  // Sub-tabs switching for Report section
  window.switchReportTab = function(tabName) {
    // Remove active class from all sub-tabs
    document.querySelectorAll('.report-subtab').forEach(tab => {
      tab.classList.remove('active', 'border-b-2', 'border-teal-600', 'text-teal-600');
      tab.classList.add('text-gray-500');
    });
    
    // Hide all tab contents
    document.querySelectorAll('.report-tab-content').forEach(content => {
      content.classList.add('hidden');
    });
    
    // Show selected tab
    const selectedTab = document.querySelector(`[data-tab="${tabName}"]`);
    const selectedContent = document.getElementById(`${tabName}-tab`);
    
    if (selectedTab) {
      selectedTab.classList.add('active', 'border-b-2', 'border-teal-600', 'text-teal-600');
      selectedTab.classList.remove('text-gray-500');
    }
    
    if (selectedContent) {
      selectedContent.classList.remove('hidden');
    }
    
    // Update preview if switching to preview tab
    if (tabName === 'preview') {
      updatePreview();
    }
  };

  // Update preview function
  function updatePreview() {
    // Get recommendation
    const selectedRecommendation = document.querySelector('input[name="recommendation"]:checked');
    const recommendationValue = document.getElementById('recommendationValue');
    if (selectedRecommendation && recommendationValue) {
      const labels = {
        'accept': 'Accept',
        'reject': 'Reject',
        'minor': 'Return for minor revisions',
        'major': 'Return for major revisions'
      };
      recommendationValue.textContent = labels[selectedRecommendation.value] || 'Not selected';
      const previewRec = document.getElementById('previewRecommendation');
      if (previewRec) previewRec.classList.remove('hidden');
    }
    
    // Get comments
    const commentsTextarea = document.querySelector('textarea[name="comments_for_author"]');
    const previewComments = document.getElementById('previewComments');
    if (commentsTextarea && previewComments) {
      if (commentsTextarea.value) {
        previewComments.textContent = commentsTextarea.value;
      } else {
        previewComments.innerHTML = '<span class="text-gray-400 italic">No comments provided yet</span>';
      }
    }
    
    // Get confidential comments
    const confidentialTextarea = document.querySelector('textarea[name="comments_for_editor"]');
    const previewConfidentialSection = document.getElementById('previewConfidentialSection');
    const previewConfidential = document.getElementById('previewConfidential');
    if (confidentialTextarea && previewConfidential) {
      if (confidentialTextarea.value) {
        previewConfidential.textContent = confidentialTextarea.value;
        if (previewConfidentialSection) previewConfidentialSection.classList.remove('hidden');
      } else {
        if (previewConfidentialSection) previewConfidentialSection.classList.add('hidden');
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    initLastSaved();
    setupAutoSave();
    loadDraft();
    
    // Update last saved text every 30 seconds
    setInterval(updateLastSavedText, 30000); // Update every 30 seconds
    
    // Add click handlers for sub-tabs
    document.querySelectorAll('.report-subtab').forEach(tab => {
      tab.addEventListener('click', () => {
        const tabName = tab.getAttribute('data-tab');
        switchReportTab(tabName);
      });
    });

    // Drag & Drop File Upload
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('reviewFiles');
    const fileList = document.getElementById('fileList');
    let selectedFiles = [];

    if (dropZone && fileInput) {
      // Prevent default drag behaviors
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
      });

      function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
      }

      // Highlight drop zone when item is dragged over it
      ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
      });

      function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
      }

      function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
      }

      // Handle dropped files
      dropZone.addEventListener('drop', handleDrop, false);

      function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
      }

      // Handle file input change
      fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
      });

      function handleFiles(files) {
        Array.from(files).forEach(file => {
          // Validate file type
          const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
          const fileExtension = file.name.split('.').pop().toLowerCase();
          
          if (!['pdf', 'doc', 'docx'].includes(fileExtension)) {
            alert(`File ${file.name} is not a valid format. Please upload DOC, DOCX, or PDF files only.`);
            return;
          }

          // Validate file size (500MB = 500 * 1024 * 1024 bytes)
          const maxSize = 500 * 1024 * 1024;
          if (file.size > maxSize) {
            alert(`File ${file.name} is too large. Maximum size is 500MB.`);
            return;
          }

          selectedFiles.push(file);
          displayFile(file);
        });
      }

      function displayFile(file) {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg';
        fileItem.innerHTML = `
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-900">${file.name}</p>
              <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
            </div>
          </div>
          <button type="button" onclick="removeFile('${file.name}')" class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        `;
        fileItem.dataset.fileName = file.name;
        fileList.appendChild(fileItem);
      }

      // Remove file function
      window.removeFile = function(fileName) {
        selectedFiles = selectedFiles.filter(file => file.name !== fileName);
        const fileItem = fileList.querySelector(`[data-file-name="${fileName}"]`);
        if (fileItem) {
          fileItem.remove();
        }
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
      };
    }
  });
</script>

@endsection