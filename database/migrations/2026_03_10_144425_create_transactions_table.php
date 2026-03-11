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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_externe');
            $table->string('campay_id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->decimal('montant', 10,2);
            $table->string('monnaie');
            $table->string('numero');
            $table->enum('methode_paiement', ['Orange Money', 'Mobile Money']);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILLED', 'CANCELED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
