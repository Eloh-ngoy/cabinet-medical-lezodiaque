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
            $table->string('document_id')->unique(); // Format: DOC-2026-000001
            $table->string('type'); // dossier_medical, resume_medical, ordonnance, etc.
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('consultation_id')->nullable();
            $table->unsignedBigInteger('user_id'); // Utilisateur qui a généré le document
            $table->string('user_role'); // Rôle de l'utilisateur au moment de la génération
            $table->string('ip_address');
            $table->timestamp('generated_at');
            $table->string('file_path')->nullable();
            $table->string('status')->default('valid'); // valid, annulé
            $table->text('metadata')->nullable(); // JSON avec informations supplémentaires
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
