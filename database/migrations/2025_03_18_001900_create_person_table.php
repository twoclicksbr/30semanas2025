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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // Referência ao usuário autenticado
            $table->string('name');
            $table->string('cpf', 14)->unique(); // CPF com máscara
            $table->unsignedBigInteger('id_church'); // Igreja vinculada
            $table->date('birthdate')->nullable(); // Data de nascimento
            $table->unsignedBigInteger('id_gender')->nullable(); // Gênero
            $table->unsignedBigInteger('id_group')->nullable(); // Gênero
            $table->string('eklesia')->nullable(); // Número de registro da pessoa na igreja
            $table->integer('active')->default(1); // Define status ativo/inativo
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
            $table->foreign('id_church')->references('id')->on('church')->onDelete('cascade');
            $table->foreign('id_gender')->references('id')->on('gender')->onDelete('set null');
            $table->foreign('id_group')->references('id')->on('group')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};
