@props(['icon' => 'sparkles', 'title', 'action' => null, 'actionLabel' => null])
<div class="empty">
    <div class="empty-icon"><x-icon :name="$icon" class="icon-lg" /></div>
    <h3>{{ $title }}</h3>
    <p>{{ $slot }}</p>
    @if ($action)
        <a href="{{ $action }}" class="btn btn-primary"><x-icon name="plus" /> {{ $actionLabel }}</a>
    @endif
</div>
