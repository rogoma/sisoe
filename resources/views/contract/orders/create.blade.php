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
                                                    <input type="text" id="number" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" maxlength="6">
                                                    @error('number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                <label for="date" class="col-sm-2 col-form-label">Fecha</label>
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
                                                <label for="locality" class="col-sm-2 col-form-label">Localidad <br><small>(Hasta 200 caracteres)</small></label>
                                                
                                                {{-- <label class="col-form-label">Dependencia <br><small>(Dependencia solicitante)</small></label> --}}
                                                <div class="col-sm-10">
                                                    <input type="text" id="locality" name="locality" class="form-control @error('locality') is-invalid @enderror" value="{{ old('locality') }}" maxlength="200">
                                                    @error('locality')
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
<script type="text/javascript">
$(document).ready(function(){

    $('#component_id').select2();
    $('#order_state_id').select2();

    // Script para formatear el valor NUMERO DE ORDEN con separador de miles mientras se ingresa Monto
    document.getElementById('number').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado
    let monto = event.target.value.replace(/\./g, '');
    // Comprobamos si el valor es vacío
    if (monto === '' || monto < 0) {
        event.target.value = '0';
        return;
    }

    // Convertimos a número
    monto = parseFloat(monto);

    // Verificamos si el monto es un número válido y no NaN
    if (isNaN(monto) || monto < 0) {
        event.target.value = '0';
        return;
    }

    // Formateamos el valor con separador de miles
    monto = monto.toLocaleString('es-ES');

    // Actualizamos el valor en el input text
    event.target.value = monto;
    });

    // Script para formatear el valor MONTO DE ORDEN con separador de miles mientras se ingresa Monto
    document.getElementById('total_amount').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado
    let monto = event.target.value.replace(/\./g, '');
    // Comprobamos si el valor es vacío
    if (monto === '' || monto < 0) {
        event.target.value = '0';
        return;
    }

    // Convertimos a número
    monto = parseFloat(monto);

    // Verificamos si el monto es un número válido y no NaN
    if (isNaN(monto) || monto < 0) {
        event.target.value = '0';
        return;
    }

    // Formateamos el valor con separador de miles
    monto = monto.toLocaleString('es-ES');

    // Actualizamos el valor en el input text
    event.target.value = monto;
    });


    $('#item_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });

    $('#date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });

    //VALIDACIÓN DE FECHAS DE ANTICIPOS
    $('#item_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#item_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#item_to').datepicker('date', null); // Limpiar el datapicker
            $('#item_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#item_to').datepicker('date', null); // Limpiar el datapicker
                $('#item_to').val('');
                $('#control_1').val('');
                $('#control_a').val('');
            }else{
                $('#item_to').datepicker('date', null); // Limpiar el datapicker
                $('#item_to').val('');
                $('#control_1').val('');
                $('#control_a').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_1').val(restaFechas(f1,f2));
            }
        }
    });

    $('#item_to').on('changeDate', function() {
        var fechaInicio = $('#item_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin) {
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#item_to').datepicker('date', null); // Limpiar el datapicker
            $('#item_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#item_to').datepicker('date', null); // Limpiar el datapicker
            $('#item_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
        }else{
            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#item_from').val();//fecha dtpicker inicio
            var f2=  $('#item_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_1').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_a').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });

    $('#file').bind('change', function() {
        max_upload_size = {{ $post_max_size }};
        if(this.files[0].size > max_upload_size){
            $('#guardar').attr("disabled", "disabled");
            file_size = Math.ceil((this.files[0].size/1024)/1024);
            max_allowed = Math.ceil((max_upload_size/1024)/1024);
            swal("Error!", "El tamaño del archivo seleccionado ("+file_size+" Mb) supera el tamaño maximo de carga permitido ("+max_allowed+" Mb).", "error");
        }else{
            $('#guardar').removeAttr("disabled");
        }
    });

});
</script>
@endpush
