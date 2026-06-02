<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_favorites_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('favorites_count')->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();

            $table->unique('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_favorites_statistics');
    }
};
