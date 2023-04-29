<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidat_personal_information', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('civilite', ['Monsieur', 'Madame'])->nullable();
            $table->enum('etat_civilite', ['Célibataire', 'Marié'])->nullable();
            $table->date("date_naissance")->nullable();
            $table->string("telephone")->nullable();
            $table->string("pays");
            $table->string("ville")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("skype")->nullable();
            $table->boolean("isCompleted");
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
        Schema::dropIfExists('candidat_personal_information');
    }
};
