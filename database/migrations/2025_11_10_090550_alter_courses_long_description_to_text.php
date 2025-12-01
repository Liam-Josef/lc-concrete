<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // EITHER with DBAL:
        // composer require doctrine/dbal
        // Schema::table('courses', function (Blueprint $table) {
        //     $table->text('short_description')->nullable()->change();
        // });

        // OR raw SQL (no DBAL needed):
        DB::statement('ALTER TABLE courses MODIFY long_description TEXT NULL');
    }

    public function down(): void
    {
        // Revert to VARCHAR(255)
        DB::statement('ALTER TABLE courses MODIFY long_description VARCHAR(255) NULL');
    }
};
