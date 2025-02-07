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
        Schema::create('producteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('localisation');
            $table->decimal('notation', 2, 1)->default(0);
            $table->string('type_production');
            $table->json('certifications')->nullable();
            $table->string('mode_paiement');
            $table->timestamps();

            /**
             * NB: Un producteur peut avoir plusieurs certifications (bio, commerce équitable, etc.), et leur nombre peut varier d'un producteur à l'autre.
             * Le format JSON permet d'enregistrer ces certifications sous forme d'un tableau dynamique.
             */

             /**
              * decimal('notation', 2, 1)
              *  decimal(2,1) signifie que la colonne notation peut contenir un nombre à virgule avec 2 chiffres au total, dont 1 après la virgule.
              *  Exemple de valeurs possibles : 0.0, 1.5, 2.0, 4.7.
              *  Valeurs interdites : 10.0, 5.55 (car elles dépassent la limite 2,1)
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producteurs');
    }
};
