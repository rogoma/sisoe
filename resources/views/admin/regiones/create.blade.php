@extends('layouts.app')

@section('content')
    <h1>Crear Región</h1>
    <form action="{{ route('regiones.store') }}" method="POST">
        @csrf
        <label>Descripción:</label>
        <input type="text" name="description" value="{{ old('description') }}">
        @error('description') <p>{{ $message }}</p> @enderror
        <button type="submit">Guardar</button>
    </form>
@endsection
