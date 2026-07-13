<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('bed_number')->unique();
            $table->string('bed_type')->default('standard');
            $table->unsignedInteger('room_number')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
