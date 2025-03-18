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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // Referência ao usuário autenticado
            $table->unsignedBigInteger('id_parent'); // ID do registro vinculado
            $table->string('route'); // Nome da entidade vinculada (ex: "church", "person")
            $table->unsignedBigInteger('id_type_contact'); // Relaciona com type_contact
            $table->string('value'); // Armazena telefone, e-mail, etc. SEM formatação
            $table->boolean('active')->default(1); // Ativo ou inativo
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
            $table->foreign('id_type_contact')->references('id')->on('type_contact')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
