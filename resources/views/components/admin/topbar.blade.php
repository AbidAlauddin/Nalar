@props(['title' => 'Dashboard'])
@php
    $user = auth()->user();
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $profileUrl = Route::has('profile.edit') ? route('profile.edit') : null;
@endphp

<header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-stone-200 bg-nalar-bg/95 px-5 sm:px-7 lg:px-9">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button" aria-label="Buka menu" aria-controls="admin-sidebar" aria-expanded="false"
                class="-ml-2 rounded p-2 text-stone-600 hover:bg-stone-100 lg:hidden">
            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 5h14M3 10h14M3 15h14"/></svg>
        </button>
        <p class="text-[13px] text-stone-500">Admin <span class="mx-1 text-stone-300">/</span> <span class="font-medium text-nalar-text">{{ $title }}</span></p>
    </div>

    <div class="relative">
        <button id="profile-button" type="button" aria-haspopup="true" aria-expanded="false"
                class="flex items-center gap-3 rounded-md p-1 pr-2 hover:bg-stone-100 focus-visible:outline-2 focus-visible:outline-nalar">
            <span class="grid size-8 place-items-center rounded-full bg-nalar-soft text-xs font-semibold text-nalar">{{ $initials }}</span>
            <span class="hidden text-left leading-tight sm:block">
                <span class="block text-[13px] font-medium">{{ $user->name }}</span>
                <span class="block text-xs text-stone-500">Super Admin</span>
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