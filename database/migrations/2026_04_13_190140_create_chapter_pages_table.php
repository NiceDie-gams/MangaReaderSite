<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('page_number');
            $table->string('image_path');
            $table->timestamps();
            $table->unique(['chapter_id', 'page_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_pages');
    }
};
