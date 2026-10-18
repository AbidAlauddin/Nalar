@props(['paginator'])

@if ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman tabel"
         {{ $attributes->merge(['class' => 'flex flex-col gap-3 border-t border-stone-200/80 bg-nalar-bg/50 px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between']) }}>
        <p class="text-xs text-stone-500">
            Menampilkan
            <span class="font-semibold tabular-nums text-nalar-text">{{ $paginator->firstItem() }}</span>&ndash;<span class="font-semibold tabular-nums text-nalar-text">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold tabular-nums text-nalar-text">{{ $paginator->total() }}</span>
            data
        </p>

        <div class="flex flex-wrap items-center gap-1.5">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true"
                      class="inline-flex cursor-not-allowed items-center rounded-lg border border-stone-200/70 bg-stone-100/70 px-3 py-1.5 text-xs font-medium text-stone-400">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center rounded-lg border border-stone-200 bg-white px-3 py-1.5 text-xs font-medium text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                    &larr; Sebelumnya
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp

            @if ($startPage > 1)
                <a href="{{ $paginator->url(1) }}"
                   class="inline-flex size-8 items-center justify-center rounded-lg border border-stone-200 bg-white text-xs font-medium tabular-nums text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                    1
                </a>
                @if ($startPage > 2)
                    <span class="px-1 text-xs text-stone-400">&hellip;</span>
                @endif
            @endif

            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page === $currentPage)
                    <span aria-current="page"
                          class="inline-flex size-8 items-center justify-center rounded-lg bg-nalar text-xs font-semibold tabular-nums text-[#FFFCF8]">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                       class="inline-flex size-8 items-center justify-center rounded-lg border border-stone-200 bg-white text-xs font-medium tabular-nums text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if ($endPage < $lastPage)
                @if ($endPage < $lastPage - 1)
                    <span class="px-1 text-xs text-stone-400">&hellip;</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}"
                   class="inline-flex size-8 items-center justify-center rounded-lg border border-stone-200 bg-white text-xs font-medium tabular-nums text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                    {{ $lastPage }}
                </a>
            @endif

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center rounded-lg border border-stone-200 bg-white px-3 py-1.5 text-xs font-medium text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                    Berikutnya &rarr;
                </a>
            @else
                <span aria-disabled="true"
                      class="inline-flex cursor-not-allowed items-center rounded-lg border border-stone-200/70 bg-stone-100/70 px-3 py-1.5 text-xs font-medium text-stone-400">
                    Berikutnya &rarr;
                </span>
            @endif
        </div>
    </nav>
@endif
