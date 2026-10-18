<x-layouts.app title="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Halo, {{ $user->name }}</h1>
        <p class="mt-1 text-[13px] text-stone-500">Pantau aktivitas belajar, materi pembelajaran, dan evaluasi pemahamanmu di Nalar.</p>
    </div>

    <section aria-label="Ringkasan aktivitas belajar" class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-admin.stat-card label="Materi tersedia" :value="$stats['materials'] ?? null" />
        <x-admin.stat-card label="Materi selesai" :value="$stats['completed_materials'] ?? null" />
        <x-admin.stat-card label="Kuis tersedia" :value="$stats['quizzes'] ?? null" />
        <x-admin.stat-card label="Kuis dikerjakan" :value="$stats['quiz_attempts'] ?? null" />
    </section>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <section aria-labelledby="h-last-studied">
                <h2 id="h-last-studied" class="text-base font-semibold">Materi terakhir dipelajari</h2>
                @if (($lastStudiedMaterial ?? null) === null)
                    @if (($stats['materials'] ?? null) === null)
                        <x-admin.empty-state class="mt-3 border-t border-stone-200"
                            title="Modul materi belum tersedia"
                            description="Materi yang terakhir kamu pelajari akan muncul di sini setelah modul pembelajaran aktif." />
                    @else
                        <x-admin.empty-state class="mt-3 border-t border-stone-200"
                            title="Belum ada aktivitas belajar"
                            description="Pilih salah satu materi yang tersedia untuk mulai mempelajari konsep baru." />
                    @endif
                @else
                    <div class="mt-3 rounded-lg border border-stone-200 bg-white p-4">
                        <p class="text-sm font-medium">{{ $lastStudiedMaterial->title }}</p>
                        <p class="mt-1 text-xs text-stone-500">Status: {{ $lastStudiedMaterial->status ?? 'Sedang dipelajari' }}</p>
                    </div>
                @endif
            </section>

            <section aria-labelledby="h-available-materials">
                <div class="flex items-baseline justify-between gap-2">
                    <h2 id="h-available-materials" class="text-base font-semibold">Materi pembelajaran tersedia</h2>
                    @if (Route::has('materials.index'))
                        <a href="{{ route('materials.index') }}" class="text-xs font-medium text-nalar hover:underline">Lihat semua materi</a>
                    @endif
                </div>
                @if (($availableMaterials ?? null) === null)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Modul materi belum tersedia"
                        description="Daftar materi pembelajaran akan tampil di sini setelah modul materi dibuat." />
                @elseif ($availableMaterials->isEmpty())
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Belum ada materi pembelajaran"
                        description="Materi pembelajaran belum ditambahkan." />
                @else
                    <ul class="mt-3 divide-y divide-stone-200 border-y border-stone-200">
                        @foreach ($availableMaterials as $material)
                            <li class="py-3">
                                @if (! empty($material->slug) && Route::has('materials.show'))
                                    <a href="{{ route('materials.show', $material->slug) }}" class="group block">
                                        <p class="text-[13px] font-medium text-nalar-text group-hover:text-nalar">{{ $material->title }}</p>
                                        @if (! empty($material->description))
                                            <p class="mt-1 line-clamp-2 text-xs text-stone-500">{{ $material->description }}</p>
                                        @endif
                                    </a>
                                @else
                                    <p class="text-[13px] font-medium">{{ $material->title }}</p>
                                    @if (! empty($material->description))
                                        <p class="mt-1 text-xs text-stone-500">{{ $material->description }}</p>
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <div class="space-y-8">
            <section aria-labelledby="h-quiz-results">
                <h2 id="h-quiz-results" class="text-base font-semibold">Ringkasan hasil kuis</h2>
                @if (($recentQuizResults ?? null) === null)
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Modul kuis belum tersedia"
                        description="Riwayat skor dan hasil evaluasi kuis akan muncul setelah modul kuis dibuat." />
                @elseif ($recentQuizResults->isEmpty())
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Belum ada kuis yang dikerjakan"
                        description="Hasil kuis yang telah kamu selesaikan akan tercatat di sini." />
                @else
                    <ul class="mt-3 divide-y divide-stone-200 border-y border-stone-200 text-[13px]">
                        @foreach ($recentQuizResults as $attempt)
                            <li class="flex items-baseline justify-between gap-4 py-3">
                                <span class="min-w-0 truncate">{{ $attempt->quiz_title ?? 'Kuis' }}</span>
                                @if (isset($attempt->score))
                                    <span class="shrink-0 font-medium tabular-nums">{{ $attempt->score }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section aria-labelledby="h-ai-assistant">
                <h2 id="h-ai-assistant" class="text-base font-semibold">Pendamping belajar AI</h2>
                @if (Route::has('ai.index'))
                    <div class="mt-3 border-t border-stone-200 py-4">
                        <p class="text-[13px] text-stone-600">Ajukan pertanyaan untuk memahami materi secara bertahap.</p>
                        <a href="{{ route('ai.index') }}" class="mt-3 inline-flex items-center rounded-md bg-nalar-soft px-3 py-2 text-xs font-semibold text-nalar hover:bg-stone-100">Buka Bantuan AI</a>
                    </div>
                @else
                    <x-admin.empty-state class="mt-3 border-t border-stone-200"
                        title="Modul bantuan AI belum tersedia"
                        description="Fitur tanya jawab berbantuan AI akan dapat diakses setelah modul materi dan integrasi AI selesai disiapkan." />
                @endif
            </section>
        </div>
    </div>
</x-layouts.app>
