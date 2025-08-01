<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('message_type')->default('text');
            $table->string('file_url')->nullable();
            $table->string('file_name')->nullable();
            $table->text('body')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Update existing NULL values to an empty string before changing the column to NOT NULL
            DB::table('messages')->whereNull('body')->update(['body' => '']);

            $table->dropColumn(['message_type', 'file_url', 'file_name']);
            $table->text('body')->nullable(false)->change();
        });
    }
};