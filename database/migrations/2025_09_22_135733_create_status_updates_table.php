<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_updates', function (Blueprint $table) {
            $table->id('update_id');
            $table->foreignId('report_id')->constrained('facility_reports', 'report_id')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users', 'user_id');
            $table->text('notes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_updates');
    }
};