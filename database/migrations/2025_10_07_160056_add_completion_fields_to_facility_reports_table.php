<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facility_reports', function (Blueprint $table) {
            $table->text('completion_notes')->nullable()->after('status');
            $table->string('completion_image_path')->nullable()->after('completion_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facility_reports', function (Blueprint $table) {
            //
        });
    }
};
