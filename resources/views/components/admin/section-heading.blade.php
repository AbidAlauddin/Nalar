@props(['title', 'desc' => null])
<div class="sec-head">
    <h2>{{ $title }}</h2>
    @if ($desc)<p>{{ $desc }}</p>@endif
</div>
