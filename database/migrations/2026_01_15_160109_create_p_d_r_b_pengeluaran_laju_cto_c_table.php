<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p_d_r_b_pengeluaran_laju_cto_c', function (Blueprint $table) {
            $table->id();
            $table->string('expenditure_category', 255);
            $table->unsignedSmallInteger('year');
            $table->enum('quarter', ['I', 'II', 'III', 'IV', 'TOTAL']);
            $table->string('preliminary_flag', 3)->nullable()->default('');
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['expenditure_category', 'year', 'quarter'], 'pdrb_pel_ctoc_ec_y_q_unique');
            $table->index(['year', 'quarter', 'expenditure_category'], 'pdrb_pel_ctoc_y_q_ec_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p_d_r_b_pengeluaran_laju_cto_c');
    }
};

