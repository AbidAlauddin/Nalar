@props(['href' => null, 'active' => false, 'icon' => null])

@if ($href)
    <a href="{{ $href }}"
       @if ($active) aria-current="page" @endif
       class="relative flex h-10 items-center gap-3 rounded-xl px-3.5 text-[13px] whitespace-nowrap transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-nalar
              {{ $active ? 'bg-nalar-soft font-semibold text-nalar' : ' font-medium text-stone-600 hover:bg-stone-100 hover:text-nalar-text' }}">
        @if ($icon)
            <x-admin.icon :name="$icon" class="size-[18px] shrink-0 {{ $active ? 'text-nalar' : 'text-stone-400' }}" />
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <span aria-disabled="true" title="Modul ini belum tersedia"
          class="flex h-10 cursor-not-allowed items-center justify-between gap-3 rounded-xl px-3.5 text-[13px] whitespace-nowrap text-stone-400">
        <span class="flex items-center gap-3 min-w-0">
            @if ($icon)
                <x-admin.icon :name="$icon" class="size-[18px] shrink-0 text-stone-300" />
            @endif
            <span class="truncate">{{ $slot }}</span>
        </span>
        <span class="shrink-0 rounded bg-stone-100 px-1.5 py-0.5 text-[10px] font-medium text-stone-400">Segera</span>
    </span>
@endif