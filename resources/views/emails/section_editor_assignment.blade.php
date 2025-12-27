@if(!empty($emailBody))
{!! $emailBody !!}
@else
<p>You have been assigned as <strong>Section Editor</strong> for the manuscript:</p>

<p><strong>{{ $paper->judul }}</strong></p>

<p>Please handle the editorial process for this manuscript via the journal system.</p>
@endif
