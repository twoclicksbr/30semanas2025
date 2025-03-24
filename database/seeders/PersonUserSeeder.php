<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonUserSeeder extends Seeder
{
    public function run(): void
    {
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ É necessário ter pelo menos uma credencial e pessoas cadastradas.');
            return;
        }

        DB::table('person_user')->insert([
            [
                'id_credential' => $credentialId,
                'id_person' => 1,
                'email' => 'alex@twoclicks.com.br',
                'password' => Hash::make('Jbxz45n40t3m$'), // Criptografada
                'verification_code' => '123456',
                'email_verified' => true,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
