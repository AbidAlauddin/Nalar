@php
    use App\Models\Material;
@endphp

<x-layouts.admin title="Materi Pembelajaran SMA">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Materi Pembelajaran</h1>
                <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">SMA</span>
            </div>
            <p class="mt-1 text-xs text-stone-500">
                Susun dan kelola bahan ajar terstruktur untuk siswa Sekolah Menengah Atas (Kelas X, XI, dan XII).
            </p>
        </div>

        <a href="{{ route($routePrefix . '.materials.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
            Buat Materi Baru
        </a>
    </div>

    @if (session('status'))
        <div role="status" class="mb-5 rounded-xl border border-orange-200 bg-nalar-soft/60 px-4 py-3 text-xs font-medium text-nalar-text">
            {{ session('status') }}
        </div>
    @endif

    {{-- Pencarian & Filter (Dipisahkan Atas-Bawah, Tidak Sejajar) --}}
    <div class="mb-5 rounded-2xl border border-stone-200/80 bg-white p-4 sm:p-5">
        <form method="GET" action="{{ route($routePrefix . '.materials.index') }}" class="space-y-3.5">
            {{-- Baris 1: Pencarian Kata Kunci --}}
            <div>
                <label for="q" class="mb-1.5 block text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                    Pencarian Materi
                </label>
                <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <input id="q" name="q" type="search" value="{{ $search }}"
                               placeholder="Cari judul atau ringkasan materi pembelajaran SMA..."
                               class="w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-xs text-nalar-text placeholder:text-stone-400 focus:border-nalar focus:outline-none">
                    </div>
                    <button type="submit"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-nalar px-4 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                        Cari Materi
                    </button>
                </div>
            </div>

            {{-- Baris 2: Filter Penyaringan (Dipisahkan dari baris Search) --}}
            <div class="border-t border-stone-200/80 pt-3.5">
                <p class="mb-2 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">
                    Filter Penyaringan
                </p>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="relative">
                        <label for="category" class="sr-only">Kategori mata pelajaran</label>
                        <select id="category" name="category"
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) $selectedCategory === (string) $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>

                    <div class="relative">
                        <label for="grade" class="sr-only">Kelas SMA</label>
                        <select id="grade" name="grade"
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Semua kelas SMA</option>
                            @foreach (Material::GRADES as $key => $label)
                                <option value="{{ $key }}" @selected($selectedGrade === (string) $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>

                    <div class="relative">
                        <label for="status" class="sr-only">Status publikasi</label>
                        <select id="status" name="status"
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Semua status</option>
                            @foreach (Material::STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected($selectedStatus === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>

                    <div class="relative">
                        <label for="scope" class="sr-only">Cakupan kepemilikan</label>
                        <select id="scope" name="scope"
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Semua pengajar</option>
                            <option value="mine" @selected($selectedScope === 'mine')>Materi saya</option>
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-nalar-text transition-colors hover:border-nalar hover:text-nalar">
                            Terapkan Filter
                        </button>
                        @if ($search !== '' || $selectedCategory !== '' || $selectedGrade !== '' || $selectedStatus !== '' || $selectedScope !== '')
                            <a href="{{ route($routePrefix . '.materials.index') }}"
                               class="shrink-0 rounded-xl border border-stone-200 px-3 py-2 text-xs font-medium text-nalar hover:bg-nalar-soft/50">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Daftar Materi --}}
    <div class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white">
        @if ($materials->isEmpty())
            <div class="p-6">
                <x-admin.empty-state
                    title="Belum ada materi pembelajaran SMA"
                    description="Materi yang disusun untuk siswa SMA akan ditampilkan di sini. Gunakan tombol Buat Materi Baru untuk menambahkan bahan ajar." />
            </div>
        @else
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-stone-200/80 bg-nalar-bg text-[11px] font-semibold tracking-wider text-stone-500 uppercase">
                        <tr>
                            <th class="px-5 py-3.5">Judul & Ringkasan Materi</th>
                            <th class="px-5 py-3.5">Mata Pelajaran</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Penyusun</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200/80">
                        @foreach ($materials as $material)
                            @php
                                $canModify = $user->isSuperAdmin() || ((int) $material->user_id === (int) $user->id);
                            @endphp
                            <tr class="hover:bg-stone-50/70">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3.5">
                                        @if ($material->imageUrl())
                                            <a href="{{ route($routePrefix . '.materials.show', $material) }}" class="shrink-0">
                                                <img src="{{ $material->imageUrl() }}"
                                                     alt="{{ $material->title }}"
                                                     class="h-11 w-16 rounded-lg border border-stone-200 bg-nalar-bg object-contain p-0.5">
                                            </a>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route($routePrefix . '.materials.show', $material) }}"
                                               class="text-[13px] font-semibold text-nalar-text hover:text-nalar">
                                                {{ $material->title }}
                                            </a>
                                            <p class="mt-0.5 line-clamp-1 max-w-lg text-xs text-stone-500">
                                                {{ $material->description }}
                                            </p>
                                            <p class="mt-1 text-[11px] text-stone-400">
                                                Estimasi {{ $material->estimated_minutes }} menit baca
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-medium text-nalar-text">{{ $material->category?->name ?? 'Tanpa kategori' }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="rounded bg-stone-100 px-2 py-0.5 text-[11px] font-medium text-stone-700">
                                        {{ $material->gradeLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-stone-600">
                                    {{ $material->author?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-block rounded px-2 py-0.5 text-[11px] font-medium {{ $material->isPublished() ? 'bg-nalar-soft text-nalar' : 'bg-stone-100 text-stone-500' }}">
                                        {{ $material->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route($routePrefix . '.materials.show', $material) }}"
                                           class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                                            Detail
                                        </a>
                                        @if ($canModify)
                                            <a href="{{ route($routePrefix . '.materials.edit', $material) }}"
                                               class="rounded-lg border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-nalar-text hover:bg-stone-50">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route($routePrefix . '.materials.destroy', $material) }}"
                                                  onsubmit="return confirm('Hapus materi {{ addslashes($material->title) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-admin.pagination :paginator="$materials" />
        @endif
    </div>
</x-layouts.admin>
