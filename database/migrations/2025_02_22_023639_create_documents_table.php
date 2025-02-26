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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type_document'); // Type de document (ex. "Carte d'identité", "Certificat de producteur", etc)
            $table->string('file_path'); // Chemin du fichier
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Statut du document
            $table->text('comments')->nullable(); // Commentaires en cas de rejet
            $table->foreignId('moderateur_id')->constrained('users')->onDelete('cascade')->nullable(); //celu qui traite le document

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
