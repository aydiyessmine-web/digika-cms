<?php
// database/migrations/2026_06_01_000008_create_ai_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['slogan', 'keywords', 'evaluation', 'visual_idea', 'description']);
            $table->text('prompt');
            $table->text('response')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->integer('duration_ms')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_requests');
    }
};