@php
// Pastikan semua variabel aman
$emailBody = $emailBody ?? '';
$names = $names ?? 'Reviewer';
$articleTitle = $articleTitle ?? '';
$articleUrl = $articleUrl ?? '#';
$deadline = $deadline ?? '';
$editorName = $editorName ?? 'Editor';
$invitationUrl = $invitationUrl ?? '#';

// TEMPLATE DEFAULT JIKA MODAL KOSONG
if (trim($emailBody) === '') {
$emailBody = "
I believe that you would serve as an excellent reviewer of the manuscript, \"{$articleTitle}\".<br><br>
Please click the button below to view the invitation details and indicate whether you will undertake the review or not.<br><br>
Thank you for considering this request.
";
}
@endphp

{{-- Dear --}}
@if(!str_contains($emailBody, 'Dear'))
<p>Dear {{ $names }},</p>
@endif

<br>

{{-- Body --}}
{!! $emailBody !!}

<br>

{{-- Title (hanya muncul jika belum ada di body) --}}
@if(!str_contains($emailBody, $articleTitle))
<p><strong>Title:</strong> {{ $articleTitle }}</p>
@endif

{{-- Deadline --}}
@if(!str_contains($emailBody, $deadline))
<p><strong>Deadline:</strong> {{ $deadline }}</p>
@endif

{{-- Invitation Link Button --}}
@if(isset($invitationUrl) && $invitationUrl !== '#')
<br>
<p style="text-align: center; margin: 30px 0;">
    <a href="{{ $invitationUrl }}" 
       style="display: inline-block; 
              background-color: #1e3a5f; 
              color: #ffffff; 
              padding: 14px 32px; 
              text-decoration: none; 
              border-radius: 25px; 
              font-weight: 600;
              font-size: 16px;">
        View Invitation & Respond
    </a>
</p>
<p style="text-align: center; color: #666; font-size: 12px;">
    Or copy this link: <a href="{{ $invitationUrl }}" style="color: #0d6efd;">{{ $invitationUrl }}</a>
</p>
<br>
@endif

{{-- Best regards --}}
@if(!str_contains($emailBody, $editorName))
<p>Best regards,<br>{{ $editorName }}</p>
@endif