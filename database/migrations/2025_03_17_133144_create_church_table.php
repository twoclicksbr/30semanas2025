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
        Schema::create('church', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // Deve ser unsigned para corresponder ao tipo de ID da tabela credential
            $table->string('name')->unique();
            $table->integer('active')->default(1);
            $table->timestamps();

            // Criando a chave estrangeira
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church');
    }
};
