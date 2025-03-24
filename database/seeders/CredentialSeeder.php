<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CredentialSeeder extends Seeder
{
    public function run(): void
    {
        // Token baseado em senha padrão
        $token = hash('sha256', 'Jbxz45n40t3m$');

        DB::table('credential')->insert([
            'username' => 'twoclicks',
            'token' => $token,
            'can_request' => 1,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mostra o token no terminal
        dump("Token gerado para 'twoclicks': " . $token);
    }
}
