<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id('lost_found_item_id');
            $table->foreignId('user_id')->constrained('users', 'user_id'); // User who reported
            $table->foreignId('instansi_id')->constrained('instansi', 'instansi_id');
            $table->enum('type', ['lost', 'found']);
            $table->string('item_name');
            $table->text('description');
            $table->string('location');
            $table->date('date');
            $table->string('contact_info');
            $table->string('status_item')->default('open'); // e.g., open, claimed, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};