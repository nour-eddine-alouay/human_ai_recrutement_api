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
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('contrat');
            $table->string('domaine');
            $table->string('status');
            $table->text('description');
            $table->string('pays');
            $table->string('adresse');
            $table->text('profil');
            $table->json('competences');
            $table->string('max_salaire');
            $table->string('min_salaire');
            $table->boolean('hide_salaire');
            $table->string('mode_travail');
            $table->string('niveau_etude');
            $table->string('niveau_experience');
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
        Schema::dropIfExists('offres');
    }
};
