@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

    $createMaterialUrl = Route::has('teacher.materials.create')
        ? route('teacher.materials.create')
        : (Route::has('teacher.materials.index') ? route('teacher.materials.index') : null);
    $createQuizUrl = Route::has('teacher.quizzes.create')
        ? route('teacher.quizzes.create')
        : (Route::has('teacher.quizzes.index') ? route('teacher.quizzes.index') : null);
    $questionBankUrl = Route::has('teacher.questions.index') ? route('teacher.questions.index') : null;

    $modules = [
        [
            'name' => 'Materi Pembelajaran',
            'scope' => 'Bahan ajar SD/SMP/SMA',
            'active' => ($stats['materials'] ?? null) !== null,
            'summary' => ($stats['materials'] ?? null) !== null
                ? number_format($stats['materials'], 0, ',', '.') . ' materi dibuat'
                : 'Tabel materi belum dibuat',
        ],
        [
            'name' => 'Kategori Materi',
            'scope' => 'Topik & mata pelajaran',
            'active' => ($categories ?? null) !== null,
            'summary' => ($categories ?? null) !== null
                ? number_format($categories, 0, ',', '.') . ' kategori tersedia'
                : 'Tabel kategori belum dibuat',
        ],
        [
            'name' => 'Bank Soal',
            'scope' => 'Koleksi butir soal',
            'active' => ($questions ?? null) !== null,
            'summary' => ($questions ?? null) !== null
                ? number_format($questions, 0, ',', '.') . ' soal tersimpan'
                : 'Tabel bank soal belum dibuat',
        ],
        [
            'name' => 'Kuis & Evaluasi',
            'scope' => 'Ujian & hasil siswa',
            'active' => ($stats['quizzes'] ?? null) !== null,
            'summary' => ($stats['quizzes'] ?? null) !== null
                ? number_format($stats['quizzes'], 0, ',', '.') . ' paket kuis'
                : 'Tabel kuis belum dibuat',
        ],
    ];

    $totalModules = count($modules);
    $activeModules = collect($modules)->where('active', true)->count();
    $readinessPercent = (int) round(($activeModules / $totalModules) * 100);
@endphp

