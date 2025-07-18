@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Localidades</h5>
                        <span>Editar Localidad</span>
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
                            <a href="{{ route('districts.index') }}">Localidades</a>
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
                                    <h5>Editar Localidad</h5>
                                </div>
                                <div class="card-block">
                                    {{-- <form method="POST" action="{{ route('admin.localities.update', $locality->id) }}"> --}}
                                        @csrf
                                        @method('PUT')                                       

                                        <div class="form-group row @error('districts') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Distrito</label>
                                            <div class="col-sm-10">
                                                <select id="districts" name="districts" class="form-control">
                                                    <option value="">--- Seleccionar Distrito ---</option>
                                                    @foreach ($districts as $district)                                                        
                                                        <option value="{{ $district->id }}" @if ($district->id == old('districts', $district->id           )) selected @endif>{{ $district->description }}</option>
                                                        {{-- <option value="{{ $region->id   }}" @if ($region->id   == old('regiones' , $department->region->id )) selected @endif>{{ $region->description   }}</option> --}}
                                                    @endforeach
                                                </select>
                                                @error('districts')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción Localidad</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description', $locality->description) }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description', $locality->description) }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-warning m-b-0">Modificar</button>
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

    $('#districts').select2();    

});
</script>
@endpush