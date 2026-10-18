@php
    use App\Models\Material;
@endphp

<x-layouts.app title="Materi Pembelajaran SMA">
    <div class="mb-6 flex flex-wrap items-baseline justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Materi Pembelajaran</h1>
                <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">SMA</span>
            </div>
            <p class="mt-1 text-xs text-stone-500">
                Pelajari konsep mata pelajaran Sekolah Menengah Atas (Kelas X, XI, dan XII) secara terstruktur.
            </p>
        </div>
    </div>

    {{-- Pencarian & Filter Kelas --}}
    <div class="mb-6 rounded-xl border border-stone-200 bg-white p-4 sm:p-5">
        <form method="GET" action="{{ route('materials.index') }}" class="space-y-3.5">
            @if ($selectedCategory !== '')
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif

            {{-- Baris 1: Pencarian --}}
            <div>
                <label for="q" class="mb-1.5 block text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                    Pencarian Materi
                </label>
                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <input id="q" name="q" type="search" value="{{ $search }}"
                               placeholder="Cari judul materi atau topik bahasan SMA..."
                               class="w-full rounded-lg border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-xs text-nalar-text placeholder:text-stone-400 focus:border-nalar focus:outline-none">
                    </div>
                    <button type="submit"
                            class="inline-flex shrink-0 items-center justify-center rounded-lg bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                        Cari Materi
                    </button>
                </div>
            </div>

            {{-- Baris 2: Filter Kelas SMA (Dipisahkan dari baris Search) --}}
            <div class="border-t border-stone-100 pt-3.5">
                <p class="mb-2 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                    Filter Kelas & Mata Pelajaran
                </p>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative sm:w-56">
                        <label for="grade" class="sr-only">Pilih kelas SMA</label>
                        <select id="grade" name="grade"
                                class="w-full appearance-none rounded-lg border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Semua Kelas SMA</option>
                            @foreach (Material::GRADES as $key => $label)
                                <option value="{{ $key }}" @selected($selectedGrade === (string) $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="rounded-lg border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                            Terapkan Filter
                        </button>
                        @if ($search !== '' || $selectedCategory !== '' || $selectedGrade !== '')
                            <a href="{{ route('materials.index') }}"
                               class="rounded-lg border border-stone-200 px-3 py-2 text-xs font-medium text-nalar hover:bg-nalar-soft/50">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        {{-- Filter Kategori Mata Pelajaran SMA --}}
        @if ($categories->isNotEmpty())
            <div class="mt-3.5 flex flex-wrap items-center gap-2 border-t border-stone-100 pt-3.5" aria-label="Filter mata pelajaran SMA">
                <a href="{{ route('materials.index', array_filter(['q' => $search, 'grade' => $selectedGrade])) }}"
                   class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $selectedCategory === '' ? 'bg-nalar text-[#FFFCF8]' : 'bg-stone-100 text-stone-600 hover:bg-stone-200/70' }}">
                    Semua Mata Pelajaran
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('materials.index', array_filter(['category' => $category->slug, 'q' => $search, 'grade' => $selectedGrade])) }}"
                       class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $selectedCategory === $category->slug ? 'bg-nalar text-[#FFFCF8]' : 'bg-stone-100 text-stone-600 hover:bg-stone-200/70' }}">
                        {{ $category->name }}
                        <span class="ml-1 opacity-75">({{ $category->published_materials_count }})</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @if ($activeCategory)
        <div class="mb-5 rounded-xl border border-stone-200 bg-white px-4 py-3.5">
            <p class="text-xs font-semibold text-nalar">{{ $activeCategory->name }} ({{ $activeCategory->education_level }})</p>
            @if ($activeCategory->description)
                <p class="mt-0.5 text-xs text-stone-600">{{ $activeCategory->description }}</p>
            @endif
        </div>
    @endif

    {{-- Daftar Materi --}}
    @if ($materials->isEmpty())
        <div class="rounded-xl border border-stone-200 bg-white p-6">
            <x-admin.empty-state
                title="Materi pembelajaran tidak ditemukan"
                description="Belum ada materi pembelajaran SMA yang sesuai dengan kata kunci atau filter yang dipilih." />
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($materials as $material)
                <article class="flex flex-col justify-between overflow-hidden rounded-xl border border-stone-200 bg-white transition-colors hover:border-stone-300">
                    <div>
                        @if ($material->imageUrl())
                            <a href="{{ route('materials.show', $material->slug) }}"
                               class="block border-b border-stone-100 bg-nalar-bg p-3">
                                <img src="{{ $material->imageUrl() }}"
                                     alt="{{ $material->image_caption ?: $material->title }}"
                                     class="h-36 w-full rounded-lg object-contain">
                            </a>
                        @endif

                        <div class="p-5 pb-0">
                            <div class="flex flex-wrap items-center justify-between gap-2 text-[11px]">
                                <span class="rounded bg-nalar-soft px-2 py-0.5 font-semibold text-nalar">
                                    {{ $material->category?->name ?? 'Materi SMA' }}
                                </span>
                                <span class="rounded bg-stone-100 px-2 py-0.5 font-medium text-stone-600">
                                    {{ $material->gradeLabel() }}
                                </span>
                            </div>

                            <h2 class="mt-3 text-base font-semibold leading-snug text-nalar-text">
                                <a href="{{ route('materials.show', $material->slug) }}" class="hover:text-nalar">
                                    {{ $material->title }}
                                </a>
                            </h2>

                            <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-stone-600">
                                {{ $material->description }}
                            </p>
                        </div>
                    </div>

                    <div class="mx-5 mt-5 mb-5 flex items-center justify-between border-t border-stone-100 pt-3.5 text-xs">
                        <span class="text-stone-500">
                            {{ $material->estimated_minutes }} menit baca
                        </span>
                        <a href="{{ route('materials.show', $material->slug) }}"
                           class="font-semibold text-nalar hover:underline">
                            Baca Materi &rarr;
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <x-admin.pagination :paginator="$materials" class="mt-5 rounded-xl border border-stone-200 bg-white" />
    @endif
</x-layouts.app>
