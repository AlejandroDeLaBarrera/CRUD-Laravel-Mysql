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
        //





        // // Obtener todos los customers para actualizar los hobbies
        // $customers = Customer::all();

        // foreach ($customers as $customer) {
        //     // Hacer la solicitud al API externo para cada usuario
        //     $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$customer->id}");

        //     if ($response->successful()) {
        //         // Comprobar si la clave "hobbies" existe en la respuesta
        //         $responseData = $response->json();
        //         if (isset($responseData['hobbies'])) {
        //             // Obtener la lista de hobbies desde la API
        //             $hobbiesFromApi = $responseData['hobbies'];

        //             // Verificar si la respuesta de la API es un array y no está vacío
        //             if (is_array($hobbiesFromApi) && !empty($hobbiesFromApi)) {
        //                 // Asegurarse de que los hobbies no son arrays anidados y filtrar solo los strings
        //                 $hobbiesFromApi = array_filter($hobbiesFromApi, function($hobbie) {
        //                     return is_string($hobbie); // Mantener sólo las cadenas
        //                 });

        //                 // Limpiar los hobbies (eliminar espacios)
        //                 $hobbiesFromApi = array_map('trim', $hobbiesFromApi);

        //                 // Limitar la cantidad a 2 hobbies si hay más de 2
        //                 if (count($hobbiesFromApi) > 2) {
        //                     $hobbiesFromApi = array_slice($hobbiesFromApi, 0, 2);
        //                 }

        //                 // Si hay menos de 2 hobbies, rellenar con hobbies de la base de datos
        //                 if (count($hobbiesFromApi) < 2) {
        //                     $neededHobbies = 2 - count($hobbiesFromApi);
        //                     $extraHobbies = Hobbie::whereNotIn('name', $hobbiesFromApi)
        //                                         ->inRandomOrder()
        //                                         ->limit($neededHobbies)
        //                                         ->pluck('name')
        //                                         ->toArray();

        //                     // Agregar hobbies extra para completar los 2
        //                     $hobbiesFromApi = array_merge($hobbiesFromApi, $extraHobbies);
        //                 }

        //                 // Convertir los nombres de los hobbies en IDs de la base de datos
        //                 $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();

        //                 // Actualizar los hobbies del usuario
        //                 $customer->hobbies()->sync($hobbiesIds);

        //                 Log::info("Hobbies actualizados para el usuario {$customer->id}: " . implode(', ', $hobbiesFromApi));
        //             } else {
        //                 Log::warning("Los hobbies para el usuario {$customer->id} están vacíos o no son válidos");
        //             }
        //         } else {
        //             Log::warning("No se encontró la clave 'hobbies' en la respuesta de la API para el usuario {$customer->id}");
        //         }
        //     } else {
        //         Log::error("Error al obtener hobbies desde la API para el usuario {$customer->id}");
        //     }
        // }



        //REFACTORIZACÓN

        // Obtener todos los customers para actualizar los hobbies
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $responseData = $this->getHobbiesFromApi($customer->id);

            if ($responseData) {
                $hobbiesFromApi = $this->prepareHobbies($responseData['hobbies'] ?? []);
                $this->updateCustomerHobbies($customer, $hobbiesFromApi);
            } else {
                Log::error("Error al obtener hobbies desde la API para el usuario {$customer->id}");
            }
        }
    }

    /**
     * Obtener la respuesta de hobbies desde la API.
     *
     * @param  int  $userId
     * @return array|null
     */
    private function getHobbiesFromApi(int $userId)
    {
        $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$userId}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::warning("Solicitud fallida para el usuario {$userId}");
        return null;
    }

    /**
     * Preparar la lista de hobbies, asegurarse de que sean válidos y asignar 2 hobbies.
     *
     * @param  array  $hobbiesFromApi
     * @return array
     */
    private function prepareHobbies(array $hobbiesFromApi)
    {
        // Filtrar y limpiar los hobbies válidos
        $hobbiesFromApi = array_filter($hobbiesFromApi, fn($hobbie) => is_string($hobbie));
        $hobbiesFromApi = array_map('trim', $hobbiesFromApi);

        // Limitar la cantidad de hobbies a 2, y agregar adicionales si faltan
        return $this->limitAndFillHobbies($hobbiesFromApi, 2);
    }

    /**
     * Limitar la lista de hobbies a una cantidad dada, rellenar si faltan.
     *
     * @param  array  $hobbies
     * @param  int  $limit
     * @return array
     */
    private function limitAndFillHobbies(array $hobbies, int $limit)
    {
        // Limitar a 2 hobbies
        $hobbies = array_slice($hobbies, 0, $limit);

        // Rellenar si faltan hobbies
        if (count($hobbies) < $limit) {
            $neededHobbies = $limit - count($hobbies);
            $extraHobbies = Hobbie::whereNotIn('name', $hobbies)
                                ->inRandomOrder()
                                ->limit($neededHobbies)
                                ->pluck('name')
                                ->toArray();

            $hobbies = array_merge($hobbies, $extraHobbies);
        }

        return $hobbies;
    }

    /**
     * Actualizar los hobbies del customer.
     *
     * @param  Customer  $customer
     * @param  array  $hobbiesFromApi
     * @return void
     */
    private function updateCustomerHobbies(Customer $customer, array $hobbiesFromApi)
    {
        if (!empty($hobbiesFromApi)) {
            $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();
            $customer->hobbies()->sync($hobbiesIds);
            Log::info("Hobbies actualizados para el usuario {$customer->id}: " . implode(', ', $hobbiesFromApi));
        } else {
            Log::warning("No se encontraron hobbies válidos para el usuario {$customer->id}");
        }

    }
}
