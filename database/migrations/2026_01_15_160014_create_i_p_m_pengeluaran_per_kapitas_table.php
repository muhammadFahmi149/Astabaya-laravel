<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('i_p_m_pengeluaran_per_kapitas', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->enum('location_type', ['REGENCY', 'MUNICIPALITY']);
            $table->unsignedSmallInteger('year');
            $table->decimal('value', 15, 2);
            $table->timestamps();
            
            $table->unique(['location_name', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_p_m_pengeluaran_per_kapitas');
    }
};

