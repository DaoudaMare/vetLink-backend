<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Categorie;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('description')->nullable();
            $table->foreignIdFor(Categorie::class)->constrained()->cascadeOnDelete();
            $table->foreignId('producer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('organisation_id')->nullable()->constrained('organisations')->onDelete('cascade');
            $table->double('quantity');
            $table->integer('price');
            $table->enum('measure', ['kg', 'g', 'L', 'unité'])->nullable();
            $table->boolean('isbio')->default(true);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
