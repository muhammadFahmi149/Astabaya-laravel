<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inflasi_per_komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('commodity_code', 50);
            $table->string('commodity_name');
            $table->string('flag', 10)->nullable();
            $table->unsignedSmallInteger('year');
            $table->enum('month', [
                'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
                'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER'
            ]);
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['commodity_code', 'flag', 'year', 'month']);
            $table->index(['commodity_code', 'year']);
            $table->index(['year', 'month']);
            $table->index(['commodity_code', 'flag', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inflasi_per_komoditas');
    }
};

