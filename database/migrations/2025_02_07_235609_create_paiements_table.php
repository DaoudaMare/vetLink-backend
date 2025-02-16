<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
           // $table->string('transaction_id')->unique();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->string('operateur');
            $table->string('devise')->default('XOF');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded'])->default('pending');
            $table->string('telephone');
            $table->decimal('montant', 10, 2); // Changer integer en decimal
            $table->timestamp('date_paiement')->useCurrent();
            $table->string('mode_paiement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
