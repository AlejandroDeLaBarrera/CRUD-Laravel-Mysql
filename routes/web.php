<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

//Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

//Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

//Registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

 //editar perfil
Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
 //actualizar perfil
Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('customers', CustomerController::class);

});

Route::get('/customers/pdf', [CustomerController::class, 'generatePDF'])->name('customers.pdf');
// Route::get('customers/{id}/pdf', [CustomerController::class, 'downloadPDF'])->name('customers.downloadPDF');
Route::get('customers/pdf', [CustomerController::class, 'downloadPDF'])->name('customers.pdf');


// Endpoint que devuelve los nombres de los customers relacionados con un hobbie
Route::get('hobbies/{id}/customers', function($id) {
    $customers = Customer::whereHas('hobbies', function ($query) use ($id) {
        $query->where('hobbies.id', $id);
    })->get(['name', 'surname']);

    return response()->json($customers);
});

// require __DIR__.'/auth.php';

//Ruta para actualizar los hobbies desde la API
Route::get('customers/update-hobbies/{userId}', [CustomerController::class, 'updateHobbiesFromApi']);

//CSV
Route::get('/customers/export-csv', [CustomerController::class, 'exportToCsv'])->name('customers.export.csv');
