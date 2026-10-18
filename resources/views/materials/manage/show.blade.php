@php
    $sections = $material->contentSections();
@endphp

<x-layouts.admin :title="$material->title">
    {{-- Header Halaman & Aksi Utama --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded bg-nalar-soft px-2.5 py-0.5 text-[11px] font-semibold text-nalar">
                    {{ $material->education_level }} &middot; {{ $material->gradeLabel() }}
                </span>
                <span class="rounded bg-stone-100 px-2.5 py-0.5 text-[11px] font-medium text-stone-700">
                    {{ $material->category?->name ?? 'Tanpa kategori' }}
                </span>
                <span class="rounded px-2.5 py-0.5 text-[11px] font-medium {{ $material->isPublished() ? 'bg-nalar-soft text-nalar' : 'bg-stone-200 text-stone-600' }}">
                    {{ $material->statusLabel() }}
                </span>
            </div>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-nalar-text">{{ $material->title }}</h1>
            <p class="mt-1 text-xs text-stone-500">
                Disusun oleh <strong>{{ $material->author?->name ?? '-' }}</strong>
                &middot; Estimasi {{ $material->estimated_minutes }} menit baca
                @if ($material->updated_at)
                    &middot; Diperbarui {{ $material->updated_at->format('d M Y, H:i') }}
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($routePrefix . '.materials.index') }}"
               class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-medium text-stone-600 hover:bg-stone-50">
                Kembali ke Daftar
            </a>
            <a href="{{ route('materials.show', $material->slug) }}"
               class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-medium text-nalar-text hover:bg-stone-50">
                Lihat Tampilan Siswa
            </a>
            @if ($canManage)
                <a href="{{ route($routePrefix . '.materials.edit', $material) }}"
                   class="rounded-xl bg-nalar px-4 py-2 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                    Edit Materi
                </a>
            @endif
        </div>
    </div>

    {{-- Baris Ringkasan Metadata Akademik (Full Width) --}}
    <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-stone-200/80 bg-white px-4 py-3.5">
            <p class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Mata Pelajaran</p>
            <p class="mt-1 text-sm font-semibold text-nalar-text">{{ $material->category?->name ?? 'Tanpa kategori' }}</p>
            <p class="mt-0.5 text-[11px] text-stone-500">Kurikulum {{ $material->education_level }}</p>
        </div>

        <div class="rounded-2xl border border-stone-200/80 bg-white px-4 py-3.5">
            <p class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Tingkat Kelas</p>
            <p class="mt-1 text-sm font-semibold text-nalar-text">{{ $material->gradeLabel() }}</p>
            <p class="mt-0.5 text-[11px] text-stone-500">Sekolah Menengah Atas</p>
        </div>

        <div class="rounded-2xl border border-stone-200/80 bg-white px-4 py-3.5">
            <p class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Durasi & Struktur</p>
            <p class="mt-1 text-sm font-semibold text-nalar-text">{{ $material->estimated_minutes }} Menit Baca</p>
            <p class="mt-0.5 text-[11px] text-stone-500">{{ count($sections) }} subbab pembahasan</p>
        </div>

        <div class="rounded-2xl border border-stone-200/80 bg-white px-4 py-3.5">
            <p class="text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Penyusun Materi</p>
            <p class="mt-1 truncate text-sm font-semibold text-nalar-text">{{ $material->author?->name ?? '-' }}</p>
            <p class="mt-0.5 text-[11px] text-stone-500">
                Status: <span class="font-medium text-nalar">{{ $material->statusLabel() }}</span>
            </p>
        </div>
    </div>

    {{-- Layout Dua Kolom Full-Width: Konten Utama & Panel Navigasi/Informasi --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px] xl:items-start">
        {{-- Kolom Kiri: Visual Ilustrasi, Pokok Bahasan, dan Pembahasan Terstruktur --}}
        <div class="space-y-5">
            {{-- Panel Gambar Ilustrasi Materi --}}
            @if ($material->imageUrl())
                <figure class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white">
                    <div class="flex items-center justify-center border-b border-stone-200/60 bg-nalar-bg p-4 sm:p-6">
                        <img src="{{ $material->imageUrl() }}"
                             alt="{{ $material->image_caption ?: $material->title }}"
                             class="max-h-80 w-full rounded-xl object-contain">
                    </div>
                    <figcaption class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 text-xs text-stone-600">
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">Ilustrasi Materi</span>
                            <span>{{ $material->image_caption ?: 'Visual pendukung pemahaman materi ' . $material->title }}</span>
                        </div>
                        @if ($canManage)
                            <a href="{{ route($routePrefix . '.materials.edit', $material) }}"
                               class="text-[11px] font-semibold text-nalar hover:underline">
                                Ubah Gambar
                            </a>
                        @endif
                    </figcaption>
                </figure>
            @elseif ($canManage)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-dashed border-stone-300 bg-white px-5 py-4">
                    <div>
                        <p class="text-xs font-semibold text-nalar-text">Belum ada gambar ilustrasi pada materi ini</p>
                        <p class="mt-0.5 text-xs text-stone-500">
                            Anda dapat menyisipkan gambar diagram, grafik, atau skema agar materi lebih visual bagi siswa SMA.
                        </p>
                    </div>
                    <a href="{{ route($routePrefix . '.materials.edit', $material) }}"
                       class="rounded-xl bg-nalar-soft px-3.5 py-2 text-xs font-semibold text-nalar hover:bg-orange-200/70">
                        + Sisipkan Gambar
                    </a>
                </div>
            @endif

            {{-- Kotak Fokus: Ringkasan / Tujuan Pembelajaran --}}
            <section aria-label="Ringkasan materi" class="rounded-2xl border border-stone-200/80 border-l-4 border-l-nalar bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="text-xs font-semibold tracking-wider text-nalar uppercase">Pokok Bahasan & Tujuan Pembelajaran</h2>
                    <span class="text-[11px] font-medium text-stone-400">Ringkasan Materi</span>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-stone-700">{{ $material->description }}</p>
            </section>

            {{-- Konten Materi Pembelajaran Terstruktur --}}
            <article aria-label="Isi materi pembelajaran" class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-200/80 bg-nalar-bg px-6 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-nalar-text">Uraian Materi Pembelajaran</h2>
                        <p class="mt-0.5 text-xs text-stone-500">
                            Disajikan per bagian subbab untuk memudahkan pemahaman konsep siswa SMA
                        </p>
                    </div>
                    <span class="rounded-lg bg-white px-2.5 py-1 text-xs font-medium text-stone-600 border border-stone-200/80">
                        {{ count($sections) }} Bagian
                    </span>
                </div>

                <div class="divide-y divide-stone-200/80 p-6 sm:p-8">
                    @foreach ($sections as $index => $section)
                        <section id="{{ $section['id'] }}" class="{{ $index > 0 ? 'pt-7' : '' }} {{ ! $loop->last ? 'pb-7' : '' }} scroll-mt-20">
                            <div class="flex items-start gap-3.5">
                                <span class="inline-flex size-7 shrink-0 items-center justify-center rounded-lg bg-nalar-soft text-xs font-semibold tabular-nums text-nalar">
                                    {{ $section['number'] }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-semibold tracking-tight text-nalar-text sm:text-[17px]">
                                        {{ $section['title'] }}
                                    </h3>

                                    <div class="mt-3.5 space-y-3.5 text-[14.5px] leading-relaxed text-stone-700">
                                        @foreach ($section['blocks'] as $block)
                                            @if ($block['type'] === 'paragraph')
                                                <p>{{ $block['text'] }}</p>
                                            @elseif ($block['type'] === 'formula')
                                                <div class="rounded-xl border border-stone-200 bg-nalar-bg px-4 py-3 font-mono text-xs font-semibold text-nalar-text sm:text-sm">
                                                    {{ $block['text'] }}
                                                </div>
                                            @elseif ($block['type'] === 'list')
                                                <ul class="space-y-2 rounded-xl border border-stone-200/70 bg-nalar-bg/60 p-4">
                                                    @foreach ($block['items'] as $item)
                                                        <li class="flex items-start gap-2.5 text-sm text-stone-700">
                                                            <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-nalar"></span>
                                                            <span>{{ $item }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
            </article>
        </div>

        {{-- Kolom Kanan: Daftar Pembahasan (TOC), Detail Akademik, & Materi Terkait --}}
        <aside aria-label="Navigasi bagian dan detail materi" class="space-y-5">
            @if (count($sections) > 0)
                <section class="rounded-2xl border border-stone-200/80 bg-white p-5">
                    <h2 class="text-xs font-semibold tracking-wider text-stone-400 uppercase">Daftar Pembahasan</h2>
                    <p class="mt-0.5 text-xs text-stone-500">Navigasi cepat ke setiap subbab materi</p>

                    <ol class="mt-3.5 space-y-1.5">
                        @foreach ($sections as $section)
                            <li>
                                <a href="#{{ $section['id'] }}"
                                   class="group flex items-center gap-2.5 rounded-xl px-2.5 py-2 text-xs transition-colors hover:bg-nalar-soft/60">
                                    <span class="inline-flex size-5 shrink-0 items-center justify-center rounded bg-stone-100 text-[11px] font-semibold tabular-nums text-stone-600 group-hover:bg-nalar group-hover:text-[#FFFCF8]">
                                        {{ $section['number'] }}
                                    </span>
                                    <span class="font-medium text-nalar-text group-hover:text-nalar">
                                        {{ $section['title'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            <section class="rounded-2xl border border-stone-200/80 bg-white p-5">
                <h2 class="text-xs font-semibold tracking-wider text-stone-400 uppercase">Informasi Teknis Materi</h2>
                <dl class="mt-3 divide-y divide-stone-100 text-xs">
                    <div class="flex items-center justify-between py-2.5">
                        <dt class="text-stone-500">Slug URL</dt>
                        <dd class="max-w-[170px] truncate font-mono text-[11px] text-stone-700">{{ $material->slug }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <dt class="text-stone-500">Gambar Ilustrasi</dt>
                        <dd class="font-medium {{ $material->hasImage() ? 'text-nalar' : 'text-stone-500' }}">
                            {{ $material->hasImage() ? 'Tersedia' : 'Belum ada' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <dt class="text-stone-500">Tanggal Publikasi</dt>
                        <dd class="font-medium text-nalar-text">
                            {{ $material->published_at ? $material->published_at->format('d M Y') : 'Belum dipublikasi' }}
                        </dd>
                    </div>
                    @if ($material->category)
                        <div class="flex items-center justify-between py-2.5">
                            <dt class="text-stone-500">Detail Kategori</dt>
                            <dd>
                                <a href="{{ route($routePrefix . '.categories.show', $material->category) }}"
                                   class="font-semibold text-nalar hover:underline">
                                    Lihat Kategori &rarr;
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </section>

            @if (isset($relatedMaterials) && $relatedMaterials->isNotEmpty())
                <section class="rounded-2xl border border-stone-200/80 bg-white p-5">
                    <h2 class="text-sm font-semibold text-nalar-text">Materi Lain di {{ $material->category?->name }}</h2>
                    <p class="mt-0.5 text-xs text-stone-500">Bahan ajar SMA pada mata pelajaran yang sama</p>

                    <ul class="mt-3 divide-y divide-stone-100">
                        @foreach ($relatedMaterials as $related)
                            <li class="py-2.5">
                                <a href="{{ route($routePrefix . '.materials.show', $related) }}" class="group block">
                                    <p class="text-xs font-medium text-nalar-text group-hover:text-nalar">{{ $related->title }}</p>
                                    <p class="mt-0.5 text-[11px] text-stone-400">
                                        {{ $related->gradeLabel() }} &middot; {{ $related->estimated_minutes }} menit baca
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </aside>
    </div>
</x-layouts.admin>
