<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (suppression).
     */
    public function up(): void
    {
        // 1. Supprimer d'abord la contrainte de clé étrangère dans produits
        Schema::table('produits', function (Blueprint $table) {
            // Vérifier si la colonne existe avant de la supprimer
            if (Schema::hasColumn('produits', 'categorie_id')) {
                $table->dropForeign(['categorie_id']);
                $table->dropColumn('categorie_id');
            }
        });

        // 2. Ensuite supprimer la table categories
        Schema::dropIfExists('categories');
    }

    /**
     * Reverse the migrations (rollback).
     */
    public function down(): void
    {
        // 1. Recréer la table categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });

        // 2. Recréer la colonne categorie_id dans produits
        Schema::table('produits', function (Blueprint $table) {
            $table->foreignId('categorie_id')->nullable()->constrained('categories');
        });
    }
};
