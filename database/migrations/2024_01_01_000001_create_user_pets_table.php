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
        Schema::create('user_pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('custom_name')->comment('User-customized pet name');
            $table->string('custom_color')->comment('User-customized pet color');
            $table->string('custom_accessory')->nullable()->comment('User-customized pet accessory');
            $table->integer('happiness')->default(100)->comment('Pet happiness level 0-100');
            $table->integer('hunger')->default(0)->comment('Pet hunger level 0-100');
            $table->timestamp('last_fed_at')->nullable()->comment('When pet was last fed');
            $table->timestamp('adopted_at')->useCurrent()->comment('When pet was adopted');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('pet_id');
            $table->index(['user_id', 'adopted_at']);
            $table->unique(['user_id', 'pet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pets');
    }
};