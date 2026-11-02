@props(['name', 'bag' => 'default'])
@error($name, $bag)
    <span class="error-text"><x-icon name="alert" class="icon-sm" /> {{ $message }}</span>
@enderror
