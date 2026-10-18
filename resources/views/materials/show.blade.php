@php
    $sections = $material->contentSections();
@endphp

<x-layouts.app :title="$material->title">
    {{-- Navigasi Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="mb-5 flex flex-wrap items-center justify-between gap-3 text-xs text-stone-500">
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('materials.index') }}" class="font-medium text-stone-600 hover:text-nalar">Materi SMA</a>
            <span>/</span>
            @if ($material->category)
                <a href="{{ route('materials.index', ['category' => $material->category->slug]) }}" class="font-medium text-stone-600 hover:text-nalar">
                    {{ $material->category->name }}
                </a>
                <span>/</span>
            @endif
            <span class="truncate text-nalar-text">{{ $material->title }}</span>
        </div>

        <a href="{{ route('materials.index') }}"
           class="rounded-lg border border-stone-200 bg-white px-3 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
            &larr; Kembali ke Daftar Materi
        </a>
    </nav>

    @if (! $material->isPublished())
        <div role="status" class="mb-5 rounded-xl border border-orange-200 bg-nalar-soft px-4 py-3 text-xs font-medium text-nalar">
            Mode Pratinjau Pengajar: Materi ini masih berstatus <strong>Draf</strong> dan belum ditampilkan kepada siswa.
        </div>
    @endif

    {{-- Header Materi & Ringkasan Akademik --}}
    <header class="mb-6 rounded-2xl border border-stone-200 bg-white p-6 sm:p-7">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="rounded bg-nalar-soft px-2.5 py-0.5 font-semibold text-nalar">
                {{ $material->category?->name ?? 'Materi SMA' }}
            </span>
            <span class="rounded bg-stone-100 px-2.5 py-0.5 font-medium text-stone-700">
                {{ $material->education_level }} &middot; {{ $material->gradeLabel() }}
            </span>
            <span class="rounded bg-stone-100 px-2.5 py-0.5 font-medium text-stone-600">
                Estimasi {{ $material->estimated_minutes }} menit baca
            </span>
            <span class="rounded bg-stone-100 px-2.5 py-0.5 font-medium text-stone-600">
                {{ count($sections) }} Subbab
            </span>
        </div>

        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-nalar-text sm:text-[26px] sm:leading-snug">
            {{ $material->title }}
        </h1>

        <p class="mt-2 text-xs text-stone-500">
            Disusun oleh <strong class="font-medium text-nalar-text">{{ $material->author?->name ?? 'Tim Pengajar Nalar' }}</strong>
            @if ($material->published_at)
                &middot; Dipublikasikan {{ $material->published_at->format('d M Y') }}
            @endif
        </p>

        {{-- Pokok Bahasan Callout --}}
        <div class="mt-5 rounded-xl border border-stone-200/80 border-l-4 border-l-nalar bg-nalar-bg p-4">
            <h2 class="text-[11px] font-semibold tracking-wider text-nalar uppercase">Pokok Bahasan & Tujuan Belajar</h2>
            <p class="mt-1 text-xs leading-relaxed text-stone-700 sm:text-sm">
                {{ $material->description }}
            </p>
        </div>
    </header>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-start">
        {{-- Area Baca Utama --}}
        <div class="space-y-5">
            {{-- Gambar Ilustrasi Materi --}}
            @if ($material->imageUrl())
                <figure class="overflow-hidden rounded-2xl border border-stone-200 bg-white">
                    <div class="flex items-center justify-center border-b border-stone-100 bg-nalar-bg p-4 sm:p-6">
                        <img src="{{ $material->imageUrl() }}"
                             alt="{{ $material->image_caption ?: $material->title }}"
                             class="max-h-80 w-full rounded-xl object-contain">
                    </div>
                    <figcaption class="flex flex-wrap items-center gap-2 px-5 py-3 text-xs text-stone-600">
                        <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">Ilustrasi Materi</span>
                        <span>{{ $material->image_caption ?: 'Visual pendukung pemahaman materi ' . $material->title }}</span>
                    </figcaption>
                </figure>
            @endif

            {{-- Uraian Subbab Terstruktur --}}
            <article class="overflow-hidden rounded-2xl border border-stone-200 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-200 bg-nalar-bg px-6 py-4">
                    <h2 class="text-base font-semibold text-nalar-text">Pembahasan Materi</h2>
                    <span class="text-xs font-medium text-stone-500">{{ count($sections) }} Bagian Pembahasan</span>
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

                                    <div class="mt-3.5 space-y-3.5 text-[15px] leading-relaxed text-nalar-text">
                                        @foreach ($section['blocks'] as $block)
                                            @if ($block['type'] === 'paragraph')
                                                <p class="text-stone-700">{{ $block['text'] }}</p>
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

        {{-- Panel Samping Daftar Pembahasan, Informasi & Materi Terkait --}}
        <aside aria-label="Informasi mata pelajaran dan materi terkait" class="space-y-5">
            @if (count($sections) > 0)
                <section class="rounded-2xl border border-stone-200 bg-white p-5">
                    <h2 class="text-xs font-semibold tracking-wider text-stone-400 uppercase">Daftar Pembahasan</h2>
                    <ol class="mt-3 space-y-1.5">
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

            <section class="rounded-2xl border border-stone-200 bg-white p-5">
                <h2 class="text-xs font-semibold tracking-wider text-stone-400 uppercase">Informasi Materi</h2>
                <dl class="mt-3 divide-y divide-stone-100 text-xs">
                    <div class="flex justify-between py-2">
                        <dt class="text-stone-500">Jenjang</dt>
                        <dd class="font-semibold text-nalar-text">{{ $material->education_level }}</dd>
                    </div>
                    <div class="flex justify-between py-2">
                        <dt class="text-stone-500">Tingkat Kelas</dt>
                        <dd class="font-medium text-nalar-text">{{ $material->gradeLabel() }}</dd>
                    </div>
                    <div class="flex justify-between py-2">
                        <dt class="text-stone-500">Mata Pelajaran</dt>
                        <dd class="font-medium text-nalar-text">{{ $material->category?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-2">
                        <dt class="text-stone-500">Waktu Belajar</dt>
                        <dd class="font-medium text-nalar-text">{{ $material->estimated_minutes }} menit</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-nalar-text">Materi Terkait</h2>
                <p class="mt-0.5 text-xs text-stone-500">Topik lain pada mata pelajaran {{ $material->category?->name ?? 'ini' }}</p>

                @if ($relatedMaterials->isEmpty())
                    <p class="mt-3 text-xs text-stone-500">Belum ada materi terkait lainnya pada kategori ini.</p>
                @else
                    <ul class="mt-3 divide-y divide-stone-100">
                        @foreach ($relatedMaterials as $related)
                            <li class="py-2.5">
                                <a href="{{ route('materials.show', $related->slug) }}" class="group block">
                                    <p class="text-xs font-medium text-nalar-text group-hover:text-nalar">{{ $related->title }}</p>
                                    <p class="mt-0.5 text-[11px] text-stone-400">{{ $related->gradeLabel() }} &middot; {{ $related->estimated_minutes }} menit</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </aside>
    </div>
</x-layouts.app>
