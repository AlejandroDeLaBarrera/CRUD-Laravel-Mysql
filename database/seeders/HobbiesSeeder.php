<?php

//namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
//use Illuminate\Database\Seeder;
//use App\Models\Hobbie;

// class HobbiesSeeder extends Seeder {

//     public function run() {
//         $hobbies = ['teatro', 'cine', 'fútbol', 'pádel', 'baile', 'tocar instrumento', 'senderismo', 'lectura', 'yoga', 'manualidades'];

//         foreach ($hobbies as $hobbie) {
//             Hobbie::create(['name' => $hobbie]);
//         }
//     }
// }


namespace Database\Seeders;

use App\Models\Hobbie;
use Illuminate\Database\Seeder;

class HobbiesSeeder extends Seeder
{
    public function run()
    {
        //Hobbie::truncate(); // Limpiar la tabla de hobbies
        Hobbie::insert([
            ['name' => 'teatro'],
            ['name' => 'cine'],
            ['name' => 'fútbol'],
            ['name' => 'pádel'],
            ['name' => 'baile'],
            ['name' => 'tocar instrumento'],
            ['name' => 'senderismo'],
            ['name' => 'lectura'],
            ['name' => 'yoga'],
            ['name' => 'manualidades'],
        ]);
    }
}
