{{-- $value === null berarti data belum tersedia (tabel/modul belum ada), bukan nol. --}}
@props(['label', 'value' => null, 'hint' => null])

<div class="rounded-lg border border-stone-200 bg-white p-4">
    <p class="text-[13px] text-stone-500">{{ $label }}</p>
    @if ($value === null)
        <p class="mt-1 text-2xl font-semibold text-stone-300">-</p>
        <p class="mt-1 text-xs text-stone-500">Belum tersedia</p>
    @else
        <p class="mt-1 text-[28px] leading-9 font-semibold tabular-nums">{{ number_format($value, 0, ',', '.') }}</p>
        @if ($hint)<p class="mt-1 text-xs text-stone-500">{{ $hint }}</p>@endif
    @endif
</div>