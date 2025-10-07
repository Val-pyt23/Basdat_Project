<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('category_id')->constrained('categories', 'category_id');
            $table->foreignId('asset_id')->nullable()->constrained('assets', 'asset_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users', 'user_id');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('status')->default('pending'); // e.g., pending, in_progress, completed, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_reports');
    }
};