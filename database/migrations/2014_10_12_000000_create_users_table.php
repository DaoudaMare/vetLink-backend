<?php

// use App\Enums\TypeSecteurActiviteEnum;
// use App\Enums\TypeUserEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserType;
use App\Models\Organization;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigIncrements
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique()->nullable();
            $table->string('tel1');
            $table->string('tel2')->nullable();
            $table->string('address')->nullable();
            $table->foreignIdFor(UserType::class)->constrained('user_types')->cascadeOnDelete();
            $table->string('password');
            $table->string('profile_photo_path')->nullable();
            $table->foreignIdFor(Organization::class)->nullable()->constrained('organisations')->cascadeOnDelete();
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
