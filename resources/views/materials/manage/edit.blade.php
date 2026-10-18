@php
    use App\Models\Material;
@endphp

<x-layouts.admin title="Edit Materi Pembelajaran SMA">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Edit Materi Pembelajaran SMA</h1>
            <p class="mt-1 text-xs text-stone-500">
                Perbarui konten, gambar ilustrasi, dan pengaturan bahan ajar <strong>{{ $material->title }}</strong>.
            </p>
        </div>
        <a href="{{ route($routePrefix . '.materials.index') }}"
           class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-medium text-stone-600 hover:bg-stone-50">
            Kembali ke Daftar Materi
        </a>
    </div>

    <div class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
        <form method="POST" action="{{ route($routePrefix . '.materials.update', $material) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-semibold text-nalar-text">Judul Materi Pembelajaran <span class="text-nalar">*</span></label>
                <input id="title" name="title" type="text" required maxlength="180"
                       value="{{ old('title', $material->title) }}"
                       class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-sm text-nalar-text focus:border-nalar focus:outline-none">
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="material_category_id" class="block text-xs font-semibold text-nalar-text">Kategori Mata Pelajaran <span class="text-nalar">*</span></label>
                    <div class="relative mt-1.5">
                        <select id="material_category_id" name="material_category_id" required
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2.5 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            <option value="">Pilih mata pelajaran SMA</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) old('material_category_id', $material->material_category_id) === (string) $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>
                    @error('material_category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="grade_level" class="block text-xs font-semibold text-nalar-text">Kelas SMA <span class="text-nalar">*</span></label>
                    <div class="relative mt-1.5">
                        <select id="grade_level" name="grade_level" required
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2.5 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            @foreach (Material::GRADES as $key => $label)
                                <option value="{{ $key }}" @selected((string) old('grade_level', $material->grade_level) === (string) $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>
                    @error('grade_level')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estimated_minutes" class="block text-xs font-semibold text-nalar-text">Estimasi Waktu Baca (Menit) <span class="text-nalar">*</span></label>
                    <input id="estimated_minutes" name="estimated_minutes" type="number" min="5" max="240" required
                           value="{{ old('estimated_minutes', $material->estimated_minutes) }}"
                           class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                    @error('estimated_minutes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-nalar-text">Status Publikasi <span class="text-nalar">*</span></label>
                    <div class="relative mt-1.5">
                        <select id="status" name="status" required
                                class="w-full appearance-none rounded-xl border border-stone-200 bg-nalar-bg py-2.5 pr-9 pl-3.5 text-xs text-nalar-text focus:border-nalar focus:outline-none">
                            @foreach (Material::STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected(old('status', $material->status) === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-admin.icon name="chev" class="pointer-events-none absolute top-1/2 right-3 size-3.5 -translate-y-1/2 text-stone-400" />
                    </div>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-nalar-text">Ringkasan / Deskripsi Singkat <span class="text-nalar">*</span></label>
                <textarea id="description" name="description" rows="2" required maxlength="1000"
                          class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-sm text-nalar-text focus:border-nalar focus:outline-none">{{ old('description', $material->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unggah / Kelola Gambar Ilustrasi Materi --}}
            <div class="rounded-xl border border-stone-200/80 bg-nalar-bg p-4">
                <div class="mb-3">
                    <h2 class="text-xs font-semibold text-nalar-text">Gambar Ilustrasi / Diagram Materi (Opsional)</h2>
                    <p class="mt-0.5 text-[11px] text-stone-500">
                        Unggah gambar baru untuk mengganti ilustrasi saat ini (JPG, PNG, WebP, atau SVG, maks. 4 MB).
                    </p>
                </div>

                @if ($material->imageUrl())
                    <div class="mb-4 flex flex-col gap-3 rounded-xl border border-stone-200 bg-white p-3.5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3.5">
                            <img src="{{ $material->imageUrl() }}"
                                 alt="{{ $material->image_caption ?: $material->title }}"
                                 class="h-16 w-28 shrink-0 rounded-lg border border-stone-200 bg-nalar-bg object-contain p-1">
                            <div>
                                <p class="text-xs font-semibold text-nalar-text">Gambar ilustrasi aktif</p>
                                <p class="mt-0.5 text-[11px] text-stone-500">
                                    {{ $material->image_caption ?: 'Belum ada keterangan khusus untuk gambar ini.' }}
                                </p>
                            </div>
                        </div>

                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-red-200 bg-red-50/60 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                            <input type="checkbox" name="remove_image" value="1" @checked(old('remove_image'))
                                   class="size-3.5 rounded border-red-300 text-red-600 focus:ring-red-500">
                            <span>Hapus gambar ini</span>
                        </label>
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label for="image" class="block text-xs font-medium text-stone-700">
                            {{ $material->imageUrl() ? 'Ganti dengan File Gambar Baru' : 'Pilih File Gambar' }}
                        </label>
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.svg,image/*"
                               class="mt-1.5 block w-full rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs text-stone-600 file:mr-3 file:rounded-lg file:border-0 file:bg-nalar-soft file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-nalar hover:file:bg-orange-200/70">
                        @error('image')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image_caption" class="block text-xs font-medium text-stone-700">Keterangan Gambar / Ilustrasi</label>
                        <input id="image_caption" name="image_caption" type="text" maxlength="255"
                               value="{{ old('image_caption', $material->image_caption) }}"
                               placeholder="Contoh: Diagram gaya yang bekerja pada bidang datar..."
                               class="mt-1.5 w-full rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs text-nalar-text placeholder:text-stone-400 focus:border-nalar focus:outline-none">
                        @error('image_caption')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold text-nalar-text">Isi Materi Pembelajaran Lengkap <span class="text-nalar">*</span></label>
                <p class="mt-0.5 text-[11px] text-stone-500">
                    Gunakan penomoran subbab (contoh: <code>1. Pengertian...</code>) dan poin daftar (<code>- ...</code>) agar otomatis diformat rapi pada halaman detail materi.
                </p>
                <textarea id="content" name="content" rows="14" required
                          class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-3 text-sm leading-relaxed text-nalar-text focus:border-nalar focus:outline-none">{{ old('content', $material->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-200/80 pt-4">
                <a href="{{ route($routePrefix . '.materials.index') }}"
                   class="rounded-xl border border-stone-200 px-4 py-2.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-xl bg-nalar px-5 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
