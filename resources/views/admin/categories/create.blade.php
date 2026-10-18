<x-layouts.admin title="Tambah Kategori Materi SMA">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Tambah Kategori Materi SMA</h1>
            <p class="mt-1 text-xs text-stone-500">
                Buat kategori mata pelajaran baru untuk lingkup Sekolah Menengah Atas (SMA).
            </p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
           class="rounded-xl border border-stone-200 bg-white px-3.5 py-2 text-xs font-medium text-stone-600 hover:bg-stone-50">
            Kembali ke Daftar Kategori
        </a>
    </div>

    <div class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-nalar-text">Nama Mata Pelajaran / Kategori <span class="text-nalar">*</span></label>
                <input id="name" name="name" type="text" required maxlength="120"
                       value="{{ old('name', $category->name) }}"
                       placeholder="Contoh: Matematika Peminatan"
                       class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-sm text-nalar-text focus:border-nalar focus:outline-none">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-nalar-text">Jenjang Pendidikan</label>
                <div class="mt-1.5 flex items-center gap-2 rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-600">
                    <span class="rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">SMA</span>
                    <span>Sekolah Menengah Atas (Kelas X, XI, XII)</span>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-nalar-text">Deskripsi Kategori</label>
                <textarea id="description" name="description" rows="3" maxlength="1000"
                          placeholder="Ringkasan ruang lingkup topik yang dipelajari pada kategori ini..."
                          class="mt-1.5 w-full rounded-xl border border-stone-200 bg-nalar-bg px-3.5 py-2.5 text-sm text-nalar-text focus:border-nalar focus:outline-none">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2.5">
                <input id="is_active" name="is_active" type="checkbox" value="1"
                       @checked(old('is_active', $category->is_active))
                       class="size-4 rounded border-stone-300 text-nalar focus:ring-nalar">
                <label for="is_active" class="text-xs font-medium text-nalar-text">
                    Aktifkan kategori ini agar dapat dipilih guru dan diakses siswa
                </label>
            </div>

            <div class="flex items-center justify-end gap-2.5 border-t border-stone-200/80 pt-4">
                <a href="{{ route('admin.categories.index') }}"
                   class="rounded-xl border border-stone-200 px-4 py-2.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-xl bg-nalar px-5 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-orange-700">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
