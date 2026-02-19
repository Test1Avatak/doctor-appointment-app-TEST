@props(['tab'])

<div x-show="tab === '{{ $tab }}'" x-cloak>
    {{ $slot }}
</div>
