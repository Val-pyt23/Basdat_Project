<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->string('asset_code')->unique()->nullable();
            $table->string('name'); // e.g., 'AC Ruang 301', 'Proyektor Lab Komputer'
            $table->string('location');
            $table->foreignId('instansi_id')->constrained('instansi', 'instansi_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};