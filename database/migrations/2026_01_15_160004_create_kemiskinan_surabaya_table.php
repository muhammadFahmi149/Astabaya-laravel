<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kemiskinan_surabaya', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->decimal('jumlah_penduduk_miskin', 15, 3)->nullable();
            $table->decimal('persentase_penduduk_miskin', 10, 2)->nullable();
            $table->decimal('indeks_kedalaman_kemiskinan_p1', 10, 2)->nullable();
            $table->decimal('indeks_keparahan_kemiskinan_p2', 10, 2)->nullable();
            $table->decimal('garis_kemiskinan', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kemiskinan_surabaya');
    }
};

