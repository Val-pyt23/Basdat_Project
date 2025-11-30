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
        Schema::table('facility_reports', function (Blueprint $table) {
            // Tambahkan jika belum ada
            if (!Schema::hasColumn('facility_reports', 'completion_notes')) {
                $table->text('completion_notes')->nullable()->after('attachment_path');
            }
            if (!Schema::hasColumn('facility_reports', 'completion_image_path')) {
                $table->string('completion_image_path')->nullable()->after('completion_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facility_reports', function (Blueprint $table) {
            if (Schema::hasColumn('facility_reports', 'completion_image_path')) {
                $table->dropColumn('completion_image_path');
            }
            if (Schema::hasColumn('facility_reports', 'completion_notes')) {
                $table->dropColumn('completion_notes');
            }
        });
    }
};
