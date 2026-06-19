@props(['active' => false])

<button type="button" {{ $attributes->merge([
    'class' => 'public-chip motion-tap cursor-pointer',
]) }} @class(['is-active' => $active])>
    {{ $slot }}
</button>
