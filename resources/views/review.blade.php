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
          <div class="flex items-center gap-2 text-green-600 text-sm" id="saveStatus">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span id="saveStatusText">All changes saved</span>
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
            <div id="fileList" class="mt-4 space-y-2">
              @if(!empty($draft['review_file']) && !empty($draft['review_file_name']))
              <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg saved-file-item" data-file-path="{{ $draft['review_file'] }}" data-file-name="{{ $draft['review_file_name'] }}">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ $draft['review_file_name'] }}</p>
                    <a href="{{ asset('storage/' . $draft['review_file']) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View file</a>
                  </div>
                </div>
                <span class="text-xs text-gray-500 bg-green-100 text-green-800 px-2 py-1 rounded">Saved</span>
              </div>
              @endif
            </div>
          </div>

          <!-- Comments Textarea -->
          <div class="mb-6">
            <h5 class="font-semibold text-gray-900 mb-2">Comments to the author(s)</h5>
            <p class="text-sm text-gray-600 mb-3">Please include your comments for the authors in the box below.</p>
            <textarea name="comments_for_author" id="commentsTextarea" required class="w-full border border-gray-300 rounded-md p-3 h-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your comments here...">{{ old('comments_for_author', $draft['comments_for_author'] ?? '') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <button type="submit" form="reviewForm" formaction="{{ route('reviewer.saveDraft', $paper->id) }}" class="bg-slate-600 text-white px-8 py-3 rounded-md hover:bg-slate-500 transition font-semibold">
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
                <input type="radio" name="recommendation" value="accept" class="w-4 h-4" required {{ old('recommendation', $draft['recommendation'] ?? '') == 'accept' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Accept</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="reject" class="w-4 h-4" {{ old('recommendation', $draft['recommendation'] ?? '') == 'reject' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Reject</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="minor" class="w-4 h-4" {{ old('recommendation', $draft['recommendation'] ?? '') == 'minor' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Return for minor revisions</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="recommendation" value="major" class="w-4 h-4" {{ old('recommendation', $draft['recommendation'] ?? '') == 'major' ? 'checked' : '' }}>
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
                <input type="radio" name="Q1" value="yes" class="w-4 h-4" {{ old('Q1', $draft['Q1'] ?? '') == 'yes' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q1" value="no1" class="w-4 h-4" {{ old('Q1', $draft['Q1'] ?? '') == 'no1' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">No, it's not suitable for publication unless extencively edited</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q1" value="no2" class="w-4 h-4" {{ old('Q1', $draft['Q1'] ?? '') == 'no2' ? 'checked' : '' }}>
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
                <input type="radio" name="Q2" value="yes" class="w-4 h-4" {{ old('Q2', $draft['Q2'] ?? '') == 'yes' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q2" value="no1" class="w-4 h-4" {{ old('Q2', $draft['Q2'] ?? '') == 'no1' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">No, but these points can be addressed with revisions</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q2" value="no2" class="w-4 h-4" {{ old('Q2', $draft['Q2'] ?? '') == 'no2' ? 'checked' : '' }}>
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
                <input type="radio" name="Q3" value="yes" class="w-4 h-4" {{ old('Q3', $draft['Q3'] ?? '') == 'yes' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Yes</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="Q3" value="no1" class="w-4 h-4" {{ old('Q3', $draft['Q3'] ?? '') == 'no1' ? 'checked' : '' }}>
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
            <textarea name="comments_for_editor" id="confidentialTextarea" class="w-full border border-gray-300 rounded-md p-3 h-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your confidential comments here...">{{ old('comments_for_editor', $draft['comments_for_editor'] ?? '') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <button type="submit" form="reviewForm" formaction="{{ route('reviewer.saveDraft', $paper->id) }}" class="bg-slate-600 text-white px-8 py-3 rounded-md hover:bg-slate-500 transition font-semibold">
              Save Draft
            </button>
            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition font-semibold">
              Submit Review
            </button>
          </div>
        </div>
      </form>

      <!-- Preview Tab Content -->
      <div id="preview-tab" class="report-tab-content hidden p-8 bg-white rounded-lg shadow border border-gray-200">
        <!-- Header -->
        <div class="mb-6">
          <h4 class="text-2xl font-bold text-gray-900 mb-1">Preview Your Report</h4>
          <p class="text-sm text-gray-600">
            Please review your report carefully before submitting.
          </p>
        </div>

        <!-- Feedback for Author -->
        <div class="bg-gray-50 border border-gray-300 rounded-lg p-8 mb-8">
          <h4 class="text-xl font-semibold text-gray-900 mb-6">
            Feedback for the author(s)
          </h4>

          <!-- Uploaded Files -->
          <div class="mb-6" id="previewFiles" @if(empty($draft['review_file'])) style="display: none;" @endif>
            <h5 class="font-medium text-gray-900 mb-3">Uploaded Files</h5>
            <ul class="space-y-2" id="previewFilesList">
              @if(!empty($draft['review_file']) && !empty($draft['review_file_name']))
              <li class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-3">
                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"/>
                </svg>
                <span class="flex-1 text-sm text-gray-700 truncate">{{ $draft['review_file_name'] }}</span>
                <a href="{{ asset('storage/' . $draft['review_file']) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View</a>
              </li>
              @endif
            </ul>
          </div>

          <!-- Author Comments -->
          <div>
            <h5 class="font-medium text-gray-900 mb-3">Comments to the author(s)</h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-4 text-gray-700 whitespace-pre-wrap min-h-[120px]" id="previewComments">
              <span class="text-gray-400 italic">No comments provided yet</span>
            </div>
          </div>
        </div>

        <!-- Confidential Feedback -->
        <div class="bg-gray-50 border border-gray-300 rounded-lg p-8 mb-8" id="previewConfidentialSection">
          <h4 class="text-xl font-semibold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            Confidential feedback for the Editor
          </h4>

          <!-- Recommendation -->
          <div class="mb-6">
            <h5 class="font-medium text-gray-900 mb-2">Your recommendation</h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-3">
              <span class="text-gray-700 font-semibold" id="recommendationValue">
                Not selected
              </span>
            </div>
          </div>

          <!-- Q1 -->
          <div class="mb-4">
            <h5 class="font-medium text-gray-900 mb-2">
              Is the presentation of the work clear?
            </h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-gray-700" id="previewQ1">
              Not answered
            </div>
          </div>

          <!-- Q2 -->
          <div class="mb-4">
            <h5 class="font-medium text-gray-900 mb-2">
              Is the study design appropriate and supported by evidence?
            </h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-gray-700" id="previewQ2">
              Not answered
            </div>
          </div>

          <!-- Q3 -->
          <div class="mb-4">
            <h5 class="font-medium text-gray-900 mb-2">
              Are the methods sufficiently described to allow replication?
            </h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-3 text-gray-700" id="previewQ3">
              Not answered
            </div>
          </div>

          <!-- Confidential Notes -->
          <div>
            <h5 class="font-medium text-gray-900 mb-2">
              Confidential comments to the Editor
            </h5>
            <div class="bg-white border-2 border-gray-200 rounded-lg p-4 text-gray-700 whitespace-pre-wrap min-h-[100px]" id="previewConfidential">
              <span class="text-gray-400 italic">No confidential comments provided</span>
            </div>
          </div>
        </div>

        <!-- Info Box -->
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-8">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <div>
              <p class="text-sm font-medium text-blue-900">
                Review your information carefully
              </p>
              <p class="text-sm text-blue-700 mt-1">
                If everything looks correct, you may submit your review.
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

<script>
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
    }
    
    // Get Q1
    const selectedQ1 = document.querySelector('input[name="Q1"]:checked');
    const previewQ1 = document.getElementById('previewQ1');
    if (previewQ1) {
      if (selectedQ1) {
        const q1Labels = {
          'yes': 'Yes',
          'no1': 'No, it\'s not suitable for publication unless extensively edited',
          'no2': 'No, it needs some language corrections before being published'
        };
        previewQ1.textContent = q1Labels[selectedQ1.value] || selectedQ1.value;
      } else {
        previewQ1.textContent = 'Not answered';
      }
    }
    
    // Get Q2
    const selectedQ2 = document.querySelector('input[name="Q2"]:checked');
    const previewQ2 = document.getElementById('previewQ2');
    if (previewQ2) {
      if (selectedQ2) {
        const q2Labels = {
          'yes': 'Yes',
          'no1': 'No, but these points can be addressed with revisions',
          'no2': 'No, and there are fundamental issues that cannot be addressed'
        };
        previewQ2.textContent = q2Labels[selectedQ2.value] || selectedQ2.value;
      } else {
        previewQ2.textContent = 'Not answered';
      }
    }
    
    // Get Q3
    const selectedQ3 = document.querySelector('input[name="Q3"]:checked');
    const previewQ3 = document.getElementById('previewQ3');
    if (previewQ3) {
      if (selectedQ3) {
        const q3Labels = {
          'yes': 'Yes',
          'no1': 'No, but these points can be addressed with revisions',
          'no2': 'No, and there are fundamental issues that cannot be addressed'
        };
        previewQ3.textContent = q3Labels[selectedQ3.value] || selectedQ3.value;
      } else {
        previewQ3.textContent = 'Not answered';
      }
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
        previewConfidential.innerHTML = '<span class="text-gray-400 italic">No confidential comments provided</span>';
        if (previewConfidentialSection) previewConfidentialSection.classList.remove('hidden');
      }
    }
    
    // Show/hide preview files section
    const previewFiles = document.getElementById('previewFiles');
    const previewFilesList = document.getElementById('previewFilesList');
    const fileInput = document.getElementById('reviewFiles');
    const savedFileItem = document.querySelector('.saved-file-item');
    
    if (previewFiles && previewFilesList) {
      // Jangan hapus file yang sudah ada di HTML (saved file)
      const existingSavedFiles = previewFilesList.querySelectorAll('li');
      let hasFiles = existingSavedFiles.length > 0;
      
      // Show newly uploaded files (tambahkan ke yang sudah ada)
      if (fileInput && fileInput.files && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach(file => {
          const fileSize = (file.size / (1024 * 1024)).toFixed(2);
          const li = document.createElement('li');
          li.className = 'flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-3';
          li.innerHTML = `
            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"/>
            </svg>
            <span class="flex-1 text-sm text-gray-700 truncate">${file.name}</span>
            <span class="text-xs text-gray-500">${fileSize} MB</span>
          `;
          previewFilesList.appendChild(li);
          hasFiles = true;
        });
      }
      
      if (hasFiles) {
        previewFiles.style.display = 'block';
      } else {
        previewFiles.style.display = 'none';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
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

          // Cek apakah file sudah ada (baik di selectedFiles maupun di saved file)
          const savedFileItem = fileList.querySelector('.saved-file-item');
          const isSavedFile = savedFileItem && savedFileItem.dataset.fileName === file.name;
          const isAlreadySelected = selectedFiles.some(f => f.name === file.name);
          const isAlreadyDisplayed = fileList.querySelector(`[data-file-name="${file.name}"]`);
          
          // Jika file sudah tersimpan, ganti dengan file baru (hapus saved file item)
          if (isSavedFile) {
            savedFileItem.remove();
          }
          
          // Jika sudah ada di selectedFiles atau sudah ditampilkan (bukan saved file), skip
          if (isAlreadySelected) {
            return;
          }
          
          // Jika sudah ditampilkan dan bukan saved file, skip
          if (isAlreadyDisplayed && !isSavedFile) {
            return;
          }

          selectedFiles.push(file);
          displayFile(file);
        });
      }

      function displayFile(file) {
        // Cek apakah file sudah ada di list (baik saved file maupun newly uploaded)
        const existingFile = fileList.querySelector(`[data-file-name="${file.name}"]`);
        if (existingFile) {
          // Jika file sudah ada, jangan tambahkan lagi
          return;
        }
        
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
        // Jangan hapus file yang sudah tersimpan (saved file)
        const savedFileItem = fileList.querySelector('.saved-file-item');
        if (savedFileItem && savedFileItem.dataset.fileName === fileName) {
          alert('Cannot remove saved file. Please upload a new file to replace it.');
          return;
        }
        
        selectedFiles = selectedFiles.filter(file => file.name !== fileName);
        const fileItem = fileList.querySelector(`[data-file-name="${fileName}"]`);
        if (fileItem && !fileItem.classList.contains('saved-file-item')) {
          fileItem.remove();
        }
        // Update file input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
      };
      
      // Load saved file on page load
      const savedFileItem = fileList.querySelector('.saved-file-item');
      if (savedFileItem) {
        // File sudah ditampilkan di HTML, tidak perlu ditambahkan lagi
      }
    }

    // Auto-save functionality
    let autoSaveTimeout;
    const saveStatusText = document.getElementById('saveStatusText');
    const saveStatus = document.getElementById('saveStatus');
    const form = document.getElementById('reviewForm');
    const paperId = {{ $paper->id }};
    const saveUrl = '{{ route("reviewer.saveDraft", $paper->id) }}';

    // Function to update save status
    function updateSaveStatus(status, message) {
      if (status === 'saving') {
        saveStatusText.textContent = 'Saving...';
        saveStatus.classList.remove('text-green-600');
        saveStatus.classList.add('text-yellow-600');
      } else if (status === 'saved') {
        saveStatusText.textContent = message || 'All changes saved';
        saveStatus.classList.remove('text-yellow-600');
        saveStatus.classList.add('text-green-600');
      } else if (status === 'error') {
        saveStatusText.textContent = 'Error saving. Please try again.';
        saveStatus.classList.remove('text-green-600', 'text-yellow-600');
        saveStatus.classList.add('text-red-600');
      }
    }

    // Function to auto-save
    function autoSave() {
      clearTimeout(autoSaveTimeout);
      
      updateSaveStatus('saving');
      
      // Create FormData from form
      const formData = new FormData(form);
      
      // FormData sudah otomatis menyertakan semua field termasuk file dari form
      // Tidak perlu menambahkan file secara manual karena FormData(form) sudah include semua
      
      // Send AJAX request
      fetch(saveUrl, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const now = new Date();
          const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
          updateSaveStatus('saved', `Last saved at ${timeStr}`);
          
          // Jika ada file yang baru di-upload, reload halaman untuk menampilkan file yang sudah tersimpan
          if (fileInput && fileInput.files && fileInput.files.length > 0) {
            // Reload halaman setelah 1 detik untuk menampilkan file yang sudah tersimpan dari database
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          }
        } else {
          updateSaveStatus('error');
        }
      })
      .catch(error => {
        console.error('Auto-save error:', error);
        updateSaveStatus('error');
      });
    }

    // Debounced auto-save function
    function debouncedAutoSave() {
      clearTimeout(autoSaveTimeout);
      autoSaveTimeout = setTimeout(autoSave, 2000); // Save 2 seconds after user stops typing
    }

    // Add event listeners for form fields
    const formFields = [
      document.getElementById('commentsTextarea'),
      document.getElementById('confidentialTextarea'),
      ...document.querySelectorAll('input[name="recommendation"]'),
      ...document.querySelectorAll('input[name="Q1"]'),
      ...document.querySelectorAll('input[name="Q2"]'),
      ...document.querySelectorAll('input[name="Q3"]'),
    ];

    formFields.forEach(field => {
      if (field) {
        if (field.tagName === 'TEXTAREA') {
          field.addEventListener('input', debouncedAutoSave);
        } else if (field.tagName === 'INPUT') {
          field.addEventListener('change', debouncedAutoSave);
        }
      }
    });

    // Auto-save on file upload
    if (fileInput) {
      fileInput.addEventListener('change', function() {
        debouncedAutoSave();
      });
    }

    // Initial save status
    updateSaveStatus('saved', 'All changes saved');
  });
</script>

@endsection