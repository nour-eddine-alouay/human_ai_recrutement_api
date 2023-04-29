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
        Schema::create('candidat_professional_information', function (Blueprint $table) {
            $table->id();
            $table->string("domaine")->nullable();
            $table->string("profession");
            $table->json('competences')->nullable();;
            $table->text("apercu")->nullable();
            $table->string("niveau_etude")->nullable();
            $table->string("niveau_experience")->nullable();
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
        Schema::dropIfExists('candidat_professional_information');
    }
};
