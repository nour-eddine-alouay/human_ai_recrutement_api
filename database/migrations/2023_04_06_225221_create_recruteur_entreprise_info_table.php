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
        Schema::create('recruteur_entreprise_information', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->nullable();
            $table->string("domaine");
            $table->string("pays");
            $table->string("site");
            $table->string("type")->nullable();
            $table->text("apercu")->nullable();
            $table->text("slogan")->nullable();
            $table->string("adresse")->nullable();
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
        Schema::dropIfExists('recruteur_entreprise_information');
    }
};
