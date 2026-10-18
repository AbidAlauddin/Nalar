@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

    $modules = [
        [
            'name' => 'Manajemen Pengguna',
            'scope' => 'Autentikasi & akun',
            'active' => $stats['users'] !== null,
            'summary' => number_format($stats['users'] ?? 0, 0, ',', '.') . ' pengguna terdaftar',
        ],
        [
            'name' => 'Materi Pembelajaran',
            'scope' => 'Konten belajar SD/SMP/SMA',
            'active' => $stats['materials'] !== null,
            'summary' => $stats['materials'] !== null
                ? number_format($stats['materials'], 0, ',', '.') . ' materi tersedia'
                : 'Tabel materi belum dibuat',
        ],
        [
            'name' => 'Kategori Materi',
            'scope' => 'Pengelompokan topik',
            'active' => $categories !== null,
            'summary' => $categories !== null
                ? number_format($categories, 0, ',', '.') . ' kategori terdaftar'
                : 'Tabel kategori belum dibuat',
        ],
        [
            'name' => 'Kuis & Evaluasi',
            'scope' => 'Bank soal & pengerjaan',
            'active' => $stats['quizzes'] !== null,
            'summary' => $stats['quizzes'] !== null
                ? number_format($stats['quizzes'], 0, ',', '.') . ' kuis tersedia'
                : 'Tabel kuis belum dibuat',
        ],
    ];

    $totalModules = count($modules);
    $activeModules = collect($modules)->where('active', true)->count();
    $readinessPercent = (int) round(($activeModules / $totalModules) * 100);
@endphp

