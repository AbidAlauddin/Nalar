<x-layouts.admin title="Kategori Materi SMA">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Kategori Materi</h1>
                <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">SMA</span>
            </div>
            <p class="mt-1 text-xs text-stone-500">
                @if ($isManageable)
                    Kelola pengelompokan mata pelajaran untuk materi pembelajaran jenjang Sekolah Menengah Atas (SMA).
                @else
                    Daftar kategori mata pelajaran SMA yang telah disiapkan oleh Super Admin untuk penyusunan materi pembelajaran.
                @endif
            </p>
        </div>

        @if ($isManageable)
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                Tambah Kategori SMA
            </a>
        @else
            <a href="{{ route('teacher.materials.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                Buat Materi Baru
            </a>
        @endif
    </div>

    @if (session('status'))
        <div role="status" class="mb-5 rounded-xl border border-orange-200 bg-nalar-soft/60 px-4 py-3 text-xs font-medium text-nalar-text">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter & Pencarian --}}
    <div class="mb-5 rounded-2xl border border-stone-200/80 bg-white p-4 sm:p-5">
        <form method="GET" action="{{ route($routePrefix . '.categories.index') }}" class="space-y-3.5">
            {{-- Baris 1: Pencarian --}}
            <div>
                <label for="q" class="mb-1.5 block text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                    Pencarian Kategori
                </label>
                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <input id="q" name="q" type="search" value="{{ $search }}"
                               placeholder="Cari mata pelajaran atau deskripsi kategori SMA..."
                               class="w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-xs text-nalar-text placeholder:text-stone-400 focus:border-nalar focus:outline-none">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                            Cari Kategori
                        </button>
                        @if (! $isManageable && $search !== '')
                            <a href="{{ route($routePrefix . '.categories.index') }}"
                               class="rounded-xl border border-stone-200 px-3.5 py-2.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Baris 2: Filter Penyaringan (Dipisahkan dari baris Search) --}}
            @if ($isManageable)
                <div class="border-t border-stone-200/80 pt-3.5">
                    <p class="mb-2 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                        Filter Penyaringan
                    </p>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative sm:w-56">
                            <label for="status" class="sr-only">Status kategori</label>
                            <select id="status" name="status"
                                    class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                                <option value="">Semua status</option>
                                <option value="active" @selected($status === 'active')>Aktif</option>
                                <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                            </select>
                            <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                    class="rounded-xl border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                                Terapkan Filter
                            </button>
                            @if ($search !== '' || $status !== '')
                                <a href="{{ route($routePrefix . '.categories.index') }}"
                                   class="rounded-xl border border-stone-200 px-3.5 py-2 text-xs font-medium text-nalar hover:bg-nalar-soft/50">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>

    {{-- Tabel / Daftar Kategori --}}
    <div class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white">
        @if ($categories->isEmpty())
            <div class="p-6">
                <x-admin.empty-state
                    title="Belum ada kategori materi SMA"
                    description="Kategori mata pelajaran SMA yang sesuai dengan pencarian atau filter akan ditampilkan di sini." />
            </div>
        @else
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-stone-200/80 bg-nalar-bg text-[11px] font-semibold tracking-wider text-stone-500 uppercase">
                        <tr>
                            <th class="px-5 py-3.5">Mata Pelajaran / Kategori</th>
                            <th class="px-5 py-3.5">Jenjang</th>
                            <th class="px-5 py-3.5">Jumlah Materi</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200/80">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-stone-50/70">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route($routePrefix . '.categories.show', $category) }}"
                                       class="text-[13px] font-semibold text-nalar-text hover:text-nalar">
                                        {{ $category->name }}
                                    </a>
                                    @if ($category->description)
                                        <p class="mt-0.5 line-clamp-1 max-w-xl text-xs text-stone-500">{{ $category->description }}</p>
                                    @endif
                                    <p class="mt-1 text-[11px] text-stone-400">
                                        Ditambahkan oleh {{ $category->creatorName() }}
                                    </p>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">
                                        {{ $category->education_level }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap tabular-nums">
                                    <span class="font-semibold text-nalar-text">{{ $category->materials_count }}</span>
                                    <span class="text-stone-400">({{ $category->published_materials_count }} publik)</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-block rounded px-2 py-0.5 text-[11px] font-medium {{ $category->is_active ? 'bg-nalar-soft text-nalar' : 'bg-stone-100 text-stone-500' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    @if ($isManageable)
                                        <div class="inline-flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                               class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-nalar-text hover:bg-stone-50">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center justify-end gap-2">
                                            <a href="{{ route('teacher.categories.show', $category) }}"
                                               class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                                                Detail
                                            </a>
                                            <a href="{{ route('teacher.materials.create', ['category' => $category->id]) }}"
                                               class="rounded-lg bg-nalar-soft px-2.5 py-1.5 text-xs font-semibold text-nalar hover:bg-orange-100">
                                                + Materi
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-admin.pagination :paginator="$categories" />
        @endif
    </div>
</x-layouts.admin>
