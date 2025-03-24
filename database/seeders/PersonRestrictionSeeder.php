<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonRestrictionSeeder extends Seeder
{
    public function run(): void
    {
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ É necessário ter pelo menos uma credencial, um tipo de usuário e pessoas cadastradas.');
            return;
        }

        DB::table('person_restriction')->insert([
            [
                'id_credential' => $credentialId,
                'id_person' => 1,
                'id_type_user' => 1,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
