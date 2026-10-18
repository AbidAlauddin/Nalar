@php
    $user = auth()->user();
    $isTeacher = $user?->isTeacher() ?? false;
    $dashboardHref = $isTeacher && Route::has('teacher.dashboard')
        ? route('teacher.dashboard')
        : route('dashboard');
    $isDashboardActive = request()->routeIs('dashboard', 'admin.dashboard', 'teacher.dashboard');
    // Ganti 'profile.edit' dengan nama route profil yang ada. Jika belum ada, item tampil nonaktif.
    $profileUrl = Route::has('profile.edit') ? route('profile.edit') : null;
@endphp

<div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-stone-900/40 lg:hidden"></div>

<aside id="admin-sidebar" aria-label="{{ $isTeacher ? 'Navigasi guru' : 'Navigasi admin' }}"
       class="fixed inset-y-0 left-0 z-40 flex w-[240px] -translate-x-full flex-col border-r border-stone-200/80 bg-white transition-transform duration-150 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">

    <div class="flex h-16 shrink-0 items-center justify-between border-b border-stone-200/80 px-5">
        <a href="{{ $dashboardHref }}" class="flex items-center gap-2.5">
            <svg class="size-7 shrink-0" viewBox="0 0 32 32" aria-hidden="true">
                <rect width="32" height="32" rx="7" fill="#EA580C"/>
                <path d="M9 24V8l14 16V8" fill="none" stroke="#FFFCF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="23" cy="8" r="2.2" fill="#FB923C"/>
            </svg>
            <div class="flex items-baseline gap-2">
                <span class="text-base font-semibold tracking-tight text-nalar-text">Nalar</span>
                <span class="rounded bg-nalar-soft px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-nalar">
                    {{ $isTeacher ? 'GURU' : 'SUPER ADMIN' }}
                </span>
            </div>
        </a>
        <button id="sidebar-close" type="button" class="rounded-lg p-1.5 text-stone-500 hover:bg-stone-100 lg:hidden" aria-label="Tutup menu">
            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
        </button>
    </div>

    @if ($isTeacher)
        <nav class="flex-1 space-y-6 overflow-y-auto px-3.5 py-5">
            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Menu Utama</p>
                <x-admin.nav-item :href="$dashboardHref" :active="$isDashboardActive" icon="home">Dashboard</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Pembelajaran</p>
                <x-admin.nav-item :href="Route::has('teacher.materials.index') ? route('teacher.materials.index') : null" :active="request()->routeIs('teacher.materials.*')" icon="book">Materi Pembelajaran</x-admin.nav-item>
                <x-admin.nav-item :href="Route::has('teacher.categories.index') ? route('teacher.categories.index') : null" :active="request()->routeIs('teacher.categories.*')" icon="tag">Kategori Materi</x-admin.nav-item>
                <x-admin.nav-item :href="Route::has('teacher.questions.index') ? route('teacher.questions.index') : null" :active="request()->routeIs('teacher.questions.*')" icon="help">Bank Soal</x-admin.nav-item>
                <x-admin.nav-item :href="Route::has('teacher.quizzes.index') ? route('teacher.quizzes.index') : null" :active="request()->routeIs('teacher.quizzes.*')" icon="check">Kuis</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Pendamping & Evaluasi</p>
                <x-admin.nav-item :href="Route::has('teacher.ai.index') ? route('teacher.ai.index') : null" :active="request()->routeIs('teacher.ai.*')" icon="spark">AI Pembelajaran</x-admin.nav-item>
                <x-admin.nav-item :href="Route::has('teacher.evaluations.index') ? route('teacher.evaluations.index') : null" :active="request()->routeIs('teacher.evaluations.*')" icon="chart">Hasil dan Evaluasi</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Akun</p>
                <x-admin.nav-item :href="$profileUrl" :active="request()->routeIs('profile.*')" icon="user">Pengaturan Akun</x-admin.nav-item>
            </div>
        </nav>
    @else
        <nav class="flex-1 space-y-6 overflow-y-auto px-3.5 py-5">
            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Menu Utama</p>
                <x-admin.nav-item :href="route('dashboard')" :active="$isDashboardActive" icon="home">Dashboard</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Pembelajaran</p>
                <x-admin.nav-item :href="Route::has('admin.materials.index') ? route('admin.materials.index') : null" :active="request()->routeIs('admin.materials.*')" icon="book">Materi pembelajaran</x-admin.nav-item>
                <x-admin.nav-item :href="Route::has('admin.categories.index') ? route('admin.categories.index') : null" :active="request()->routeIs('admin.categories.*')" icon="tag">Kategori materi</x-admin.nav-item>
                <x-admin.nav-item icon="help">Bank soal</x-admin.nav-item>
                <x-admin.nav-item icon="check">Kuis</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Manajemen & AI</p>
                <x-admin.nav-item icon="users">Pengguna</x-admin.nav-item>
                <x-admin.nav-item icon="spark">Asisten AI</x-admin.nav-item>
                <x-admin.nav-item icon="chart">Penggunaan AI</x-admin.nav-item>
            </div>

            <div class="space-y-1">
                <p class="mb-2 px-3.5 text-[11px] font-semibold tracking-wider text-stone-400 uppercase">Platform</p>
                <x-admin.nav-item icon="cog">Pengaturan</x-admin.nav-item>
                <x-admin.nav-item :href="$profileUrl" icon="user">Profil saya</x-admin.nav-item>
            </div>
        </nav>
    @endif

    <div class="shrink-0 border-t border-stone-200/80 p-3.5">
        <x-admin.nav-item :href="route('home')" icon="out">Kembali ke beranda</x-admin.nav-item>
    </div>
</aside>