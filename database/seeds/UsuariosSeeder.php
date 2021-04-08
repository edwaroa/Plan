<?php

namespace Database\Seeders;

use App\Rol;
use App\User;
use FontLib\Table\Type\name;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'nombre' => 'Decano'
        ]);

        Rol::create([
            'nombre' => 'Administrativo'
        ]);

        Rol::create([
            'nombre' => 'Docente'
        ]);

        $user = User::create([
            'tipo_documento' => 'Cedula de Ciudadanía',
            'documento' => '1098811453',
            'nombre' => 'Jesús',
            'apellido' => 'Arévalo',
            'email' => 'decano@udi.edu.co',
            'password' => Hash::make('123456789'),
            'id_rol' => 1,
            'imagen' => 'Imagen'
        ]);

        $user2 = User::create([
            'tipo_documento' => 'Cedula de Ciudadanía',
            'documento' => '1098823453',
            'nombre' => 'Edwar',
            'apellido' => 'Roa',
            'email' => 'administrativo@udi.edu.co',
            'password' => Hash::make('123456789'),
            'id_rol' => 2,
            'imagen' => 'Imagen'
        ]);

        $user3 = User::create([
            'tipo_documento' => 'Cedula de Ciudadanía',
            'documento' => '109882453',
            'nombre' => 'Pedro',
            'apellido' => 'Casas',
            'email' => 'docente@udi.edu.co',
            'password' => Hash::make('123456789'),
            'id_rol' => 3,
            'imagen' => 'Imagen'
        ]);
    }
}
