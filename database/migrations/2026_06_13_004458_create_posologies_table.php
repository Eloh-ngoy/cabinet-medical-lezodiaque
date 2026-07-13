<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospitalization_id')->constrained()->cascadeOnDelete();
            $table->string('medication_name', 100);
            $table->string('dosage', 50);
            $table->string('frequency', 50);
            $table->string('duration', 50)->nullable();
            $table->text('instructions')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['hospitalization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posologies');
    }
};
