<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Daftar resmi role yang diizinkan di Nalar.
     *
     * @var list<string>
     */
    private const OFFICIAL_ROLES = [
        'super_admin',
        'teacher',
        'student',
    ];

    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', self::OFFICIAL_ROLES)
                    ->default('student')
                    ->index()
                    ->after('email');
            });

            return;
        }

        // Konversi nilai role lama (seperti 'user' atau null) ke 'student'
        // agar seluruh akun yang sudah ada tetap valid dan tidak ada data yang hilang.
        DB::table('users')
            ->whereNull('role')
            ->orWhereNotIn('role', self::OFFICIAL_ROLES)
            ->update(['role' => 'student']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', self::OFFICIAL_ROLES)
                ->default('student')
                ->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 32)
                    ->default('student')
                    ->change();
            });
        }
    }
};
