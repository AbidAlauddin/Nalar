<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (! Schema::hasColumn('materials', 'image_path')) {
                $table->string('image_path')->nullable()->after('description');
            }

            if (! Schema::hasColumn('materials', 'image_caption')) {
                $table->string('image_caption', 255)->nullable()->after('image_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $columns = array_filter(
                ['image_path', 'image_caption'],
                fn (string $col) => Schema::hasColumn('materials', $col)
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
