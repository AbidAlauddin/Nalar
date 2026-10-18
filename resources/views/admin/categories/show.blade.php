<x-layouts.admin :title="'Kategori: ' . $category->name">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">
                    {{ $category->education_level }}
                </span>
                <span class="rounded px-2 py-0.5 text-[11px] font-medium {{ $category->is_active ? 'bg-nalar-soft text-nalar' : 'bg-stone-200 text-stone-600' }}">
                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="rounded bg-stone-100 px-2 py-0.5 text-[11px] font-medium text-stone-600">
                    Slug: {{ $category->slug }}
                </span>
            </div>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-nalar-text">{{ $category->name }}</h1>
            <p class="mt-1 text-xs text-stone-500">
                Ditambahkan oleh <strong>{{ $category->creatorName() }}</strong>
                &middot; {{ $category->materials_count }} materi terdaftar ({{ $category->published_materials_count }} dipublikasikan)
                @if ($category->updated_at)
                    &middot; Diperbarui {{ $category->updated_at->format('d M Y, H:i') }}
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($routePrefix . '.categories.index') }}"
               class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-medium text-stone-600 hover:bg-stone-50">
                Kembali ke Daftar Kategori
            </a>
            <a href="{{ route($routePrefix . '.materials.create', ['category' => $category->id]) }}"
               class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-semibold text-nalar hover:bg-nalar-soft/50">
                + Buat Materi di Kategori Ini
            </a>
            @if ($isManageable)
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="rounded-xl bg-nalar px-4 py-2 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                    Edit Kategori
                </a>
            @endif
        </div>
    </div>

    {{-- Ringkasan Deskripsi Kategori --}}
    <section aria-label="Deskripsi kategori" class="mb-5 rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
        <h2 class="text-xs font-semibold tracking-wider text-stone-400 uppercase">Deskripsi Mata Pelajaran / Kategori</h2>
        <p class="mt-2 text-sm leading-relaxed text-stone-700">
            {{ $category->description ?: 'Belum ada deskripsi khusus untuk kategori mata pelajaran ini.' }}
        </p>
    </section>

    {{-- Tabel Materi pada Kategori Ini --}}
    <section aria-labelledby="h-category-materials" class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-200/80 px-5 py-4">
            <div>
                <h2 id="h-category-materials" class="text-base font-semibold text-nalar-text">
                    Daftar Materi pada Kategori {{ $category->name }}
                </h2>
                <p class="mt-0.5 text-xs text-stone-500">
                    Bahan ajar SMA yang terhubung dengan kategori ini
                </p>
            </div>
            <span class="rounded-md bg-stone-100 px-2.5 py-1 text-xs font-medium tabular-nums text-stone-600">
                {{ $category->materials_count }} materi
            </span>
        </div>

        @if ($materials->isEmpty())
            <div class="p-6">
                <x-admin.empty-state
                    title="Belum ada materi pada kategori ini"
                    description="Gunakan tombol Buat Materi di Kategori Ini untuk menambahkan bahan ajar SMA pertama pada mata pelajaran ini." />
            </div>
        @else
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-stone-200/80 bg-nalar-bg text-[11px] font-semibold tracking-wider text-stone-500 uppercase">
                        <tr>
                            <th class="px-5 py-3.5">Judul & Ringkasan Materi</th>
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
    </section>
</x-layouts.admin>
