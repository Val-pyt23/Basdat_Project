<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('report_id')->nullable()->constrained('facility_reports', 'report_id');
            $table->foreignId('asset_id')->constrained('assets', 'asset_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users', 'user_id');
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->text('task_description');
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed
            $table->dateTime('scheduled_date');
            $table->dateTime('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};