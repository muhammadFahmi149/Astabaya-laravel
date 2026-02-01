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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Polymorphic relationship - equivalent to Django's GenericForeignKey
            $table->morphs('bookmarkable'); // Creates bookmarkable_id and bookmarkable_type
            $table->timestamp('created_at')->useCurrent();
            
            // Ensure unique bookmark per user per item
            $table->unique(['user_id', 'bookmarkable_id', 'bookmarkable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
