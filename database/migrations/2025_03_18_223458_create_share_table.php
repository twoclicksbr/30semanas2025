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
        Schema::create('share', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // ID do usuário autenticado
            $table->string('name'); // Nome do tema da sala
            $table->unsignedBigInteger('id_gender'); // Gênero associado à sala
            $table->unsignedBigInteger('id_church'); // Igreja associada à sala
            $table->unsignedBigInteger('id_type_participation'); // Tipo de participação na sala
            $table->string('link_meet')->nullable(); // Link do Google Meet para a sala
            $table->integer('active')->default(1); // Status ativo/inativo
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
            $table->foreign('id_gender')->references('id')->on('gender')->onDelete('cascade');
            $table->foreign('id_church')->references('id')->on('church')->onDelete('cascade');
            $table->foreign('id_type_participation')->references('id')->on('type_participation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share');
    }
};
