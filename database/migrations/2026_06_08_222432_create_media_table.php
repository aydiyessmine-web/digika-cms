<?php
// database/migrations/2026_06_01_000003_create_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['image', 'video', 'document']);
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->integer('size')->nullable()->comment('Taille en KB');
            $table->integer('duration')->nullable()->comment('Durée vidéo en secondes');
            $table->string('resolution', 50)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};