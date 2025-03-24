<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('person_restriction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credential');
            $table->unsignedBigInteger('id_person');
            $table->unsignedBigInteger('id_type_user');
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_restriction');
    }
};
