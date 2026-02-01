<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('human_development_indices', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->enum('location_type', ['REGENCY', 'MUNICIPALITY']); // Kabupaten or Kota
            $table->unsignedSmallInteger('year');
            $table->decimal('ipm_value', 5, 2);
            $table->timestamps();
            
            $table->unique(['location_name', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('human_development_indices');
    }
};

