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
        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('education_level', 20)->default('SMA');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['education_level', 'is_active']);
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_category_id')
                ->constrained('material_categories')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->string('education_level', 20)->default('SMA');
            $table->enum('grade_level', ['10', '11', '12'])->default('10');
            $table->text('description');
            $table->longText('content');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->unsignedSmallInteger('estimated_minutes')->default(15);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['education_level', 'grade_level', 'status']);
            $table->index(['material_category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_categories');
    }
};
