<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_occupancy_yearly', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->decimal('mktj', 15, 2)->nullable();
            $table->decimal('tpk', 10, 2)->nullable();
            $table->decimal('rlmta', 10, 2)->nullable();
            $table->decimal('rlmtnus', 10, 2)->nullable();
            $table->decimal('rlmtgab', 10, 2)->nullable();
            $table->decimal('gpr', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_occupancy_yearly');
    }
};

