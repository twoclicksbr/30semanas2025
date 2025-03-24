<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeContactSeeder extends Seeder
{
    public function run(): void
    {
        // Pegando o ID da primeira credencial existente
        $credentialId = DB::table('credential')->value('id');

        if (!$credentialId) {
            dump('⚠️ Nenhuma credencial encontrada para associar aos tipos de contato.');
            return;
        }

        DB::table('type_contact')->insert([
            [
                'id_credential' => $credentialId,
                'name' => 'WhatsApp',
                'input_type' => 'numer',
                'mask' => json_encode(['(00) 00000-0000, (00) 0000-0000']),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'E-mail',
                'input_type' => 'email',
                'mask' => null,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'Telefone Fixo',
                'input_type' => 'numer',
                'mask' => json_encode(['(00) 0000-0000']),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_credential' => $credentialId,
                'name' => 'WhatsApp Internacional',
                'input_type' => 'text',
                'mask' => json_encode(['']),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
