@extends('layouts.app')

{{-- @extends('layouts.app') --}}

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Órdenes</h5>
                        <span>Agregar Orden</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.show', $contract->id) }}">Contrato N° {{ $contract->number_year }}</a>
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
                                    <h5>Agregar Orden al Contrato N° {{ $contract->number_year }}</h5>
                                    <br><br>
                                    <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                    <h3 style="text-align: center;">Agregar Orden</h3>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('contracts.orders.store', $contract->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="container">
                                            <div class="form-group row">
                                                <label for="number" class="col-sm-2 col-form-label">N° de Orden</label>
                                                <div class="col-sm-4">
                                                    {{-- <label for="" value="{{ $nextContractId }}"></label> --}}
                                                    <label for="number">{{ $nextContractNumber }}</label>
                                                    {{-- <input type="text" id="number" name="number" class="form-control @error('number') is-invalid @enderror"
                                                    value="{{ $contract->number }}" maxlength="6" disabled>
                                                    @error('number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror --}}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="total_amount" class="col-sm-2 col-form-label">Monto</label>
                                                <div class="col-sm-4">
                                                    <input type="text" id="total_amount" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount', 0) }}" maxlength="23" disabled>
                                                    @error('total_amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="department_id" class="col-sm-2 col-form-label">Departamento</label>
                                                <div class="col-sm-10">
                                                    <select id="department_id" name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Departamento ---</option>
                                                        @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" @if ($department->id == old('department_id')) selected @endif>
                                                            {{ $department->description }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('department_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="district_id" class="col-sm-2 col-form-label">Distrito</label>
                                                <div class="col-sm-10">
                                                    <select id="district_id" name="district_id" class="form-control @error('district_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Distrito ---</option>
                                                    </select>
                                                    @error('district_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="locality" class="col-sm-2 col-form-label">Localidad <br><small>(Hasta 200 caracteres)</small></label>
                                                <div class="col-sm-10">
                                                    <input type="text" id="locality" name="locality" class="form-control @error('locality') is-invalid @enderror" value="{{ old('locality') }}" maxlength="200">
                                                    @error('locality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="date" class="col-sm-2 col-form-label">Fecha acuse recibo Contratista</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <input type="text" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" autocomplete="off">
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="show('date');"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                    @error('date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="plazo" class="col-sm-2 col-form-label">Plazo de ejecución <br><small>(En días)</small></label>
                                                <div class="col-sm-4">
                                                    <input type="text" id="plazo" name="plazo" class="form-control @error('plazo') is-invalid @enderror" value="{{ old('plazo') }}" maxlength="3">
                                                    @error('plazo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="component_id" class="col-sm-2 col-form-label">Componente</label>
                                                <div class="col-sm-10">
                                                    <select id="component_id" name="component_id" class="form-control @error('component_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Componente ---</option>
                                                        @foreach ($components as $component)
                                                        <option value="{{ $component->id }}" @if ($component->id == old('component_id')) selected @endif>{{ $component->description }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('component_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="order_state_id" class="col-sm-2 col-form-label">Estado de la Orden</label>
                                                <div class="col-sm-10">
                                                    <select id="order_state_id" name="order_state_id" class="form-control @error('order_state_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Estado ---</option>
                                                        @foreach ($order_states as $order_state)
                                                        <option value="{{ $order_state->id }}" @if ($order_state->id == old('order_state_id')) selected @endif>{{ $order_state->description }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('order_state_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="comments" class="col-sm-2 col-form-label">Comentarios <br><small>(Hasta 300 caracteres)</small></label>
                                                <div class="col-sm-10">
                                                    <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror" maxlength="300">{{ old('comments') }}</textarea>
                                                    @error('comments')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script type="text/javascript">

$(document).ready(function(){

    $('#component_id').select2();
    $('#order_state_id').select2();
    $('#department_id').select2();
    $('#district_id').select2();


    // Script para formatear el valor NUMERO DE ORDEN con separador de miles mientras se ingresa Monto
    document.getElementById('number').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado
    let number = event.target.value.replace(/\./g, '');
    // Comprobamos si el valor es vacío
    if (number === '' || number < 0) {
        event.target.value = '0';
        return;
    }

    // Convertimos a número
    number = parseFloat(number);

    // Verificamos si el number es un número válido y no NaN
    if (isNaN(number) || number < 0) {
        event.target.value = '0';
        return;
    }

    // Formateamos el valor con separador de miles
    number = number.toLocaleString('es-ES');

    // Actualizamos el valor en el input text
    event.target.value = number;
    });

    // Script para formatear el valor MONTO DE ORDEN con separador de miles mientras se ingresa Monto
    document.getElementById('total_amount').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado
    let total_amount = event.target.value.replace(/\./g, '');
    // Comprobamos si el valor es vacío
    if (total_amount === '' || total_amount < 0) {
        event.target.value = '0';
        return;
    }

    // Convertimos a número
    total_amount = parseFloat(total_amount);

    // Verificamos si el total_amount es un número válido y no NaN
    if (isNaN(total_amount) || total_amount < 0) {
        event.target.value = '0';
        return;
    }

    // Formateamos el valor con separador de miles
    total_amount = total_amount.toLocaleString('es-ES');

    // Actualizamos el valor en el input text
    event.target.value = total_amount;
    });

    // Script para formatear el valor NUMERO DE ORDEN con separador de miles mientras se ingresa Monto
    document.getElementById('plazo').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado
    let plazo = event.target.value.replace(/\./g, '');
    // Comprobamos si el valor es vacío
    if (plazo === '' || plazo < 0) {
        event.target.value = '0';
        return;
    }

    // Convertimos a número
    plazo = parseFloat(plazo);

    // Verificamos si el plazo es un número válido y no NaN
    if (isNaN(plazo) || plazo < 0) {
        event.target.value = '0';
        return;
    }

    // Formateamos el valor con separador de miles
    plazo = plazo.toLocaleString('es-ES');

    // Actualizamos el valor en el input text
    event.target.value = plazo;
    });


    $('#date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });

    // $('#file').bind('change', function() {
    //     max_upload_size = {{ $post_max_size }};
    //     if(this.files[0].size > max_upload_size){
    //         $('#guardar').attr("disabled", "disabled");
    //         file_size = Math.ceil((this.files[0].size/1024)/1024);
    //         max_allowed = Math.ceil((max_upload_size/1024)/1024);
    //         swal("Error!", "El tamaño del archivo seleccionado ("+file_size+" Mb) supera el tamaño maximo de carga permitido ("+max_allowed+" Mb).", "error");
    //     }else{
    //         $('#guardar').removeAttr("disabled");
    //     }
    // });

});
</script>
@endpush
