<?php

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
            $table->enum('type', ['image', 'video', 'pdf', 'document', 'webpage'])->default('image');
            $table->string('file_path')->nullable(); // يصبح nullable لأن webpage ما عندها ملف
            $table->string('url')->nullable(); // العمود الجديد للروابط
            $table->string('thumbnail_path')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('duration')->nullable();
            $table->string('resolution', 50)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};