@props(['title' => 'Dashboard'])
@php
    $user = auth()->user();
    $isTeacher = $user->isTeacher();
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $profileUrl = Route::has('profile.edit') ? route('profile.edit') : null;
    $materialsUrl = Route::has('materials.index') ? route('materials.index') : null;
    $quizzesUrl = Route::has('quizzes.index') ? route('quizzes.index') : null;
    $aiUrl = Route::has('ai.index') ? route('ai.index') : null;
    $progressUrl = Route::has('progress.index') ? route('progress.index') : null;
    $dashboardRoute = $isTeacher && Route::has('teacher.dashboard') ? route('teacher.dashboard') : route('dashboard');
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Nalar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-nalar-bg text-sm text-nalar-text antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[232px_minmax(0,1fr)]">
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-stone-900/40 lg:hidden"></div>

        <aside id="user-sidebar" aria-label="Navigasi pembelajaran"
               class="fixed inset-y-0 left-0 z-40 flex w-[232px] -translate-x-full flex-col border-r border-stone-200 bg-white transition-transform duration-150 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">

            <div class="flex h-14 shrink-0 items-center justify-between border-b border-stone-200 px-4">
                <a href="{{ $dashboardRoute }}" class="flex items-baseline gap-2">
                    <span class="text-base font-semibold">Nalar</span>
                    <span class="text-[10px] font-medium tracking-wide text-nalar">{{ $isTeacher ? 'PENGAJAR' : 'BELAJAR' }}</span>
                </a>
                <button id="sidebar-close" type="button" class="rounded p-1 text-stone-500 hover:bg-stone-100 lg:hidden" aria-label="Tutup menu">
                    <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-4">
                <div>
                    <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Ringkasan</p>
                    <x-admin.nav-item :href="$dashboardRoute" :active="request()->routeIs('dashboard', 'teacher.dashboard')" icon="home">Dashboard</x-admin.nav-item>
                </div>

                <div>
                    <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Pembelajaran</p>
                    <x-admin.nav-item :href="$materialsUrl" :active="request()->routeIs('materials.*')" icon="book">Materi pembelajaran</x-admin.nav-item>
                    <x-admin.nav-item :href="$quizzesUrl" :active="request()->routeIs('quizzes.*')" icon="check">Kuis</x-admin.nav-item>
                    <x-admin.nav-item :href="$aiUrl" :active="request()->routeIs('ai.*')" icon="spark">Bantuan AI</x-admin.nav-item>
                    @if (! $isTeacher)
                        <x-admin.nav-item :href="$progressUrl" :active="request()->routeIs('progress.*')" icon="chart">Progres belajar</x-admin.nav-item>
                    @endif
                </div>

                <div>
                    <p class="mb-1 px-3 text-[11px] font-medium text-stone-400">Akun</p>
                    <x-admin.nav-item :href="$profileUrl" :active="request()->routeIs('profile.*')" icon="user">Profil saya</x-admin.nav-item>
                </div>
            </nav>

            <div class="shrink-0 border-t border-stone-200 p-3">
                <x-admin.nav-item :href="route('home')" icon="out">Kembali ke beranda</x-admin.nav-item>
            </div>
        </aside>

        <div class="flex min-w-0 flex-col">
            <header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-stone-200 bg-nalar-bg/95 px-5 sm:px-7 lg:px-9">
                <div class="flex items-center gap-3">
                    <button id="sidebar-toggle" type="button" aria-label="Buka menu" aria-controls="user-sidebar" aria-expanded="false"
                            class="-ml-2 rounded p-2 text-stone-600 hover:bg-stone-100 lg:hidden">
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 5h14M3 10h14M3 15h14"/></svg>
                    </button>
                    <p class="text-[13px] text-stone-500">{{ $isTeacher ? 'Ruang Pengajar' : 'Ruang Belajar' }} <span class="mx-1 text-stone-300">/</span> <span class="font-medium text-nalar-text">{{ $title }}</span></p>
                </div>

                <div class="relative">
                    <button id="profile-button" type="button" aria-haspopup="true" aria-expanded="false"
                            class="flex items-center gap-3 rounded-md p-1 pr-2 hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-nalar">
                        <span class="grid size-8 place-items-center rounded-full bg-nalar-soft text-xs font-semibold text-nalar">{{ $initials }}</span>
                        <span class="hidden text-left leading-tight sm:block">
                            <span class="block text-[13px] font-medium">{{ $user->name }}</span>
                            <span class="block text-xs text-stone-500">{{ $isTeacher ? 'Guru' : 'Siswa' }}</span>
                        </span>
                    </button>

                    <div id="profile-menu" class="absolute right-0 mt-1 hidden w-52 rounded-md border border-stone-200 bg-white py-1 shadow-sm">
                        @if ($profileUrl)
                            <a href="{{ $profileUrl }}" class="block px-3 py-2 text-[13px] hover:bg-stone-50">Profil saya</a>
                        @endif
                        <a href="{{ route('home') }}" class="block px-3 py-2 text-[13px] hover:bg-stone-50">Kembali ke beranda</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100">
                            @csrf
                            <button type="submit" class="block w-full px-3 py-2 text-left text-[13px] hover:bg-stone-50">Keluar</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-5 py-6 sm:px-7 lg:px-9 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('user-sidebar');
            var backdrop = document.getElementById('sidebar-backdrop');
            var toggle = document.getElementById('sidebar-toggle');

            function setSidebar(open) {
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
            }
            toggle.addEventListener('click', function () { setSidebar(true); });
            backdrop.addEventListener('click', function () { setSidebar(false); });
            document.getElementById('sidebar-close').addEventListener('click', function () { setSidebar(false); });

            var menuBtn = document.getElementById('profile-button');
            var menu = document.getElementById('profile-menu');
            function setMenu(open) {
                menu.classList.toggle('hidden', !open);
                menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
            menuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                setMenu(menu.classList.contains('hidden'));
            });
            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) setMenu(false);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { setSidebar(false); setMenu(false); }
            });
        })();
    </script>
</body>
</html>
