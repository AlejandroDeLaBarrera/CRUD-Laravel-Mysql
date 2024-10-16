<?php

//namespace Database\Seeders; -->

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
// use App\Models\User;

// class UserSeeder extends Seeder {

//     public function run() {
//         User::truncate();

//         User::create(['id'=> 1, 'name'=> 'User1', 'email' => 'user1@example.com', 'password' => bcrypt('password')]);
//         User::create(['id'=> 2, 'name'=> 'User2', 'email' => 'user2@example.com', 'password' => bcrypt('password')]);
//         User::create(['id'=> 3, 'name'=> 'User3', 'email' => 'user3@example.com', 'password' => bcrypt('password')]);
//         User::create(['id'=> 4, 'name'=> 'User4', 'email' => 'user4@example.com', 'password' => bcrypt('password')]);
//     }
// }


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'status_id' => 1,
            'profile_id' => 1,
            'password' => Hash::make('password'),
        ]);

        // Crear otros 4 usuarios (del 1 al 4 como se pide)
        User::create([
            'name' => 'Usuario1',
            'email' => 'user1@example.com',
            'status_id' => 1,
            'profile_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Usuario2',
            'email' => 'user2@example.com',
            'status_id' => 1,
            'profile_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Usuario3',
            'email' => 'user3@example.com',
            'status_id' => 1,
            'profile_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Usuario4',
            'email' => 'user4@example.com',
            'status_id' => 1,
            'profile_id' => 2,
            'password' => Hash::make('password'),
        ]);
    }
}



// namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;

// class UserSeeder extends Seeder
// {
//     public function run()
//     {
//         // Crear 4 usuarios con IDs fijos
//        // User::truncate(); // Limpiar la tabla primero
//         User::insert([
//             ['id' => 1, 'name' => 'Usuario 1', 'email' => 'usuario1@example.com', 'password' => Hash::make('password')],
//             ['id' => 2, 'name' => 'Usuario 2', 'email' => 'usuario2@example.com', 'password' => Hash::make('password')],
//             ['id' => 3, 'name' => 'Usuario 3', 'email' => 'usuario3@example.com', 'password' => Hash::make('password')],
//             ['id' => 4, 'name' => 'Usuario 4', 'email' => 'usuario4@example.com', 'password' => Hash::make('password')],
//         ]);
//     }
// }
