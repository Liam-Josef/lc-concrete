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
        Schema::table('question_student', function (Blueprint $table) {
            $table->string('answer')->nullable()->after('question_id');  // e.g. 'A'|'B'|'C'|'D'|'T'|'F'
            $table->boolean('is_correct')->default(false)->after('answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_student', function (Blueprint $table) {
            $table->dropColumn(['answer', 'is_correct']);
        });
    }
};
