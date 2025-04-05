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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom_produit');
            $table->text('description')->nullable();
            $table->integer('prix');
            $table->integer('quantite_disponible');
            $table->integer('ventes')->nullable()->default(0);
            $table->decimal('note', 2, 1)->default(0);
            $table->foreignId('secteur_id')->constrained('secteurs')->onDelete('cascade');
            $table->foreignId('sous_secteur_id')->constrained('sous_secteurs')->onDelete('cascade');
            $table->foreignId('activite_id')->constrained('activites')->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained('producteurs')->onDelete('cascade');
            $table->string('code_type')->nullable()->comment('Code du type selon classification VetLink');
            $table->string('unite_mesure')->default('kg');
            $table->string('image_principale');
            $table->json('images_secondaires')->nullable();
            // Certification et labels
            $table->boolean('est_bio')->default(false);
            $table->json('certifications')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
