@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Regiones</h5>
                        <span>Listado de Regiones</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('regiones.index') }}">Regiones</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-left">
                                        <h5>Listado de Regiones</h5>
                                    </div>
                                    <div class="float-right">
                                        <a href="{{ route('regiones.create') }}" class="btn btn-primary">Agregar Región</a>  
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="regiones" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Código Región</th>
                                                    <th>Nombre Región</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($regiones); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td>{{ $regiones[$i]->id }}</td>
                                                    <td>{{ $regiones[$i]->description }}</td>
                                                    <td>
                                                        <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateDepartment({{ $regiones[$i]->id }})">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteDepartment({{ $regiones[$i]->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- @section('content')
    <h1>Regiones</h1>
    <a href="{{ route('regiones.create') }}">Crear nueva región</a>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <ul>
        @foreach ($regiones as $region)
            <li>
                {{ $region->description }}
                <a href="{{ route('regiones.edit', $region) }}">Editar</a>
                <form action="{{ route('regiones.destroy', $region) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection --}}
