<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hobbie;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use PDF;
// use Barryvdh\DomPDF\Facade as PDF;


class CustomerController extends Controller
{

    public function generatePDF()
    {
        // Obtener todos los customers y sus hobbies
        $customers = Customer::with('hobbies')->get();

        // Generar vista en HTML y convertirla en PDF
        $pdf = Pdf::loadView('customers.pdf', ['customers' => $customers]);

        // Descargar el PDF o mostrarlo en pantalla
        return $pdf->download('lista_customers_hobbies.pdf');
    }

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
        return view('customers.create', compact('hobbies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'hobbies' => 'required|array|min:2|max:2', // Deben seleccionar exactamente 2 hobbies
            'hobbies.*' => 'exists:hobbies,id',
            // 'hobbies' => 'array', // Validar hobbies como un array
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'user_id' => auth()->id(),  //asigna user_id
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
            // 'hobbies' => 'array',
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

    public function show($id) {
    // Obtener el customer por id
    $customer = Customer::findOrFail($id);

    // Retornar una vista
    return view('customers.show', compact('customer'));
    }

    public function downloadPDF()
{
    $customers = Customer::with('hobbies')->get(); // Obtener los customers con sus hobbies

    // Usar DomPDF para cargar la vista y generar el PDF
    $pdf = PDF::loadView('customers.pdf', compact('customers'));

    // Descargar el PDF generado
    return $pdf->download('customers_list.pdf');
}
}


