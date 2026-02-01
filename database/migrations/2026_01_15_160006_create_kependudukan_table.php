<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kependudukan', function (Blueprint $table) {
            $table->id();
            $table->string('age_group', 20);
            $table->unsignedSmallInteger('year');
            $table->enum('gender', ['LK', 'PR', 'TOTAL']); // Laki-Laki, Perempuan, Total
            $table->integer('population')->nullable();
            $table->timestamps();
            
            $table->unique(['age_group', 'year', 'gender']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kependudukan');
    }
};

