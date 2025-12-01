<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // nullable so existing lessons don’t break; index + FK to courses(id)
            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->nullOnDelete()   // if a course is deleted, keep the lesson but set course_id = null
                ->cascadeOnUpdate()
                ->after('org_id'); // or wherever makes sense in your schema
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};
