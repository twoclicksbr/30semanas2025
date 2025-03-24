<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $credentialId = DB::table('credential')->value('id');
        $churchId = DB::table('church')->value('id');

        if (!$credentialId || !$churchId) {
            dump('⚠️ É necessário ter pelo menos uma credencial e uma igreja cadastrada.');
            return;
        }

        DB::table('person')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'Alex Alves de Almeida',
                'cpf' => '355.644.858-07',
                'id_church' => 1,
                'birthdate' => '1990-05-15',
                'id_gender' => 1,
                'eklesia' => '115062',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
