<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p_d_r_b_lapangan_usaha_a_d_h_k', function (Blueprint $table) {
            $table->id();
            $table->string('industry_category', 255);
            $table->unsignedSmallInteger('year');
            $table->string('preliminary_flag', 3)->nullable()->default('');
            $table->decimal('value', 20, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['industry_category', 'year'], 'pdrb_lu_adhk_ic_y_unique');
            $table->index(['year', 'industry_category'], 'pdrb_lu_adhk_y_ic_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p_d_r_b_lapangan_usaha_a_d_h_k');
    }
};

