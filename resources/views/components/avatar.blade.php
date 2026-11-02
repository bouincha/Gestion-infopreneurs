@props(['src' => null, 'initials' => '?', 'size' => 40])
@if ($src)
    <img src="{{ $src }}" alt="" {{ $attributes->merge(['class' => 'avatar']) }} style="--size: {{ $size }}px" loading="lazy">
@else
    <span {{ $attributes->merge(['class' => 'avatar']) }} style="--size: {{ $size }}px">{{ $initials ?: '?' }}</span>
@endif
