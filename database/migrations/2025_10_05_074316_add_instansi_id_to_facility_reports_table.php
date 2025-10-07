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
            // Tambahkan kolom instansi_id setelah kolom asset_id
            $table->foreignId('instansi_id')->nullable()->after('asset_id')->constrained('instansi', 'instansi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facility_reports', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['instansi_id']);
            // Hapus kolomnya
            $table->dropColumn('instansi_id');
        });
    }
};