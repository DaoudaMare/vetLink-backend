<?php

use App\Enums\TypeSecteurActiviteEnum;
use App\Enums\TypeUserEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_raison_sociale');
            $table->enum('type_user',array_column(TypeUserEnum::cases(), 'value'));
            $table->enum('secteur_activite', array_column(TypeSecteurActiviteEnum::cases(), 'value'))->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('pays');
            $table->string('ville')->nullable();
            $table->string('coordonnees_gps')->nullable();
            $table->string('adresse_physique')->nullable();
            $table->string('photo_profil')->nullable();
            $table->text('description')->nullable();
            $table->string('password');
            $table->json('liens_reseaux_sociaux')->nullable();
            $table->uuid('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // NB: La méthode $table->softDeletes(); ajoute une colonne deleted_at à la table, permettant la suppression douce (soft delete).
            // particulier: vendeur et aussi client
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
