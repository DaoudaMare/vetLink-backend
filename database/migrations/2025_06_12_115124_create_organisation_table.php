<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BusinessSector;
use App\Models\OrganizationType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('adresse');
            $table->foreignIdFor(BusinessSector::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(OrganizationType::class, 'organization_type_id')->constrained('organisation_types')->cascadeOnDelete();
            $table->string('email')->unique()->nullable();
            $table->string('tel1');
            $table->string('tel2')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
