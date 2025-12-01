<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            // Executive Director
            $table->string('exec_director_name')->nullable()->after('company_email');

            // Store the file path to a rendered signature image (png/svg) in /storage
            $table->string('exec_director_signature_path')->nullable()->after('exec_director_name');

            // Optional: raw SVG or JSON strokes from a signature pad (keeps it editable)
            $table->longText('exec_director_signature_svg')->nullable()->after('exec_director_signature_path');

            // Analytics
            $table->string('ga_measurement_id', 32)->nullable()->after('favicon');  // e.g., G-XXXXXXX
            $table->string('gtm_container_id', 32)->nullable()->after('ga_measurement_id'); // e.g., GTM-XXXX
            $table->json('analytics_scripts')->nullable()->after('gtm_container_id');
            // (array of extra script snippets to inject in <head>)

            // Accreditation
            $table->string('accreditation_image')->nullable()->after('internal_background'); // path in storage
            $table->string('accreditation_image_alt')->nullable()->after('accreditation_image');
        });
    }

    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->dropColumn([
                'exec_director_name',
                'exec_director_signature_path',
                'exec_director_signature_svg',
                'ga_measurement_id',
                'gtm_container_id',
                'analytics_scripts',
                'accreditation_image',
                'accreditation_image_alt',
            ]);
        });
    }
};
