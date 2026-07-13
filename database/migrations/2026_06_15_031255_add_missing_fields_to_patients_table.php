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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('numero_unique')->unique()->after('id');
            $table->text('adresse')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->string('photo')->nullable();
            $table->json('allergies')->nullable();
            $table->json('antecedents')->nullable();
            $table->json('maladies_chroniques')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'numero_unique',
                'adresse',
                'contact_urgence_nom',
                'contact_urgence_telephone',
                'photo',
                'allergies',
                'antecedents',
                'maladies_chroniques'
            ]);
        });
    }
};
