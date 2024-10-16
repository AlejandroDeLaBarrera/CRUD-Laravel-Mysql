<?php

namespace App\Jobs;


use App\Models\Customer;
use App\Models\Hobbie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateHobbiesJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        // //Obtener todos los customers para actualizar los hobbies
        // $customers = Customer::all();

        // foreach ($customers as $customer) {
        //     // Hacer la solicitud al API externo para cada usuario
        //     $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$customer->id}");

        //     if ($response->successful()) {
        //         // Obtener la lista de hobbies desde la API
        //         $hobbiesFromApi = $response->json()['hobbies'];

        //         // Convertir los nombres de los hobbies en IDs de la base de datos
        //         if (is_array($hobbiesFromApi)) {
        //             $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();

        //             // Actualizar los hobbies del usuario
        //             $customer->hobbies()->sync($hobbiesIds);
        //         }
        //     }
        // }

        //Obtener todos los customers para actualizar los hobbies
        $customers = Customer::all();

        foreach ($customers as $customer) {
            // Hacer la solicitud al API externo para cada usuario
            $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$customer->id}");

            if ($response->successful()) {
                // Verificar si la respuesta tiene la clave 'hobbies' antes de acceder
                $responseData = $response->json();

                if (array_key_exists('hobbies', $responseData)) {
                    // Obtener la lista de hobbies desde la API
                    $hobbiesFromApi = $responseData['hobbies'];

                    // Verificar si la respuesta de la API es un array y no está vacío
                    if (is_array($hobbiesFromApi) && !empty($hobbiesFromApi)) {

                        // Asegurarse de que los hobbies no son arrays anidados y están correctamente formateados
                        $hobbiesFromApi = array_filter($hobbiesFromApi, 'is_string'); // Filtrar solo cadenas
                        $hobbiesFromApi = array_map('trim', $hobbiesFromApi);         // Limpiar los hobbies (eliminar espacios)
                        $hobbiesFromApi = Arr::flatten($hobbiesFromApi);              // Aplanar cualquier posible array anidado

                        // Verificar nuevamente que no haya anidación después de la limpieza
                        if (count($hobbiesFromApi) === count(Arr::flatten($hobbiesFromApi))) {
                            // Convertir los nombres de los hobbies en IDs de la base de datos
                            $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();

                            // Actualizar los hobbies del usuario
                            $customer->hobbies()->sync($hobbiesIds);
                        } else {
                            // Log o manejar el error si los datos siguen incorrectos
                            Log::error("Error: Datos anidados encontrados para el customer ID: {$customer->id}");
                        }
                    } else {
                        Log::error("Error: La respuesta de hobbies no es un array válido o está vacío para el customer ID: {$customer->id}");
                    }
                } else {
                    // Log si la clave 'hobbies' no existe en la respuesta
                    Log::error("Error: Clave 'hobbies' no encontrada en la respuesta para el customer ID: {$customer->id}");
                }
            } else {
                // Log o manejar el caso de que la API no sea exitosa
                Log::error("Error: No se pudo obtener la respuesta de la API para el customer ID: {$customer->id}");
            }
        }

    }
}
