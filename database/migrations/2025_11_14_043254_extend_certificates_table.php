<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->after('id');
            $table->unsignedBigInteger('lesson_id')->after('student_id');
            $table->string('serial')->nullable()->after('lesson_id');   // fill after create
            $table->timestamp('issued_at')->nullable()->after('serial');

            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();

            $table->unique(['student_id','lesson_id']); // one cert per student/lesson
            $table->unique('serial');                   // serial must be unique
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['student_id','lesson_id']);
            $table->dropUnique(['serial']);
            $table->dropForeign(['student_id']);
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['student_id','lesson_id','serial','issued_at']);
        });
    }
};
