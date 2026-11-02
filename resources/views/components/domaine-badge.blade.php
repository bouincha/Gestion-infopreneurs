@props(['domaine'])
@if ($domaine)
    <span class="badge badge-domaine badge-dot" style="--c: {{ $domaine->couleur }}">{{ $domaine->nom }}</span>
@else
    <span class="badge">Sans domaine</span>
@endif
