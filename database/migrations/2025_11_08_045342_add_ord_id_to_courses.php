<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('org_id')
                ->constrained('organizations')   // organizations.id
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->nullable()
                ->after('position');            // or ->nullOnDelete()->nullable() if you prefer
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['org_id']);
            $table->dropColumn('org_id');
        });
    }
};
