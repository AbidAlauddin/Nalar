@php
    // Ganti 'profile.edit' dengan nama route profil yang ada. Jika belum ada, item tampil nonaktif.
    $profileUrl = Route::has('profile.edit') ? route('profile.edit') : null;
@endphp

<div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-stone-900/40 lg:hidden"></div>

<aside id="admin-sidebar" aria-label="Navigasi admin"
       class="fixed inset-y-0 left-0 z-40 flex w-[232px] -translate-x-full flex-col border-r border-stone-200 bg-white transition-transform duration-150 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">

    <div class="flex h-14 shrink-0 items-center justify-between border-b border-stone-200 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-baseline gap-2">
            {{-- Ganti dengan logo Nalar yang sudah ada jika tersedia --}}
            <span class="text-base font-semibold">Nalar</span>
            <span class="text-[10px] font-medium tracking-wide text-nalar">SUPER ADMIN</span>
        </a>
        <button id="sidebar-close" type="button" class="rounded p-1 text-stone-500 hover:bg-stone-100 lg:hidden" aria-label="Tutup menu">
            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
        </button>
    </div>

    <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
        <div>
            <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Ringkasan</p>
            <x-admin.nav-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-admin.nav-item>
        </div>

        <div>
            <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Manajemen pembelajaran</p>
            <x-admin.nav-item>Materi pembelajaran</x-admin.nav-item>
            <x-admin.nav-item>Kategori materi</x-admin.nav-item>
            <x-admin.nav-item>Bank soal</x-admin.nav-item>
            <x-admin.nav-item>Kuis</x-admin.nav-item>
        </div>

        <div>
            <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Manajemen pengguna</p>
            <x-admin.nav-item>Pengguna</x-admin.nav-item>
        </div>

        <div>
            <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Manajemen AI</p>
            <x-admin.nav-item>Asisten AI</x-admin.nav-item>
            <x-admin.nav-item>Penggunaan AI</x-admin.nav-item>
        </div>

        <div>
            <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Platform</p>
            <x-admin.nav-item>Pengaturan</x-admin.nav-item>
            <x-admin.nav-item :href="$profileUrl">Profil saya</x-admin.nav-item>
        </div>
    </nav>

    <div class="shrink-0 border-t border-stone-200 p-3">
        <x-admin.nav-item :href="route('home')">Kembali ke beranda</x-admin.nav-item>
    </div>
</aside>