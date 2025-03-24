<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o ID da primeira credencial cadastrada
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar aos grupos.');
            return;
        }

        DB::table('group')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Igreja da Cidade',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Rede Inspire',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Outras',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
