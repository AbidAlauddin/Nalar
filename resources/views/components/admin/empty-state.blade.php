@props(['title', 'description' => null])

<div {{ $attributes->class('py-5') }}>
    <p class="text-[13px] font-medium text-nalar-text">{{ $title }}</p>
    @if ($description)<p class="mt-1 max-w-prose text-xs leading-relaxed text-stone-500">{{ $description }}</p>@endif
    {{ $slot }}
</div>