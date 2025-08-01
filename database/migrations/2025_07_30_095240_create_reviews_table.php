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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('produits')->cascadeOnDelete();
            $table->integer('rating')->comment('Note de 1 à 5');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']); // Un utilisateur ne peut noter un produit qu'une seule fois
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
