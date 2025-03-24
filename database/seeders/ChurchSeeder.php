<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurchSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o ID da primeira credencial cadastrada
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar às igrejas.');
            return;
        }

        DB::table('church')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Igreja da Cidade - São José dos Campos - SP',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
