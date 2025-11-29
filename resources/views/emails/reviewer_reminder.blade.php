<p>Dear {{ $reviewerName }},</p>

<p>
    Just a gentle reminder regarding the manuscript
    <strong>"{{ $articleTitle }}"</strong>
    which is currently assigned to you for review.
</p>

<p>
    We noticed that the review deadline is approaching
    <strong>{{ $deadline ?? '-' }}</strong>.<br>
    We would appreciate it if you could submit your review soon.
</p>


<p>Best regards,<br>
    {{ $editorName }}
</p>