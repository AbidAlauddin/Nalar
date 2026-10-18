{{-- $value === null berarti data belum tersedia (tabel/modul belum ada), bukan nol. --}}
@props(['label', 'value' => null, 'hint' => null, 'icon' => null])

<div class="flex items-center gap-4 rounded-2xl border border-stone-200/80 bg-white p-4 sm:p-5">
    @if ($icon)
        <div class="grid size-12 shrink-0 place-items-center rounded-xl bg-nalar-soft text-nalar">
            <x-admin.icon :name="$icon" class="size-5" />
        </div>
    @endif

    <div class="min-w-0 flex-1">
        <p class="truncate text-xs font-medium text-stone-500">{{ $label }}</p>
        @if ($value === null)
            <div class="mt-1 flex items-baseline gap-2">
                <p class="text-xl font-semibold text-stone-300">&mdash;</p>
                <p class="truncate text-xs text-stone-400">Belum tersedia</p>
            </div>
        @else
            <p class="mt-0.5 text-2xl font-semibold tracking-tight tabular-nums text-nalar-text">
                {{ number_format($value, 0, ',', '.') }}
            </p>
            @if ($hint)
                <p class="mt-0.5 truncate text-[11px] text-stone-500">{{ $hint }}</p>
            @endif
        @endif
    </div>
</div>