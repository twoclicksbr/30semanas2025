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
        Schema::create('type_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // ID do usuário autenticado
            $table->string('name')->unique(); // Nome do tipo de contato (WhatsApp, Email, etc.)
            $table->string('input_type'); // Tipo de entrada (number, email, text)
            $table->json('mask')->nullable(); // Permite armazenar múltiplas máscaras como JSON
            $table->integer('active')->default(1); // Status ativo/inativo
            $table->timestamps();

            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_contact');
    }
};
