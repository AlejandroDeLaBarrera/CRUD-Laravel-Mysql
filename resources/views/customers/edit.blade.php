@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Customer</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="surname" class="form-label">Apellido</label>
            <input type="text" name="surname" id="surname" class="form-control" value="{{ old('surname', $customer->surname) }}" required>
        </div>

        <div class="mb-3">
            <label for="hobbies" class="form-label">Hobbies</label>
            <select name="hobbies[]" id="hobbies" class="form-control" multiple required>
                @foreach($hobbies as $hobbie)
                    <option value="{{ $hobbie->id }}"
                        @selected(in_array($hobbie->id, old('hobbies', $customer->hobbies->pluck('id')->toArray() ?? [])))
                    >
                        {{ $hobbie->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
