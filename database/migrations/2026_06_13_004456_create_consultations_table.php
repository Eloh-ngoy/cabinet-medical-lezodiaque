<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->dateTime('date_consultation');
            $table->text('motif');
            $table->text('diagnostic')->nullable();
            $table->text('traitement')->nullable();
            $table->decimal('prix', 10, 2);
            $table->text('ordonnance')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'date_consultation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
