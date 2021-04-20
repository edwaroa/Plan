<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\GeneralSeed;
use Database\Seeders\UsuariosSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsuariosSeeder::class);
        $this->call(GeneralSeed::class);
    }
}
