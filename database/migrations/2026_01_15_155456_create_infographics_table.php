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
        Schema::create('infographics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('dl', 500)->nullable();
            $table->timestamps();
        });
        
        // Add index for ordering
        Schema::table('infographics', function (Blueprint $table) {
            $table->index(['created_at', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infographics');
    }
};
