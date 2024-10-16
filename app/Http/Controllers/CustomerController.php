<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hobbie;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;  //con Http en lugar de Guzzle es más simple
// use PDF;
// use Barryvdh\DomPDF\Facade as PDF;


// class CustomerController extends Controller
// {

//     public function generatePDF()
//     {
//         // Obtener todos los customers y sus hobbies
//         $customers = Customer::with('hobbies')->get();

//         // Generar vista en HTML y convertirla en PDF
//         $pdf = Pdf::loadView('customers.pdf', ['customers' => $customers]);

//         // Descargar el PDF o mostrarlo en pantalla
//         return $pdf->download('lista_customers_hobbies.pdf');
//     }

//     // Proteger con middleware para permitir acceso sólo a administradores
//     public function __construct()
//     {
//         $this->middleware('admin'); // Hay que definir este middleware
//     }

//     public function index()
//     {
//         $customers = Customer::with('hobbies')->get();
//         return view('customers.index', compact('customers'));
//     }

//     public function create()
//     {
//         $hobbies = Hobbie::all(); // Obtener todos los hobbies
//         return view('customers.create', compact('hobbies'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'surname' => 'required|string|max:255',
//             'hobbies' => 'required|array|min:2|max:2', // Deben seleccionar exactamente 2 hobbies
//             'hobbies.*' => 'exists:hobbies,id',
//             // 'hobbies' => 'array', // Validar hobbies como un array
//         ]);

//         $customer = Customer::create([
//             'name' => $request->name,
//             'surname' => $request->surname,
//             'user_id' => auth()->id(),  //asigna user_id
//         ]);
//         $customer->hobbies()->sync($request->hobbies); // Asociar hobbies

//         return redirect()->route('customers.index');
//     }

//     public function edit(Customer $customer)
//     {
//         $hobbies = Hobbie::all();
//         return view('customers.edit', compact('customer', 'hobbies'));
//     }

//     public function update(Request $request, Customer $customer)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'surname' => 'required|string|max:255',
//             'hobbies' => 'required|array|min:2|max:2', // Deben seleccionar exactamente 2 hobbies
//             'hobbies.*' => 'exists:hobbies,id',
//             // 'hobbies' => 'array',
//         ]);

//         $customer->update($request->only(['name', 'surname']));
//         $customer->hobbies()->sync($request->hobbies);

//         return redirect()->route('customers.index');
//     }

//     public function destroy(Customer $customer)
//     {
//         $customer->delete();
//         return redirect()->route('customers.index');
//     }

//     public function show($id) {
//     // Obtener el customer por id
//     $customer = Customer::findOrFail($id);

//     // Retornar una vista
//     return view('customers.show', compact('customer'));
//     }

//     public function downloadPDF() {
//     $customers = Customer::with('hobbies')->get(); // Obtener los customers con sus hobbies

//     // Usar DomPDF para cargar la vista y generar el PDF
//     $pdf = PDF::loadView('customers.pdf', compact('customers'));

//     // Descargar el PDF generado
//     return $pdf->download('customers_list.pdf');
//     }

//     //Método para conectar con la API (hacer solicitud HTTP al endpoint de la API y actualizar los hobbies del usuario)
//     public function updateHobbiesFromApi($userId) {
//         // Hacer la solicitud al API externo
//         $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$userId}");

//         // Verificar si la solicitud fue ok
//         if ($response->successful()) {
//             $hobbiesFromApi = $response->json()['hobbies']; // Obtener la lista de hobbies desde la API

//             // Buscar el usuario por ID
//             $customer = Customer::findOrFail($userId);

//             // Convertir los nombres de los hobbies en IDs de la base de datos
//             $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();

//             // Actualizar los hobbies del usuario
//             $customer->hobbies()->sync($hobbiesIds);

//             return response()->json(['message' => 'Hobbies actualizados con éxito']);
//         }

//         return response()->json(['message' => 'Error al obtener los hobbies del servicio externo'], 500);
//     }
// }

class CustomerController extends Controller
{
    // Proteger con middleware para permitir acceso sólo a administradores
    public function __construct()
    {
        $this->middleware('admin'); // Hay que definir este middleware
    }

