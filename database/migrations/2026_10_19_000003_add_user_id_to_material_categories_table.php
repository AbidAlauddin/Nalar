<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('material_categories', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $superAdminId = DB::table('users')
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->orderBy('id')
            ->value('id');

        if ($superAdminId !== null) {
            DB::table('material_categories')
                ->whereNull('user_id')
                ->update(['user_id' => $superAdminId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
