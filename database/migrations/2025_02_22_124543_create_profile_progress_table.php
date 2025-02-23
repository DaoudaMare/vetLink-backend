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
        Schema::create('profile_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('completion_percentage')->default(30); // Pourcentage de complétion du profil
            $table->enum('status', ['incomplete', 'verified'])->default('incomplete'); // Statut du profil
            $table->timestamp('last_reminder_at')->nullable(); // Dernière relance envoyée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_progress');
    }
};
