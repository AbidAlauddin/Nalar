@props(['href' => null, 'active' => false])

@if ($href)
    <a href="{{ $href }}"
       @if ($active) aria-current="page" @endif
       class="relative flex h-9 items-center rounded-md px-3 text-[13px] whitespace-nowrap transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-nalar
              {{ $active ? 'bg-nalar-soft font-medium text-nalar-text before:absolute before:inset-y-2 before:left-0 before:w-[3px] before:rounded-r before:bg-nalar' : 'text-stone-600 hover:bg-stone-100 hover:text-nalar-text' }}">
        {{ $slot }}
    </a>
@else
    <span aria-disabled="true" title="Modul ini belum tersedia"
          class="flex h-9 cursor-not-allowed items-center rounded-md px-3 text-[13px] whitespace-nowrap text-stone-400">
        {{ $slot }}
    </span>
@endif