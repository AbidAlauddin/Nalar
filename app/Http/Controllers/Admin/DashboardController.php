<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if (! $this->isSuperAdmin($user)) {
            // Pertahankan tampilan dashboard pengguna biasa yang sudah ada.
            return view('dashboard', ['user' => $user]);
        }

        $materials = $this->countOrNull('materials');
        $quizzes   = $this->countOrNull('quizzes');
        $attempts  = $this->countOrNull('quiz_attempts');

        return view('admin.dashboard', [
            'user'       => $user,
            // null = tabel belum ada (modul belum dibuat); 0 = tabel ada, data kosong.
            'stats' => [
                'users'     => User::count(),
                'materials' => $materials,
                'quizzes'   => $quizzes,
                'attempts'  => $attempts,
            ],
            'categories' => $this->countOrNull('material_categories'),
            'activities' => $this->recentActivities(),
        ]);
    }

    /**
     * Sesuaikan dengan implementasi role yang sudah ada,
     * misalnya $user->role === User::ROLE_SUPER_ADMIN atau $user->hasRole(...).
     */
    private function isSuperAdmin(?User $user): bool
    {
        return $user !== null && $user->role === 'super_admin';
    }

    private function countOrNull(string $table): ?int
    {
        return Schema::hasTable($table) ? DB::table($table)->count() : null;
    }

    /** Aktivitas diambil dari data yang benar-benar tercatat, tanpa data buatan. */
    private function recentActivities(): Collection
    {
        $events = collect();

        User::latest()->limit(5)->get(['name', 'created_at'])->each(
            fn ($u) => $events->push([
                'text' => "Pengguna baru terdaftar: {$u->name}",
                'at'   => $u->created_at,
            ])
        );

        foreach (['materials' => 'Materi baru dibuat', 'quizzes' => 'Kuis baru dibuat'] as $table => $label) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'title') && Schema::hasColumn($table, 'created_at')) {
                DB::table($table)->orderByDesc('created_at')->limit(5)->get(['title', 'created_at'])->each(
                    fn ($row) => $events->push([
                        'text' => "{$label}: {$row->title}",
                        'at'   => Carbon::parse($row->created_at),
                    ])
                );
            }
        }

        return $events->filter(fn ($e) => $e['at'])->sortByDesc('at')->take(8)->values();
    }
}