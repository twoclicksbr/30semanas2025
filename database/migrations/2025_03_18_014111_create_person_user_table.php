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
        Schema::create('person_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // Referência ao usuário autenticado
            $table->unsignedBigInteger('id_person')->unique(); // Garante que cada pessoa tenha um único usuário
            $table->string('email')->unique(); // E-mail do usuário
            $table->string('password'); // Senha criptografada
            $table->string('verification_code', 6)->nullable(); // Código de verificação (6 dígitos)
            $table->boolean('email_verified')->default(false); // E-mail confirmado ou não
            $table->integer('active')->default(1); // 1 = Ativo, 0 = Inativo
            $table->timestamps();

            // Chave estrangeira para garantir integridade
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
            $table->foreign('id_person')->references('id')->on('person')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_user');
    }
};
