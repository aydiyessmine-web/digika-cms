<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('media_count')->default(0);
            $table->timestamps();
        });
        
        // Insertion des catégories par défaut
        DB::table('categories')->insert([
            ['name' => 'Promotion', 'description' => 'Campagnes promotionnelles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Événement', 'description' => 'Événements spéciaux', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Saisonnier', 'description' => 'Contenu saisonnier', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Branding', 'description' => 'Image de marque', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Produit', 'description' => 'Lancements produits', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};