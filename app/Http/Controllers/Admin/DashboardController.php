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

        if ($this->isSuperAdmin($user)) {
            return $this->admin($request);
        }

        if ($this->isTeacher($user)) {
            return $this->teacher($request);
        }

        return $this->student($request);
    }

    public function admin(Request $request): View
    {
        $user = $request->user();

        return view('admin.dashboard', [
            'user'       => $user,
            // null = tabel belum ada (modul belum dibuat); 0 = tabel ada, data kosong.
            'stats' => [
                'users'     => User::count(),
                'materials' => $this->countOrNull('materials'),
                'quizzes'   => $this->countOrNull('quizzes'),
                'attempts'  => $this->countOrNull('quiz_attempts'),
            ],
            'categories' => $this->countOrNull('material_categories'),
            'activities' => $this->recentActivities(),
        ]);
    }

    public function teacher(Request $request): View
    {
        $user = $request->user();

        return view('teacher.dashboard', [
            'user' => $user,
            // null = tabel belum ada (modul belum dibuat); 0 = tabel ada, data kosong.
            'stats' => [
                'materials'      => $this->teacherMaterialsCount($user),
                'quizzes'        => $this->teacherQuizzesCount($user),
                'active_quizzes' => $this->teacherActiveQuizzesCount($user),
                'attempts'       => $this->teacherQuizAttemptsCount($user),
            ],
            'categories'      => $this->countOrNull('material_categories'),
            'questions'       => $this->countOrNull('questions'),
            'recentMaterials' => $this->teacherRecentMaterials($user),
            'recentQuizzes'   => $this->teacherRecentQuizzes($user),
        ]);
    }

    public function student(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'user' => $user,
            'stats' => [
                'materials'           => $this->countOrNull('materials'),
                'completed_materials' => $this->userCompletedMaterialsCount($user),
                'quizzes'             => $this->countOrNull('quizzes'),
                'quiz_attempts'       => $this->userQuizAttemptsCount($user),
            ],
            'availableMaterials'  => $this->availableMaterials(),
            'lastStudiedMaterial' => $this->lastStudiedMaterial($user),
            'recentQuizResults'   => $this->userRecentQuizResults($user),
        ]);
    }

    private function isSuperAdmin(?User $user): bool
    {
        return $user !== null && $user->isSuperAdmin();
    }

    private function isTeacher(?User $user): bool
    {
        return $user !== null && $user->isTeacher();
    }

    private function countOrNull(string $table): ?int
    {
        return Schema::hasTable($table) ? DB::table($table)->count() : null;
    }

    private function teacherOwnerColumn(string $table): ?string
    {
        foreach (['user_id', 'author_id', 'created_by', 'teacher_id'] as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function teacherMaterialsCount(User $user): ?int
    {
        if (! Schema::hasTable('materials')) {
            return null;
        }

        $query = DB::table('materials');
        $ownerColumn = $this->teacherOwnerColumn('materials');

        if ($ownerColumn !== null) {
            $query->where($ownerColumn, $user->id);
        }

        return $query->count();
    }

    private function teacherQuizzesCount(User $user): ?int
    {
        if (! Schema::hasTable('quizzes')) {
            return null;
        }

        $query = DB::table('quizzes');
        $ownerColumn = $this->teacherOwnerColumn('quizzes');

        if ($ownerColumn !== null) {
            $query->where($ownerColumn, $user->id);
        }

        return $query->count();
    }

    private function teacherActiveQuizzesCount(User $user): ?int
    {
        if (! Schema::hasTable('quizzes')) {
            return null;
        }

        $query = DB::table('quizzes');
        $ownerColumn = $this->teacherOwnerColumn('quizzes');

        if ($ownerColumn !== null) {
            $query->where($ownerColumn, $user->id);
        }

        if (Schema::hasColumn('quizzes', 'is_active')) {
            $query->where('is_active', true);
        } elseif (Schema::hasColumn('quizzes', 'is_published')) {
            $query->where('is_published', true);
        } elseif (Schema::hasColumn('quizzes', 'status')) {
            $query->whereIn('status', ['active', 'published']);
        }

        return $query->count();
    }

    private function teacherQuizAttemptsCount(User $user): ?int
    {
        if (! Schema::hasTable('quiz_attempts')) {
            return null;
        }

        $query = DB::table('quiz_attempts');

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quiz_attempts', 'quiz_id')) {
            $ownerColumn = $this->teacherOwnerColumn('quizzes');

            if ($ownerColumn !== null) {
                $query->join('quizzes', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                    ->where("quizzes.{$ownerColumn}", $user->id);
            }
        }

        return $query->count();
    }

    private function teacherRecentMaterials(User $user): ?Collection
    {
        if (! Schema::hasTable('materials') || ! Schema::hasColumn('materials', 'title')) {
            return null;
        }

        $columns = array_values(array_filter(
            ['id', 'title', 'description', 'slug', 'status', 'created_at'],
            fn (string $column) => Schema::hasColumn('materials', $column)
        ));

        $query = DB::table('materials');
        $ownerColumn = $this->teacherOwnerColumn('materials');

        if ($ownerColumn !== null) {
            $query->where($ownerColumn, $user->id);
        }

        if (in_array('created_at', $columns, true)) {
            $query->orderByDesc('created_at');
        }

        return $query->limit(5)->get($columns);
    }

    private function teacherRecentQuizzes(User $user): ?Collection
    {
        if (! Schema::hasTable('quizzes') || ! Schema::hasColumn('quizzes', 'title')) {
            return null;
        }

        $columns = array_values(array_filter(
            ['id', 'title', 'description', 'status', 'is_active', 'created_at'],
            fn (string $column) => Schema::hasColumn('quizzes', $column)
        ));

        $query = DB::table('quizzes');
        $ownerColumn = $this->teacherOwnerColumn('quizzes');

        if ($ownerColumn !== null) {
            $query->where($ownerColumn, $user->id);
        }

        if (in_array('created_at', $columns, true)) {
            $query->orderByDesc('created_at');
        }

        return $query->limit(5)->get($columns);
    }

    private function userCompletedMaterialsCount(User $user): ?int
    {
        if (! Schema::hasTable('learning_progress') || ! Schema::hasColumn('learning_progress', 'user_id')) {
            return null;
        }

        $query = DB::table('learning_progress')->where('user_id', $user->id);

        if (Schema::hasColumn('learning_progress', 'status')) {
            $query->where('status', 'completed');
        }

        return $query->count();
    }

    private function userQuizAttemptsCount(User $user): ?int
    {
        if (! Schema::hasTable('quiz_attempts') || ! Schema::hasColumn('quiz_attempts', 'user_id')) {
            return null;
        }

        return DB::table('quiz_attempts')->where('user_id', $user->id)->count();
    }

    private function availableMaterials(): ?Collection
    {
        if (! Schema::hasTable('materials') || ! Schema::hasColumn('materials', 'title')) {
            return null;
        }

        $columns = array_values(array_filter(
            ['id', 'title', 'description', 'slug', 'grade_level', 'created_at'],
            fn (string $column) => Schema::hasColumn('materials', $column)
        ));

        $query = DB::table('materials');
        if (Schema::hasColumn('materials', 'status')) {
            $query->where('status', 'published');
        }
        if (in_array('created_at', $columns, true)) {
            $query->orderByDesc('created_at');
        }

        return $query->limit(5)->get($columns);
    }

    private function lastStudiedMaterial(User $user): ?object
    {
        if (
            ! Schema::hasTable('learning_progress')
            || ! Schema::hasTable('materials')
            || ! Schema::hasColumn('learning_progress', 'user_id')
            || ! Schema::hasColumn('learning_progress', 'material_id')
        ) {
            return null;
        }

        return DB::table('learning_progress')
            ->join('materials', 'materials.id', '=', 'learning_progress.material_id')
            ->where('learning_progress.user_id', $user->id)
            ->orderByDesc('learning_progress.updated_at')
            ->select('materials.title', 'learning_progress.status', 'learning_progress.updated_at')
            ->first();
    }

    private function userRecentQuizResults(User $user): ?Collection
    {
        if (! Schema::hasTable('quiz_attempts') || ! Schema::hasColumn('quiz_attempts', 'user_id')) {
            return null;
        }

        $query = DB::table('quiz_attempts')->where('quiz_attempts.user_id', $user->id);

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quiz_attempts', 'quiz_id') && Schema::hasColumn('quizzes', 'title')) {
            $query->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_attempts.quiz_id')
                ->select('quiz_attempts.*', 'quizzes.title as quiz_title');
        }

        if (Schema::hasColumn('quiz_attempts', 'created_at')) {
            $query->orderByDesc('quiz_attempts.created_at');
        }

        return $query->limit(5)->get();
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