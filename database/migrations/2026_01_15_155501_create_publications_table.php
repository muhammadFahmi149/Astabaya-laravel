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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('pub_id')->unique();
            $table->string('title')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('dl', 500)->nullable();
            $table->date('date')->nullable();
            $table->text('abstract')->nullable();
            $table->string('size', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
