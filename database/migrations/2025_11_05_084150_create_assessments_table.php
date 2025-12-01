<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('type', 10); // 'pre' | 'post'
            $table->string('title')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id','type']);
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};
