<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CredentialSeeder::class,
            GenderSeeder::class,
            TypeUserSeeder::class,
            GroupSeeder::class,
            ChurchSeeder::class,
            TypeContactSeeder::class,
            TypeParticipationSeeder::class,
            PersonSeeder::class,
            PersonUserSeeder::class,
            PersonRestrictionSeeder::class,
        ]);
    }

}
