<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeUserSeeder extends Seeder
{
    public function run(): void
    {
        // Obtém o ID da primeira credencial existente
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar aos tipos de usuário.');
            return;
        }

        DB::table('type_user')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Adm-Global',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Adm-Igreja',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Líder',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
