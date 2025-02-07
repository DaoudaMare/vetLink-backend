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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->string('password');
            $table->enum('type_utilisateur', ['consommateur', 'producteur']);
            $table->boolean('abonnement')->default(false);
            $table->softDeletes();
            $table->timestamps();

            // NB: La méthode $table->softDeletes(); ajoute une colonne deleted_at à la table, permettant la suppression douce (soft delete).
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
