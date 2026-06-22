<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // أولاً: نغير نوع العمود إلى string مؤقتاً
        DB::statement("ALTER TABLE ai_requests MODIFY type VARCHAR(50)");
        
        // ثانياً: نحدّث القيم الموجودة
        DB::statement("UPDATE ai_requests SET type = 'general' WHERE type NOT IN ('slogan', 'keywords', 'evaluation', 'visual_idea', 'description', 'general')");
        
        // ثالثاً: نعيد تعيين العمود كـ ENUM مع القيم الجديدة
        DB::statement("ALTER TABLE ai_requests MODIFY type ENUM('slogan', 'keywords', 'evaluation', 'visual_idea', 'description', 'general') DEFAULT 'general'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ai_requests MODIFY type VARCHAR(50)");
        DB::statement("UPDATE ai_requests SET type = 'general' WHERE type NOT IN ('slogan', 'keywords', 'evaluation', 'visual_idea', 'description')");
        DB::statement("ALTER TABLE ai_requests MODIFY type ENUM('slogan', 'keywords', 'evaluation', 'visual_idea', 'description') DEFAULT 'slogan'");
    }
};