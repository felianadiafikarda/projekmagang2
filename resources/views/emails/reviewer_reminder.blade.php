@php
$emailBody = $emailBody ?? '';

// Template default jika emailBody kosong
if (trim($emailBody) === '') {
$emailBody = "
Just a gentle reminder regarding the manuscript \"{$articleTitle}\" which is currently assigned to you for
review.<br><br>
We noticed that the deadline is approaching ({$deadline}). We would appreciate it if you could submit your review soon.
";
}

$reviewerName = $reviewerName ?? ($names ?? 'Reviewer');
$articleTitle = $articleTitle ?? '';
$deadline = $deadline ?? '';
$editorName = $editorName ?? '';
@endphp

@if(!str_contains($emailBody, 'Dear'))
<p>Dear {{ $reviewerName }},</p>
@endif

{!! $emailBody !!}

@if(!str_contains($emailBody, $editorName))
<p>Best regards,<br>{{ $editorName }}</p>
@endif