<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // أضف عمود url
            if (!Schema::hasColumn('media', 'url')) {
                $table->string('url')->nullable()->after('file_path');
            }
            // اجعل file_path nullable
            if (Schema::hasColumn('media', 'file_path')) {
                $table->string('file_path')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'url')) {
                $table->dropColumn('url');
            }
        });
    }
};