<x-layouts.admin title="Dashboard">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-wrap items-baseline justify-between gap-2">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Dashboard</h1>
            <p class="mt-1 text-xs text-stone-500">
                {{ now()->locale('id')->translatedFormat('l, j F Y') }} &middot; Pantau dan kelola aktivitas pembelajaran Nalar.
            </p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_310px] xl:items-start">
        {{-- Kolom Utama --}}
        <div class="min-w-0 space-y-6">
            {{-- Baris Banner Sambutan & Ringkasan Kesiapan Modul --}}
            <div class="grid gap-5 lg:grid-cols-12">
                {{-- Banner Sambutan Admin (Solid Primary #EA580C, Tanpa Gradient) --}}
                <section aria-label="Sambutan administrator"
                         class="relative flex flex-col justify-between overflow-hidden rounded-2xl bg-nalar p-6 text-[#FFFCF8] sm:flex-row sm:items-center sm:p-7 lg:col-span-8">
                    <div class="relative z-10 max-w-md">
                        <span class="inline-block rounded-md bg-white/15 px-2.5 py-1 text-[11px] font-semibold tracking-wider uppercase">
                            Panel Administrasi
                        </span>
                        <h2 class="mt-3 text-xl font-semibold tracking-tight sm:text-2xl">
                            Halo, {{ $user->name }}!
                        </h2>
                        <p class="mt-2 text-xs leading-relaxed text-nalar-soft sm:text-[13px]">
                            Kelola pengguna terdaftar, pantau kesiapan modul materi pembelajaran, serta awasi evaluasi kuis dalam satu ruang kerja terpusat.
                        </p>
                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <a href="{{ route('home') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-[#FFFCF8] px-4 py-2.5 text-xs font-semibold text-nalar transition-colors hover:bg-nalar-soft">
                                Lihat Halaman Publik
                            </a>
                            <span class="text-xs font-medium text-nalar-soft">
                                {{ number_format($stats['users'], 0, ',', '.') }} pengguna aktif di database
                            </span>
                        </div>
                    </div>

                    {{-- Ilustrasi Vektor Minimalis Ruang Kerja Admin --}}
                    <div class="hidden shrink-0 sm:block sm:pl-4" aria-hidden="true">
                        <svg class="h-32 w-36" viewBox="0 0 160 136" fill="none">
                            <rect x="14" y="16" width="132" height="94" rx="10" fill="#FFFCF8" stroke="#292524" stroke-width="2"/>
                            <rect x="14" y="16" width="132" height="22" rx="10" fill="#FFEDD5" stroke="#292524" stroke-width="2"/>
                            <circle cx="26" cy="27" r="3" fill="#EA580C"/>
                            <circle cx="36" cy="27" r="3" fill="#FB923C"/>
                            <rect x="26" y="50" width="46" height="26" rx="5" fill="#FFEDD5"/>
                            <rect x="32" y="57" width="22" height="5" rx="2.5" fill="#EA580C"/>
                            <rect x="32" y="66" width="32" height="4" rx="2" fill="#292524" fill-opacity="0.35"/>
                            <rect x="80" y="50" width="54" height="11" rx="4" fill="#FFEDD5"/>
                            <rect x="80" y="65" width="54" height="11" rx="4" fill="#FFEDD5"/>
                            <rect x="26" y="84" width="108" height="14" rx="4" fill="#292524" fill-opacity="0.08"/>
                            <rect x="32" y="89" width="48" height="4" rx="2" fill="#2563EB"/>
                            <rect x="48" y="110" width="64" height="8" rx="4" fill="#FFEDD5" stroke="#292524" stroke-width="2"/>
                        </svg>
                    </div>
                </section>

                {{-- Panel Kesiapan Modul Platform (Data Asli Skema Database) --}}
                <section aria-labelledby="h-readiness"
                         class="flex flex-col justify-between rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6 lg:col-span-4">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h2 id="h-readiness" class="text-sm font-semibold text-nalar-text">Kesiapan Modul</h2>
                            <span class="rounded-md bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">
                                {{ $readinessPercent }}%
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-stone-500">Status tabel inti LMS di database</p>
                    </div>

                    <div class="my-5">
                        <div class="flex items-baseline justify-between">
                            <span class="text-3xl font-semibold tracking-tight tabular-nums text-nalar-text">
                                {{ $activeModules }}<span class="text-lg font-normal text-stone-400">/{{ $totalModules }}</span>
                            </span>
                            <span class="text-xs font-medium text-stone-500">Modul aktif</span>
                        </div>
                        <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-nalar-soft" role="progressbar"
                             aria-valuenow="{{ $readinessPercent }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="Persentase kesiapan modul">
                            <div class="h-full rounded-full bg-nalar transition-all" style="width: {{ $readinessPercent }}%"></div>
                        </div>
                    </div>

                    <p class="text-xs leading-relaxed text-stone-500">
                        @if ($activeModules < $totalModules)
                            {{ $totalModules - $activeModules }} modul pembelajaran menunggu pembuatan skema database.
                        @else
                            Seluruh modul inti telah tersedia di database.
                        @endif
                    </p>
                </section>
            </div>

            {{-- Baris Statistik Utama --}}
            <section aria-label="Ringkasan statistik" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-admin.stat-card label="Total pengguna" :value="$stats['users']" icon="users" hint="Akun terdaftar" />
                <x-admin.stat-card label="Total materi" :value="$stats['materials']" icon="book" hint="Materi pembelajaran" />
                <x-admin.stat-card label="Kategori materi" :value="$categories" icon="tag" hint="Topik pembelajaran" />
                <x-admin.stat-card label="Aktivitas belajar" :value="$stats['attempts']" icon="check" hint="Total pengerjaan kuis" />
            </section>

            {{-- Baris Ringkasan Materi & Evaluasi Kuis --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <section aria-labelledby="h-materials" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                        <div>
                            <h2 id="h-materials" class="text-base font-semibold text-nalar-text">Ringkasan materi</h2>
                            <p class="mt-0.5 text-xs text-stone-500">Ketersediaan bahan ajar dan kategori topik</p>
                        </div>
                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ $stats['materials'] === null ? 'bg-stone-100 text-stone-500' : 'bg-nalar-soft text-nalar' }}">
                            {{ $stats['materials'] === null ? 'Belum tersedia' : 'Aktif' }}
                        </span>
                    </div>

                    @if ($stats['materials'] === null)
                        <x-admin.empty-state
                            title="Modul materi belum tersedia"
                            description="Ringkasan akan muncul setelah modul pembelajaran dibuat." />
                    @elseif ($stats['materials'] === 0)
                        <x-admin.empty-state
                            title="Belum ada materi"
                            description="Tabel materi telah siap, namun belum ada materi pembelajaran yang ditambahkan." />
                    @else
                        <dl class="mt-2 divide-y divide-stone-200/80 text-[13px]">
                            <div class="flex justify-between py-3">
                                <dt class="text-stone-500">Total materi</dt>
                                <dd class="font-semibold tabular-nums text-nalar-text">{{ number_format($stats['materials'], 0, ',', '.') }}</dd>
                            </div>
                            @if ($categories !== null)
                                <div class="flex justify-between py-3">
                                    <dt class="text-stone-500">Total kategori</dt>
                                    <dd class="font-semibold tabular-nums text-nalar-text">{{ number_format($categories, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                        </dl>
                    @endif
                </section>

                <section aria-labelledby="h-quiz" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                        <div>
                            <h2 id="h-quiz" class="text-base font-semibold text-nalar-text">Aktivitas kuis</h2>
                            <p class="mt-0.5 text-xs text-stone-500">Evaluasi pemahaman dan pengerjaan soal</p>
                        </div>
                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ $stats['quizzes'] === null ? 'bg-stone-100 text-stone-500' : 'bg-nalar-soft text-nalar' }}">
                            {{ $stats['quizzes'] === null ? 'Belum tersedia' : 'Aktif' }}
                        </span>
                    </div>

                    @if ($stats['quizzes'] === null)
                        <x-admin.empty-state
                            title="Modul kuis belum tersedia"
                            description="Statistik kuis akan muncul setelah modul kuis dibuat." />
                    @elseif ($stats['quizzes'] === 0)
                        <x-admin.empty-state
                            title="Belum ada kuis"
                            description="Tabel kuis telah siap, namun belum ada paket kuis yang dipublikasikan." />
                    @else
                        <dl class="mt-2 divide-y divide-stone-200/80 text-[13px]">
                            <div class="flex justify-between py-3">
                                <dt class="text-stone-500">Total kuis</dt>
                                <dd class="font-semibold tabular-nums text-nalar-text">{{ number_format($stats['quizzes'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between py-3">
                                <dt class="text-stone-500">Total pengerjaan</dt>
                                <dd class="font-semibold tabular-nums text-nalar-text">
                                    {{ $stats['attempts'] !== null ? number_format($stats['attempts'], 0, ',', '.') : 'Belum dilacak' }}
                                </dd>
                            </div>
                        </dl>
                    @endif
                </section>
            </div>

            {{-- Bagian Aktivitas Terbaru (Data Asli Pendaftaran & Konten) --}}
            <section aria-labelledby="h-activity" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                    <div>
                        <h2 id="h-activity" class="text-base font-semibold text-nalar-text">Aktivitas terbaru</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Riwayat pendaftaran pengguna, pembuatan materi, dan kuis di sistem</p>
                    </div>
                    <span class="rounded-md bg-stone-100 px-2.5 py-1 text-xs font-medium tabular-nums text-stone-600">
                        {{ $activities->count() }} catatan
                    </span>
                </div>

                @if ($activities->isEmpty())
                    <x-admin.empty-state
                        title="Belum ada aktivitas"
                        description="Pengguna baru, materi, dan kuis yang dibuat akan muncul di sini." />
                @else
                    <ul class="divide-y divide-stone-200/80">
                        @foreach ($activities as $event)
                            <li class="flex items-center justify-between gap-4 py-3.5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-nalar-soft text-nalar">
                                        <x-admin.icon name="user" class="size-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-[13px] font-medium text-nalar-text">{{ $event['text'] }}</p>
                                        <p class="text-[11px] text-stone-400">{{ $event['at']->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <time datetime="{{ $event['at']->toIso8601String() }}"
                                      class="shrink-0 rounded-md bg-stone-100 px-2 py-1 text-xs text-stone-500">
                                    {{ $event['at']->diffForHumans() }}
                                </time>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        {{-- Panel Sekunder Kanan (Profil Admin & Status Modul Platform) --}}
        <aside aria-label="Informasi administrator dan status modul" class="space-y-5">
            {{-- Kartu Profil Administrator --}}
            <section aria-label="Profil administrator" class="rounded-2xl border border-stone-200/80 bg-white p-5">
                <div class="flex items-center gap-3.5">
                    <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-nalar-soft text-sm font-semibold text-nalar">
                        {{ $initials }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-nalar-text">{{ $user->name }}</p>
                        <p class="truncate text-xs text-stone-500">{{ $user->email }}</p>
                        <span class="mt-1 inline-block rounded bg-nalar-soft px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-nalar">
                            SUPER ADMIN
                        </span>
                    </div>
                </div>

                <dl class="mt-4 divide-y divide-stone-200/80 border-t border-stone-200/80 pt-1 text-xs">
                    <div class="flex justify-between py-2.5">
                        <dt class="text-stone-500">Peran akses</dt>
                        <dd class="font-medium text-nalar-text">Administrator Utama</dd>
                    </div>
                    @if ($user->created_at)
                        <div class="flex justify-between py-2.5">
                            <dt class="text-stone-500">Terdaftar sejak</dt>
                            <dd class="font-medium tabular-nums text-nalar-text">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            {{-- Daftar Status Modul Platform (Terinspirasi dari susunan kartu kanan pada referensi) --}}
            <section aria-labelledby="h-module-status" class="rounded-2xl border border-stone-200/80 bg-white p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 id="h-module-status" class="text-sm font-semibold text-nalar-text">Status Modul LMS</h2>
                    <span class="text-xs font-medium text-stone-400">{{ $activeModules }}/{{ $totalModules }} aktif</span>
                </div>
                <p class="mt-1 text-xs text-stone-500">Kondisi aktual modul berdasarkan skema database</p>

                <ul class="mt-4 space-y-3">
                    @foreach ($modules as $mod)
                        <li class="rounded-xl border border-stone-200/70 bg-nalar-bg p-3.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-semibold {{ $mod['active'] ? 'bg-nalar-soft text-nalar' : 'bg-stone-200/70 text-stone-600' }}">
                                    {{ $mod['active'] ? 'Aktif' : 'Belum tersedia' }}
                                </span>
                                <span class="text-[11px] text-stone-400">{{ $mod['scope'] }}</span>
                            </div>
                            <p class="mt-2 text-[13px] font-semibold text-nalar-text">{{ $mod['name'] }}</p>
                            <p class="mt-0.5 text-xs text-stone-500">{{ $mod['summary'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </div>
</x-layouts.admin>