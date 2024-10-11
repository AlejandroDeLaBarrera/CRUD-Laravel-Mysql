<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Customer;
use App\Models\Hobbie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear un usuario administrador
        $admin = User::create([
            'email' => 'admin@example.com',
            'status_id' => 1,
            'profile_id' => 1,
            'password' => Hash::make('password'),
        ]);

        // Crear hobbies
        $hobbie1 = Hobbie::create(['name' => 'Fútbol']);
        $hobbie2 = Hobbie::create(['name' => 'Leer']);
        $hobbie3 = Hobbie::create(['name' => 'Pintura']);

        // Crear un customer y asociar hobbies
        $customer = Customer::create([
            'name' => 'Juan',
            'surname' => 'Pérez',
            'user_id' => $admin->id,
        ]);

        $customer->hobbies()->attach([$hobbie1->id, $hobbie2->id]);
    }
}
