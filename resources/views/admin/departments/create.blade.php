@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Departamentos Geográficos</h5>
                        <span>Agregar Departamento Geográfico</span>
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
                            <a href="{{ route('departments.index') }}">Departamentos Geográficos</a>
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
                                    <h5>Agregar Departamento Geográfico</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('departments.store') }}">
                                        @csrf

                                        {{-- <div class="form-group row @error('coddpto') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código Departamento Geográfico</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="coddpto" name="coddpto" value="{{ old('coddpto') }}" class="form-control @error('coddpto') form-control-danger @enderror" value="{{ old('coddpto') }}">
                                                @error('coddpto')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="form-group row @error('regiones') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Región Geográfica</label>
                                            <div class="col-sm-10">
                                                <select id="regiones" name="regiones" class="form-control">
                                                    <option value="">--- Seleccionar Región ---</option>
                                                    @foreach ($regiones as $region)
                                                        <option value="{{ $region->id }}" @if ($region->id == old('regiones')) selected @endif>{{ $region->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('regiones')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nombre Departamento Geográfico</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary m-b-0">Guardar</button>
                                            </div>
                                        </div>

                                    </form>
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    $('#regiones').select2();    

});
</script>
@endpush