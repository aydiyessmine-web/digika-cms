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
        DB::statement("ALTER TABLE media MODIFY type VARCHAR(50)");
        
        // ثانياً: نحدّث القيم الموجودة
        DB::statement("UPDATE media SET type = 'document' WHERE type NOT IN ('image', 'video', 'pdf', 'document', 'webpage')");
        
        // ثالثاً: نعيد تعيين العمود كـ ENUM مع القيم الجديدة
        DB::statement("ALTER TABLE media MODIFY type ENUM('image', 'video', 'pdf', 'document', 'webpage') DEFAULT 'image'");
    }

    public function down(): void
    {
        // نرجع للـ ENUM القديم
        DB::statement("ALTER TABLE media MODIFY type VARCHAR(50)");
        DB::statement("UPDATE media SET type = 'document' WHERE type NOT IN ('image', 'video', 'document')");
        DB::statement("ALTER TABLE media MODIFY type ENUM('image', 'video', 'document') DEFAULT 'image'");
    }
};