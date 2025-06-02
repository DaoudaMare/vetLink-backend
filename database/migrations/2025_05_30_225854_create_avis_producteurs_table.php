<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avis_producteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained('producteurs')->onDelete('cascade');
            $table->decimal('note'); // 1 à 5 étoiles
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'producteur_id']); // Un utilisateur ne peut noter un producteur qu'une seule fois
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis_producteurs');
    }
};
