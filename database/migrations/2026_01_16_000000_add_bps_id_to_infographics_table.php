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
        Schema::table('infographics', function (Blueprint $table) {
            $table->string('bps_id')->nullable()->after('id');
            $table->unique('bps_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infographics', function (Blueprint $table) {
            $table->dropUnique(['bps_id']);
            $table->dropColumn('bps_id');
        });
    }
};

