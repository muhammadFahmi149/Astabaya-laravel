<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gini_ratios', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->enum('location_type', ['REGENCY', 'MUNICIPALITY']);
            $table->unsignedSmallInteger('year');
            $table->decimal('gini_ratio_value', 5, 3);
            $table->timestamps();
            
            $table->unique(['location_name', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gini_ratios');
    }
};