<x-layouts.admin title="Dashboard Guru">
    {{-- Header Halaman --}}
    <div class="mb-6 flex flex-wrap items-baseline justify-between gap-2">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-nalar-text">Dashboard Guru</h1>
            <p class="mt-1 text-xs text-stone-500">
                {{ now()->locale('id')->translatedFormat('l, j F Y') }} &middot; Susun materi pembelajaran, kelola bank soal, dan pantau evaluasi siswa.
            </p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_310px] xl:items-start">
        {{-- Kolom Utama --}}
        <div class="min-w-0 space-y-6">
            {{-- Baris Banner Sambutan Guru & Ringkasan Kesiapan Ruang Pengajar --}}
            <div class="grid gap-5 lg:grid-cols-12">
                {{-- Banner Sambutan Guru (Solid Primary #EA580C, Tanpa Gradient) --}}
                <section aria-label="Sambutan guru"
                         class="relative flex flex-col justify-between overflow-hidden rounded-2xl bg-nalar p-6 text-[#FFFCF8] sm:flex-row sm:items-center sm:p-7 lg:col-span-8">
                    <div class="relative z-10 max-w-md">
                        <span class="inline-block rounded-md bg-white/15 px-2.5 py-1 text-[11px] font-semibold tracking-wider uppercase">
                            Ruang Kerja Guru
                        </span>
                        <h2 class="mt-3 text-xl font-semibold tracking-tight sm:text-2xl">
                            Halo, {{ $user->name }}!
                        </h2>
                        <p class="mt-2 text-xs leading-relaxed text-nalar-soft sm:text-[13px]">
                            Siapkan bahan ajar terstruktur, susun paket latihan soal di bank soal, dan tinjau hasil pengerjaan kuis siswa secara berkala.
                        </p>
                        <div class="mt-5 flex flex-wrap items-center gap-2.5">
                            @if ($createMaterialUrl)
                                <a href="{{ $createMaterialUrl }}"
                                   class="inline-flex items-center gap-2 rounded-xl bg-[#FFFCF8] px-4 py-2.5 text-xs font-semibold text-nalar transition-colors hover:bg-nalar-soft">
                                    Buat Materi
                                </a>
                            @else
                                <span aria-disabled="true"
                                      title="Rute pembuatan materi belum tersedia"
                                      class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl bg-[#FFFCF8]/90 px-4 py-2.5 text-xs font-semibold text-nalar">
                                    Buat Materi
                                </span>
                            @endif

                            @if ($createQuizUrl)
                                <a href="{{ $createQuizUrl }}"
                                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-3.5 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-white/20">
                                    Buat Kuis
                                </a>
                            @else
                                <span aria-disabled="true"
                                      title="Rute pembuatan kuis belum tersedia"
                                      class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-3.5 py-2.5 text-xs font-semibold text-nalar-soft">
                                    Buat Kuis
                                </span>
                            @endif

                            @if ($questionBankUrl)
                                <a href="{{ $questionBankUrl }}"
                                   class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-3.5 py-2.5 text-xs font-semibold text-[#FFFCF8] transition-colors hover:bg-white/20">
                                    Bank Soal
                                </a>
                            @else
                                <span aria-disabled="true"
                                      title="Rute bank soal belum tersedia"
                                      class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-3.5 py-2.5 text-xs font-semibold text-nalar-soft">
                                    Bank Soal
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Ilustrasi Vektor Minimalis Ruang Pengajar --}}
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

                {{-- Panel Kesiapan Modul Pengajaran --}}
                <section aria-labelledby="h-teacher-readiness"
                         class="flex flex-col justify-between rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6 lg:col-span-4">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h2 id="h-teacher-readiness" class="text-sm font-semibold text-nalar-text">Kesiapan Modul Ajar</h2>
                            <span class="rounded-md bg-nalar-soft px-2 py-0.5 text-[11px] font-semibold text-nalar">
                                {{ $readinessPercent }}%
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-stone-500">Ketersediaan modul pengajaran di database</p>
                    </div>

                    <div class="my-5">
                        <div class="flex items-baseline justify-between">
                            <span class="text-3xl font-semibold tracking-tight tabular-nums text-nalar-text">
                                {{ $activeModules }}<span class="text-lg font-normal text-stone-400">/{{ $totalModules }}</span>
                            </span>
                            <span class="text-xs font-medium text-stone-500">Modul siap</span>
                        </div>
                        <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-nalar-soft" role="progressbar"
                             aria-valuenow="{{ $readinessPercent }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="Persentase kesiapan modul pengajaran">
                            <div class="h-full rounded-full bg-nalar transition-all" style="width: {{ $readinessPercent }}%"></div>
                        </div>
                    </div>

                    <p class="text-xs leading-relaxed text-stone-500">
                        @if ($activeModules < $totalModules)
                            {{ $totalModules - $activeModules }} modul pengajaran menunggu implementasi tabel pada tahap berikutnya.
                        @else
                            Seluruh modul pengajaran telah aktif dan siap digunakan.
                        @endif
                    </p>
                </section>
            </div>

            {{-- Baris Statistik Aktivitas Mengajar --}}
            <section aria-label="Ringkasan aktivitas mengajar" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-admin.stat-card label="Total materi" :value="$stats['materials'] ?? null" icon="book" hint="Materi dibuat guru" />
                <x-admin.stat-card label="Total kuis" :value="$stats['quizzes'] ?? null" icon="help" hint="Kuis dibuat guru" />
                <x-admin.stat-card label="Kuis aktif" :value="$stats['active_quizzes'] ?? null" icon="check" hint="Siap dikerjakan siswa" />
                <x-admin.stat-card label="Pengerjaan kuis" :value="$stats['attempts'] ?? null" icon="chart" hint="Total percobaan siswa" />
            </section>

            {{-- Baris Materi Terbaru & Evaluasi Kuis --}}
            <div class="grid gap-5 lg:grid-cols-2">
                {{-- Materi Terbaru Milik Guru --}}
                <section aria-labelledby="h-teacher-materials" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                        <div>
                            <h2 id="h-teacher-materials" class="text-base font-semibold text-nalar-text">Materi terbaru</h2>
                            <p class="mt-0.5 text-xs text-stone-500">Bahan ajar yang telah Anda susun</p>
                        </div>
                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ ($stats['materials'] ?? null) === null ? 'bg-stone-100 text-stone-500' : 'bg-nalar-soft text-nalar' }}">
                            {{ ($stats['materials'] ?? null) === null ? 'Belum tersedia' : 'Aktif' }}
                        </span>
                    </div>

                    @if (($recentMaterials ?? null) === null)
                        <x-admin.empty-state
                            title="Modul materi belum tersedia"
                            description="Daftar materi yang Anda buat akan muncul di sini setelah modul materi pembelajaran diimplementasikan." />
                    @elseif ($recentMaterials->isEmpty())
                        <x-admin.empty-state
                            title="Belum ada materi pembelajaran"
                            description="Anda belum menambahkan materi pembelajaran. Materi yang Anda susun akan tampil di sini." />
                    @else
                        <ul class="mt-2 divide-y divide-stone-200/80">
                            @foreach ($recentMaterials as $material)
                                <li class="flex items-start justify-between gap-3 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-[13px] font-medium text-nalar-text">{{ $material->title }}</p>
                                        @if (! empty($material->description))
                                            <p class="mt-0.5 line-clamp-1 text-xs text-stone-500">{{ $material->description }}</p>
                                        @endif
                                    </div>
                                    @if (! empty($material->status))
                                        <span class="shrink-0 rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-medium text-nalar">
                                            {{ $material->status }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Kuis & Evaluasi Terbaru --}}
                <section aria-labelledby="h-teacher-quizzes" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                        <div>
                            <h2 id="h-teacher-quizzes" class="text-base font-semibold text-nalar-text">Kuis & evaluasi terbaru</h2>
                            <p class="mt-0.5 text-xs text-stone-500">Daftar kuis dan ringkasan pengerjaan siswa</p>
                        </div>
                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ ($stats['quizzes'] ?? null) === null ? 'bg-stone-100 text-stone-500' : 'bg-nalar-soft text-nalar' }}">
                            {{ ($stats['quizzes'] ?? null) === null ? 'Belum tersedia' : 'Aktif' }}
                        </span>
                    </div>

                    @if (($recentQuizzes ?? null) === null)
                        <x-admin.empty-state
                            title="Modul kuis belum tersedia"
                            description="Paket kuis dan hasil pengerjaan siswa akan muncul setelah modul kuis dibuat." />
                    @elseif ($recentQuizzes->isEmpty())
                        <x-admin.empty-state
                            title="Belum ada kuis"
                            description="Belum ada paket kuis yang Anda publikasikan untuk evaluasi siswa." />
                    @else
                        <ul class="mt-2 divide-y divide-stone-200/80">
                            @foreach ($recentQuizzes as $quiz)
                                <li class="flex items-start justify-between gap-3 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-[13px] font-medium text-nalar-text">{{ $quiz->title }}</p>
                                        @if (! empty($quiz->description))
                                            <p class="mt-0.5 line-clamp-1 text-xs text-stone-500">{{ $quiz->description }}</p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 rounded bg-nalar-soft px-2 py-0.5 text-[11px] font-medium text-nalar">
                                        {{ ! empty($quiz->status) ? $quiz->status : 'Tersedia' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            {{-- Bagian Pintasan Kerja Pengajar --}}
            <section aria-labelledby="h-teacher-actions" class="rounded-2xl border border-stone-200/80 bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-stone-200/80">
                    <div>
                        <h2 id="h-teacher-actions" class="text-base font-semibold text-nalar-text">Aksi cepat pengajaran</h2>
                        <p class="mt-0.5 text-xs text-stone-500">Alur kerja utama guru dalam menyiapkan pembelajaran dan evaluasi</p>
                    </div>
                    <span class="rounded-md bg-stone-100 px-2.5 py-1 text-xs font-medium text-stone-600">
                        Ruang Pengajar
                    </span>
                </div>

                <div class="mt-4 grid gap-3.5 sm:grid-cols-3">
                    <div class="rounded-xl border border-stone-200/70 bg-nalar-bg p-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="grid size-8 place-items-center rounded-lg bg-nalar-soft text-nalar">
                                <x-admin.icon name="book" class="size-4" />
                            </span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $createMaterialUrl ? 'bg-nalar-soft text-nalar' : 'bg-stone-200/70 text-stone-600' }}">
                                {{ $createMaterialUrl ? 'Tersedia' : 'Segera hadir' }}
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] font-semibold text-nalar-text">Susun Materi Baru</p>
                        <p class="mt-1 text-xs leading-relaxed text-stone-500">
                            Tulis bahan ajar berdasarkan jenjang dan kategori mata pelajaran.
                        </p>
                    </div>

                    <div class="rounded-xl border border-stone-200/70 bg-nalar-bg p-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="grid size-8 place-items-center rounded-lg bg-nalar-soft text-nalar">
                                <x-admin.icon name="help" class="size-4" />
                            </span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $questionBankUrl ? 'bg-nalar-soft text-nalar' : 'bg-stone-200/70 text-stone-600' }}">
                                {{ $questionBankUrl ? 'Tersedia' : 'Segera hadir' }}
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] font-semibold text-nalar-text">Kelola Bank Soal</p>
                        <p class="mt-1 text-xs leading-relaxed text-stone-500">
                            Siapkan daftar pertanyaan pilihan ganda maupun latihan pemahaman.
                        </p>
                    </div>

                    <div class="rounded-xl border border-stone-200/70 bg-nalar-bg p-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="grid size-8 place-items-center rounded-lg bg-nalar-soft text-nalar">
                                <x-admin.icon name="check" class="size-4" />
                            </span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $createQuizUrl ? 'bg-nalar-soft text-nalar' : 'bg-stone-200/70 text-stone-600' }}">
                                {{ $createQuizUrl ? 'Tersedia' : 'Segera hadir' }}
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] font-semibold text-nalar-text">Publikasikan Kuis</p>
                        <p class="mt-1 text-xs leading-relaxed text-stone-500">
                            Rangkai soal menjadi kuis evaluasi dan pantau hasil belajar siswa.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Panel Sekunder Kanan (Profil Guru & Status Modul Pengajaran) --}}
        <aside aria-label="Informasi guru dan status modul pengajaran" class="space-y-5">
            {{-- Kartu Profil Guru --}}
            <section aria-label="Profil guru" class="rounded-2xl border border-stone-200/80 bg-white p-5">
                <div class="flex items-center gap-3.5">
                    <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-nalar-soft text-sm font-semibold text-nalar">
                        {{ $initials }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-nalar-text">{{ $user->name }}</p>
                        <p class="truncate text-xs text-stone-500">{{ $user->email }}</p>
                        <span class="mt-1 inline-block rounded bg-nalar-soft px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-nalar">
                            GURU
                        </span>
                    </div>
                </div>

                <dl class="mt-4 divide-y divide-stone-200/80 border-t border-stone-200/80 pt-1 text-xs">
                    <div class="flex justify-between py-2.5">
                        <dt class="text-stone-500">Peran akses</dt>
                        <dd class="font-medium text-nalar-text">Guru Pengajar</dd>
                    </div>
                    @if ($user->created_at)
                        <div class="flex justify-between py-2.5">
                            <dt class="text-stone-500">Terdaftar sejak</dt>
                            <dd class="font-medium tabular-nums text-nalar-text">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            {{-- Daftar Status Modul Pengajaran --}}
            <section aria-labelledby="h-teacher-module-status" class="rounded-2xl border border-stone-200/80 bg-white p-5">
                <div class="flex items-center justify-between gap-2">
                    <h2 id="h-teacher-module-status" class="text-sm font-semibold text-nalar-text">Status Modul Ajar</h2>
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
