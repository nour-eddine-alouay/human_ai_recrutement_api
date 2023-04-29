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
        Schema::create('valeurs_humaines', function (Blueprint $table) {
            $table->id();
            $table->boolean('agilite')->default(false);
            $table->boolean('apprentissage_continu')->default(false);
            $table->boolean('authenticite')->default(false);
            $table->boolean('efficacite')->default(false);
            $table->boolean('empathie')->default(false);
            $table->boolean('engagement_envers_client')->default(false);
            $table->boolean('excellence')->default(false);
            $table->boolean('humilite')->default(false);
            $table->boolean('innovation')->default(false);
            $table->boolean('integrite')->default(false);
            $table->boolean('orientationeclient')->default(false);
            $table->boolean('orientation_resultats')->default(false);
            $table->boolean('ouverture_esprit')->default(false);
            $table->boolean('collaboration_travail_equipe')->default(false);
            $table->boolean('communication')->default(false);
            $table->boolean('confiance')->default(false);
            $table->boolean('confiance_en_soi')->default(false);
            $table->boolean('confidentialite')->default(false);
            $table->boolean('creativite')->default(false);
            $table->boolean('croissance')->default(false);
            $table->boolean('croissance_personnelle')->default(false);
            $table->boolean('culture_apprentissage')->default(false);
            $table->boolean('diversite_inclusion')->default(false);
            $table->boolean('durabilite')->default(false);
            $table->boolean('flexibilite')->default(false);
            $table->boolean('flexibilite_horaire')->default(false);
            $table->boolean('gestion_temps')->default(false);
            $table->boolean('loyaute')->default(false);
            $table->boolean('passion')->default(false);
            $table->boolean('qualite')->default(false);
            $table->boolean('qualite_vie_travail')->default(false);
            $table->boolean('reactivite')->default(false);
            $table->boolean('reconnaissance')->default(false);
            $table->boolean('resilience')->default(false);
            $table->boolean('resolution_problemes')->default(false);
            $table->boolean('responsabilite_environnementale')->default(false);
            $table->boolean('responsabilite_personnelle')->default(false);
            $table->boolean('responsabilite_sociale')->default(false);
            $table->boolean('securite')->default(false);
            $table->boolean('securite_financiere')->default(false);
            $table->boolean('transparence')->default(false);
            $table->boolean('volonte_prendre_risques')->default(false);
            $table->boolean('respect')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valeurs_humaines');
    }
};
