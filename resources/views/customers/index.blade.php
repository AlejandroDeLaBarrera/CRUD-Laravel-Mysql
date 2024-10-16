
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Crear Nuevo Customer</a>
    <a href="{{ route('customers.pdf') }}" class="btn btn-primary mb-3">Descargar PDF</a><br>
    <a href="{{ route('customers.export.csv') }}" class="btn btn-primary">Exportar Clientes a CSV</a>
    {{-- <a href="{{ route('customers.pdf') }}"class="btn btn-primary mb-3">Descargar PDF de Customers</a> --}}


    @if($customers->isEmpty())
        <p>No hay customers registrados.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Hobbies</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->surname }}</td>
                        <td>
                            @foreach($customer->hobbies as $hobbie)
                                <span class="badge bg-secondary">{{ $hobbie->name }}</span>
                            @endforeach
                        </td>
                        {{-- <a href="{{ route('customers.downloadPDF', $customer->id) }}"class="btn btn-primary mb-3">Descargar PDF</a> --}}
                        <td>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
