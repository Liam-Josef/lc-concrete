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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('answer_1');
            $table->boolean('answer_1_correct')->default(false);
            $table->string('answer_2');
            $table->boolean('answer_2_correct')->default(false);
            $table->string('answer_3')->nullable();
            $table->boolean('answer_3_correct')->default(false);
            $table->string('answer_4')->nullable();
            $table->boolean('answer_4_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