    public function index()
    {
        $customers = Customer::with('hobbies')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $hobbies = Hobbie::all(); // Obtener todos los hobbies
        $customer = new Customer(); // Inicializar un nuevo Customer
        return view('customers.create', compact('hobbies', 'customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'hobbies' => 'required|array|min:2|max:2', // Deben seleccionar exactamente 2 hobbies
            'hobbies.*' => 'exists:hobbies,id',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'user_id' => auth()->id(),  // Asigna user_id
        ]);
        $customer->hobbies()->sync($request->hobbies); // Asociar hobbies

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        $hobbies = Hobbie::all();
        return view('customers.edit', compact('customer', 'hobbies'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'hobbies' => 'required|array|min:2|max:2', // Deben seleccionar exactamente 2 hobbies
            'hobbies.*' => 'exists:hobbies,id',
        ]);

        $customer->update($request->only(['name', 'surname']));
        $customer->hobbies()->sync($request->hobbies);

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index');
    }

    public function show($id)
    {
        // Obtener el customer por id
        $customer = Customer::findOrFail($id);

        // Retornar una vista
        return view('customers.show', compact('customer'));
    }

    public function generatePDF()
    {
        // Obtener todos los customers y sus hobbies
        $customers = Customer::with('hobbies')->get();

        // Generar vista en HTML y convertirla en PDF
        $pdf = PDF::loadView('customers.pdf', ['customers' => $customers]);

        // Descargar el PDF o mostrarlo en pantalla
        return $pdf->download('lista_customers_hobbies.pdf');
    }

    // Método para conectar con la API (hacer solicitud HTTP al endpoint de la API y actualizar los hobbies del usuario)
    // public function updateHobbiesFromApi($userId)
    // {
    //     // Hacer la solicitud al API externo
    //     $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$userId}");

    //     // Verificar si la solicitud fue ok
    //     if ($response->successful()) {
    //         $hobbiesFromApi = $response->json()['hobbies']; // Obtener la lista de hobbies desde la API

    //         // Buscar el usuario por ID
    //         $customer = Customer::findOrFail($userId);

    //         // Convertir los nombres de los hobbies en IDs de la base de datos
    //         $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();

    //         // Actualizar los hobbies del usuario
    //         $customer->hobbies()->sync($hobbiesIds);

    //         return response()->json(['message' => 'Hobbies actualizados con éxito']);
    //     }

    //     return response()->json(['message' => 'Error al obtener los hobbies del servicio externo'], 500);
    // }
    public function updateHobbiesFromApi($userId)
    {
        // Hacer la solicitud al API externo
        $response = Http::get("https://cratererp.bloonde.es/testapi/index.php?user_id={$userId}");

        // Verificar si la solicitud fue ok
        if ($response->successful()) {
            $hobbiesFromApi = $response->json()['hobbies']; // Obtener la lista de hobbies desde la API

            // Debug: Revisar la estructura de los datos
            // dd($hobbiesFromApi);

            // Buscar el usuario por ID
            $customer = Customer::findOrFail($userId);

            // Convertir los nombres de los hobbies en IDs de la base de datos
            if (is_array($hobbiesFromApi) && !empty($hobbiesFromApi)) {
                // Si es un array simple
                if (is_string($hobbiesFromApi[0])) {
                    $hobbiesIds = Hobbie::whereIn('name', $hobbiesFromApi)->pluck('id')->toArray();
                }
                // Si es un array de arrays
                elseif (is_array($hobbiesFromApi[0]) && isset($hobbiesFromApi[0]['name'])) {
                    $hobbiesIds = Hobbie::whereIn('name', collect($hobbiesFromApi)->pluck('name'))->pluck('id')->toArray();
                } else {
                    return response()->json(['message' => 'Formato de datos no válido'], 400);
                }
            } else {
                return response()->json(['message' => 'No se encontraron hobbies en la respuesta'], 400);
            }

            // Actualizar los hobbies del usuario
            $customer->hobbies()->sync($hobbiesIds);

            return response()->json(['message' => 'Hobbies actualizados con éxito']);
        }

        return response()->json(['message' => 'Error al obtener los hobbies del servicio externo'], 500);
    }

    //CSV

    public function exportToCsv()
    {
        $customers = Customer::with('hobbies')->get(); // Obtener los customers con hobbies

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=customers_hobbies.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Nombre', 'Apellido', 'Hobbies'];

        $callback = function () use ($customers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Agregar los encabezados

            foreach ($customers as $customer) {
                $hobbies = $customer->hobbies->pluck('name')->implode(', '); // Obtener hobbies como una lista separada por comas
                fputcsv($file, [$customer->name, $customer->surname, $hobbies]);
            }

            fclose($file);
        };

        // return new StreamedResponse($callback, 200, $headers);
        return new \Symfony\Component\HttpFoundation\StreamedResponse($callback, 200, $headers);
    }


}
