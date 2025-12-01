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
            $table->dropColumn('org_id');
        });

        Schema::table('lessons', function (Blueprint $table) {
            // Add the new foreign key column
            $table->foreignId('org_id')->nullable()->constrained('organizations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['org_id']);
            $table->dropColumn('org_id');

            $table->string('org_id')->nullable();
        });
    }
};
