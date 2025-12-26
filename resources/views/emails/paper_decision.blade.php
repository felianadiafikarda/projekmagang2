<p>Dear {{ $author->first_name }} {{ $author->last_name }},</p>

{!! nl2br($emailBody) !!}

<p>
    Best regards,<br>
    {{ $editorName }}<br>
    Editorial Team
</p>