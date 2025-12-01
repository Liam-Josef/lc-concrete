<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('lesson_student', function (Blueprint $table) {
            $table->boolean('access_granted')
                ->default(false)
                ->after('complete');

            $table->timestamp('access_granted_at')
                ->nullable()
                ->after('access_granted');
        });
    }

    public function down()
    {
        Schema::table('lesson_student', function (Blueprint $table) {
            $table->dropColumn(['access_granted', 'access_granted_at']);
        });
    }
};
