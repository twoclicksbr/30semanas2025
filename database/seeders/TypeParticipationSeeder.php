<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeParticipationSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o ID da primeira credencial cadastrada
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar aos tipos de participação.');
            return;
        }

        DB::table('type_participation')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Online',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Presencial',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
