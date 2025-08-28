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
        Schema::table('commande_produit', function (Blueprint $table) {
            // 0: En attente, 1: Confirmé, 2: En préparation, 3: Expédié, 4: Livré
            $table->integer('status')->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_produit', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
