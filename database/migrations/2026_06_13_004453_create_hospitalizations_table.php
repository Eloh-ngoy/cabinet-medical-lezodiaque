<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitalizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bed_id')->constrained();
            $table->dateTime('admission_date');
            $table->unsignedInteger('expected_duration')->nullable();
            $table->dateTime('discharge_date')->nullable();
            $table->string('status')->default('active');
            $table->text('admission_reason')->nullable();
            $table->text('discharge_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'admission_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitalizations');
    }
};
