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
        Schema::create('celebration', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential'); // ID do usuário autenticado
            $table->string('name'); // Nome da celebração
            $table->date('dt_celebration'); // Data da celebração
            $table->string('link_youtube')->nullable(); // Link do YouTube
            $table->boolean('active')->default(1); // Define se está ativo
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('id_credential')->references('id')->on('credential')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('celebration');
    }
};
