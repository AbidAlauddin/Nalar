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
        if (! Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->foreignId('category_id')
                    ->nullable()
                    ->constrained('material_categories')
                    ->nullOnDelete();
                $table->foreignId('material_id')
                    ->nullable()
                    ->constrained('materials')
                    ->nullOnDelete();
                $table->text('question_text');
                $table->string('image_path')->nullable();
                $table->string('image_caption', 255)->nullable();
                $table->string('question_type', 32)->default('multiple_choice');
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
                $table->text('explanation')->nullable();
                $table->unsignedSmallInteger('default_points')->default(10);
                $table->enum('status', ['draft', 'published', 'archived'])->default('published');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['category_id', 'material_id', 'status']);
                $table->index(['teacher_id', 'status']);
                $table->index(['difficulty', 'status']);
            });
        }

        if (! Schema::hasTable('question_options')) {
            Schema::create('question_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')
                    ->constrained('questions')
                    ->cascadeOnDelete();
                $table->string('option_label', 8);
                $table->text('option_text');
                $table->boolean('is_correct')->default(false);
                $table->unsignedSmallInteger('sort_order')->default(1);
                $table->timestamps();

                $table->index(['question_id', 'sort_order']);
            });
        }

        if (! Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->foreignId('category_id')
                    ->nullable()
                    ->constrained('material_categories')
                    ->nullOnDelete();
                $table->foreignId('material_id')
                    ->nullable()
                    ->constrained('materials')
                    ->nullOnDelete();
                $table->string('title', 180);
                $table->string('slug', 200)->unique();
                $table->text('description')->nullable();
                $table->unsignedSmallInteger('duration_minutes')->nullable();
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();
                $table->unsignedSmallInteger('attempt_limit')->default(3);
                $table->boolean('shuffle_questions')->default(false);
                $table->boolean('shuffle_options')->default(false);
                $table->boolean('show_explanations')->default(true);
                $table->boolean('show_score_immediately')->default(true);
                $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['category_id', 'material_id', 'status']);
                $table->index(['teacher_id', 'status']);
            });
        }

        if (! Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')
                    ->constrained('quizzes')
                    ->cascadeOnDelete();
                $table->foreignId('question_id')
                    ->constrained('questions')
                    ->restrictOnDelete();
                $table->unsignedSmallInteger('sort_order')->default(1);
                $table->unsignedSmallInteger('points')->default(10);
                $table->json('question_snapshot')->nullable();
                $table->timestamps();

                $table->unique(['quiz_id', 'question_id']);
                $table->index(['quiz_id', 'sort_order']);
            });
        }

        if (! Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')
                    ->constrained('quizzes')
                    ->cascadeOnDelete();
                $table->foreignId('student_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->unsignedSmallInteger('attempt_number')->default(1);
                $table->timestamp('started_at');
                $table->timestamp('submitted_at')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->unsignedInteger('earned_points')->default(0);
                $table->unsignedInteger('total_points')->default(0);
                $table->unsignedSmallInteger('correct_count')->nullable();
                $table->enum('status', ['in_progress', 'submitted', 'graded', 'expired'])->default('in_progress');
                $table->timestamps();

                $table->index(['quiz_id', 'student_id', 'attempt_number']);
                $table->index(['student_id', 'status']);
            });
        }

        if (! Schema::hasTable('quiz_answers')) {
            Schema::create('quiz_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attempt_id')
                    ->constrained('quiz_attempts')
                    ->cascadeOnDelete();
                $table->foreignId('question_id')
                    ->constrained('questions')
                    ->restrictOnDelete();
                $table->foreignId('selected_option_id')
                    ->nullable()
                    ->constrained('question_options')
                    ->nullOnDelete();
                $table->boolean('is_correct')->nullable();
                $table->unsignedSmallInteger('points_earned')->default(0);
                $table->timestamp('answered_at')->nullable();
                $table->timestamps();

                $table->unique(['attempt_id', 'question_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
    }
};
