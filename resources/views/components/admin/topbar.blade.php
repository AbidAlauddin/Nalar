@props(['title' => 'Dashboard'])
@php
    $user = auth()->user();
    $isTeacher = $user?->isTeacher() ?? false;
    $roleLabel = $isTeacher ? 'Guru' : 'Super Admin';
    $sectionLabel = $isTeacher ? 'Ruang Pengajar' : 'Admin';
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $profileUrl = Route::has('profile.edit') ? route('profile.edit') : null;
@endphp

<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-stone-200/80 bg-nalar-bg/95 px-5 sm:px-7 lg:px-8">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button" aria-label="Buka menu" aria-controls="admin-sidebar" aria-expanded="false"
                class="-ml-2 rounded-lg p-2 text-stone-600 hover:bg-stone-100 lg:hidden">
            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 5h14M3 10h14M3 15h14"/></svg>
        </button>
        <div class="flex items-center gap-2 text-[13px]">
            <span class="text-stone-500">{{ $sectionLabel }}</span>
            <span class="text-stone-300">/</span>
            <span class="font-medium text-nalar-text">{{ $title }}</span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="hidden items-center gap-1.5 rounded-lg border border-stone-200/80 bg-white px-3 py-1.5 text-xs font-medium text-stone-600 transition-colors hover:border-stone-300 hover:text-nalar-text sm:inline-flex">
            Beranda publik
        </a>

        <div class="relative">
            <button id="profile-button" type="button" aria-haspopup="true" aria-expanded="false"
                    class="flex items-center gap-2.5 rounded-xl border border-stone-200/80 bg-white p-1.5 pr-3 transition-colors hover:bg-stone-50 focus-visible:outline-2 focus-visible:outline-nalar">
                <span class="grid size-8 place-items-center rounded-lg bg-nalar-soft text-xs font-semibold text-nalar">{{ $initials }}</span>
                <span class="hidden text-left leading-tight sm:block">
                    <span class="block text-xs font-semibold text-nalar-text">{{ $user->name }}</span>
                    <span class="block text-[11px] text-stone-500">{{ $roleLabel }}</span>
                </span>
                <x-admin.icon name="chev" class="hidden size-3.5 text-stone-400 sm:block" />
            </button>

            <div id="profile-menu" class="absolute right-0 mt-1.5 hidden w-56 rounded-xl border border-stone-200 bg-white py-1.5 shadow-sm">
                <div class="border-b border-stone-100 px-3.5 py-2 sm:hidden">
                    <p class="truncate text-xs font-semibold text-nalar-text">{{ $user->name }}</p>
                    <p class="truncate text-[11px] text-stone-500">{{ $user->email }}</p>
                </div>
                @if ($profileUrl)
                    <a href="{{ $profileUrl }}" class="block px-3.5 py-2 text-[13px] text-stone-700 hover:bg-stone-50">Profil saya</a>
                @endif
                <a href="{{ route('home') }}" class="block px-3.5 py-2 text-[13px] text-stone-700 hover:bg-stone-50">Kembali ke beranda</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-stone-100 pt-1">
                    @csrf
                    <button type="submit" class="block w-full px-3.5 py-2 text-left text-[13px] font-medium text-nalar hover:bg-nalar-soft/50">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>