<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'planned', 'finished'])->default('planned');
            $table->integer('progress')->default(0);
            $table->integer('media_count')->default(0);
            $table->timestamps();
            $table->string('background_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};