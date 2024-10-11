@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Perfil</h1>

    <form method="POST" action="{{ route('profile.update', Auth::user()->id) }}">
        @csrf
        @method('PUT') <!--  método PUT para actualizaciones -->

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection

