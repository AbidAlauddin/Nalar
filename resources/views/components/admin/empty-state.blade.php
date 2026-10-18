@props(['title', 'description' => null])

<div {{ $attributes->class('py-6') }}>
    <p class="text-[13px] font-medium">{{ $title }}</p>
    @if ($description)<p class="mt-1 max-w-prose text-[13px] text-stone-500">{{ $description }}</p>@endif
    {{ $slot }}
</div>