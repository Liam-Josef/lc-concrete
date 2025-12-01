<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessment_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_attempt_id')->constrained('assessment_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('selected_option')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(
                ['assessment_attempt_id', 'question_id'],
                'attempt_q_unique'
            );
        });
    }
    public function down(): void { Schema::dropIfExists('assessment_attempt_answers'); }
};
