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
        Schema::create('postules', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['en attente', 'acceptée', 'refusée', 'offre non disponible']);
            $table->text('lettre');
            $table->unsignedBigInteger('candidat_id');
            $table->unsignedBigInteger('offre_id');
            $table->foreign('candidat_id')->references('id')->on('users');
            $table->foreign('offre_id')->references('id')->on('offres');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postules');
    }
};
