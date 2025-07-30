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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The pet name');
            $table->string('type')->comment('Pet type: cat, dog, bird, etc.');
            $table->string('color')->default('#8B4513')->comment('Pet color in hex format');
            $table->string('accessory')->nullable()->comment('Pet accessory: hat, bow, collar, etc.');
            $table->json('attributes')->nullable()->comment('Additional pet attributes as JSON');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};