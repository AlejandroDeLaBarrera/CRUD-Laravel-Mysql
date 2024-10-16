<?php

//namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use App\Models\User;
// use App\Models\Customer;
// use App\Models\Hobbie;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;

// class DatabaseSeeder extends Seeder
// {
//     public function run()
//     {
//         // Crear un usuario administrador
//         $admin = User::create([
//             'email' => 'admin@example.com',
//             'status_id' => 1,
//             'profile_id' => 1,
//             'password' => Hash::make('password'),
//         ]);

//         // Crear hobbies
//         $hobbie1 = Hobbie::create(['name' => 'Fútbol']);
//         $hobbie2 = Hobbie::create(['name' => 'Leer']);
//         $hobbie3 = Hobbie::create(['name' => 'Pintura']);

//         // Crear un customer y asociar hobbies
//         $customer = Customer::create([
//             'name' => 'Juan',
//             'surname' => 'Pérez',
//             'user_id' => $admin->id,
//         ]);

//         $customer->hobbies()->attach([$hobbie1->id, $hobbie2->id]);
//     }
// }



// namespace Database\Seeders;

// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     public function run()
//     {
//         // Llamar a los seeders individuales
//         $this->call(UserSeeder::class);
//         $this->call(HobbiesSeeder::class);

//     }
// }




// namespace Database\Seeders;

// use App\Models\Customer;
// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     public function run()
//     {
//         // Llamar a los seeders individuales
//         $this->call(UserSeeder::class);
//         $this->call(HobbiesSeeder::class);

//         // Crear algunos customers
//         $customer1 = Customer::create([
//             'name' => 'Juan',
//             'surname' => 'Pérez',
//             'user_id' => 1, // Asignar un usuario existente
//         ]);

//         $customer2 = Customer::create([
//             'name' => 'Maria',
//             'surname' => 'López',
//             'user_id' => 1, // Asignar un usuario existente
//         ]);

//         // Asociar hobbies a los customers
//         $customer1->hobbies()->attach([1, 2]); // Hobbies con IDs 1 y 2
//         $customer2->hobbies()->attach([3, 4]); // Hobbies con IDs 3 y 4
//     }
// }

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Hobbie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Llamar a los seeders individuales
        $this->call(UserSeeder::class); // Asegúrate de que aquí se crean usuarios
        $this->call(HobbiesSeeder::class); // Asegúrate de que aquí se crean hobbies

        // Obtener el primer usuario que se ha creado
        $user = User::first(); // Asumiendo que UserSeeder crea al menos un usuario

        // Verificar que se haya creado un usuario
        if ($user) {
            // Crear algunos customers
            $customer1 = Customer::create([
                'name' => 'Juan',
                'surname' => 'Pérez',
                'user_id' => $user->id, // Asignar un usuario existente
            ]);

            $customer2 = Customer::create([
                'name' => 'Maria',
                'surname' => 'López',
                'user_id' => $user->id, // Asignar un usuario existente
            ]);

            // Asociar hobbies a los customers
            $customer1->hobbies()->attach([1, 2]); // Hobbies con IDs 1 y 2
            $customer2->hobbies()->attach([3, 4]); // Hobbies con IDs 3 y 4
        } else {
            // Manejo de error: no se creó ningún usuario
            echo "No se encontraron usuarios. Asegúrate de que el UserSeeder se ejecutó correctamente.\n";
        }
    }
}

