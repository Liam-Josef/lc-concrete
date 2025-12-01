<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique(); // e.g. INV-20251104-00001
            $table->date('date');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('paid')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'lesson_id']); // one invoice per student/lesson
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
