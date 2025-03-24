<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        // Pegando a primeira credencial existente
        $credentialId = DB::table('credential')->value('id');

        // Verificação de segurança
        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar aos gêneros.');
            return;
        }

        DB::table('gender')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Masculino',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Feminino',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
