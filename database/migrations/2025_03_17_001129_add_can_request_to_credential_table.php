<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('credential', function (Blueprint $table) {
            $table->boolean('can_request')->default(0)->after('token'); // Permissão para requisições
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credential', function (Blueprint $table) {
            $table->dropColumn('can_request');
        });
    }
};
