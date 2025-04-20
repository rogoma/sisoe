@extends('layouts.app')

@section('content')
    <h1>Editar Región</h1>
    <form action="{{ route('regiones.update', $regione) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Descripción:</label>
        <input type="text" name="description" value="{{ old('description', $regione->description) }}">
        @error('description') <p>{{ $message }}</p> @enderror
        <button type="submit">Actualizar</button>
    </form>
@endsection
