<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('telephone', 20);
            $table->string('email', 100)->unique();
            $table->date('date_naissance');
            $table->enum('sexe', ['homme', 'femme']);
            $table->string('groupe_sanguin', 5)->nullable();
            $table->enum('statut_interne_externe', ['interne', 'externe'])->default('externe');
            $table->text('traitement_passe')->nullable();
            $table->timestamps();

            $table->index(['nom', 'prenom']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
