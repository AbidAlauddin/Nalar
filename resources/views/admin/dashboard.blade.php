<x-layouts.admin title="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <p class="mt-1 text-[13px] text-stone-500">Pantau dan kelola aktivitas pembelajaran Nalar.</p>
    </div>

    <section aria-label="Ringkasan statistik" class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-admin.stat-card label="Total pengguna" :value="$stats['users']" />
        <x-admin.stat-card label="Total materi" :value="$stats['materials']" />
        <x-admin.stat-card label="Total kuis" :value="$stats['quizzes']" />
        <x-admin.stat-card label="Aktivitas belajar" :value="$stats['attempts']" hint="Total pengerjaan kuis" />
    </section>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <section class="lg:col-span-2" aria-labelledby="h-activity">
            <h2 id="h-activity" class="text-base font-semibold">Aktivitas terbaru</h2>
            @if ($activities->isEmpty())
                <x-admin.empty-state class="mt-3 border-t border-stone-200"
                    title="Belum ada aktivitas"
                    description="Pengguna baru, materi, dan kuis yang dibuat akan muncul di sini." />
            @else
                <ul class="mt-3 divide-y divide-stone-200 border-y border-stone-200">
                    @foreach ($activities as $event)
                        <li class="flex items-baseline justify-between gap-4 py-3">
                            <span class="min-w-0 truncate text-[13px]">{{ $event['text'] }}</span>
                            <time datetime="{{ $event['at']->toIso8601String() }}" class="shrink-0 text-xs text-stone-500">
                                {{ $event['at']->diffForHumans() }}
                            </time>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <div class="space-y-8">
            <section aria-labelledby="h-materials">
                <h2 id="h-materials" class="text-base font-semibold">Ringkasan materi</h2>
                @if ($stats['materials'] === null)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Modul materi belum tersedia"
                        description="Ringkasan akan muncul setelah modul pembelajaran dibuat." />
                @elseif ($stats['materials'] === 0)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200" title="Belum ada materi" />
                @else
                    <dl class="mt-3 divide-y divide-stone-200 border-y border-stone-200 text-[13px]">
                        <div class="flex justify-between py-3"><dt class="text-stone-500">Total materi</dt><dd class="font-medium tabular-nums">{{ $stats['materials'] }}</dd></div>
                        @if ($categories !== null)
                            <div class="flex justify-between py-3"><dt class="text-stone-500">Total kategori</dt><dd class="font-medium tabular-nums">{{ $categories }}</dd></div>
                        @endif
                    </dl>
                @endif
            </section>

            <section aria-labelledby="h-quiz">
                <h2 id="h-quiz" class="text-base font-semibold">Aktivitas kuis</h2>
                @if ($stats['quizzes'] === null)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Modul kuis belum tersedia"
                        description="Statistik kuis akan muncul setelah modul kuis dibuat." />
                @elseif ($stats['quizzes'] === 0)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200" title="Belum ada kuis" />
                @else
                    <dl class="mt-3 divide-y divide-stone-200 border-y border-stone-200 text-[13px]">
                        <div class="flex justify-between py-3"><dt class="text-stone-500">Total kuis</dt><dd class="font-medium tabular-nums">{{ $stats['quizzes'] }}</dd></div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">Total pengerjaan</dt>
                            <dd class="font-medium tabular-nums">{{ $stats['attempts'] ?? 'Belum dilacak' }}</dd>
                        </div>
                    </dl>
                @endif
            </section>
        </div>
    </div>
</x-layouts.admin>