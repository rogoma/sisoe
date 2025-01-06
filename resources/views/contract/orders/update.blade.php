@extends('layouts.app')

@section('content')
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-sitemap bg-c-blue"></i>
                        <div class="d-inline">
                            <h5>Órdenes</h5>
                            <span>Modificar Órdenes</span>
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
                                        <h5>Modificar Orden del Contrato N° {{ $contract->number_year }}</h5>
                                        <br><br>
                                        <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                        <h3 style="text-align: center;">Modificar Orden</h3>                                        
                                    </div>
                                    <div class="card-block">
                                        <form method="POST"
                                            action="{{ route('contracts.orders.update', [$contract->id, $order->id]) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="container">                                                
                                                <div class="form-group row">
                                                    <label for="number" class="col-sm-2 col-form-label">N° de Orden</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" id="number" name="number"
                                                            class="form-control @error('number') is-invalid @enderror"
                                                            value="{{ old('number',$order->number) }}" maxlength="6" disabled>
                                                        @error('number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="total_amount" class="col-sm-2 col-form-label">Monto</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control @error('total_amount') is-invalid @enderror"
                                                            value="{{ old('total_amount', $order->total_amount) }}" maxlength="23" disabled>
                                                        @error('total_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="date" class="col-sm-2 col-form-label">Fecha</label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            {{-- <input type="text" id="sign_date" name="sign_date"  --}}
                                                            <input type="text" id="date" name="date"
                                                                class="form-control @error('date') is-invalid @enderror"
                                                                value="{{ old('date', date('d/m/Y', strtotime($order->date)))}}" autocomplete="off">
                                                                {{-- value="{{ old('sign_date', date('d/m/Y', strtotime($order->date))) }}" class="form-control" autocomplete="off"> --}}
                                                            <span class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="show('date');"><i
                                                                        class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                        @error('date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="locality" class="col-sm-2 col-form-label">Localidad
                                                        <br><small>(Hasta 200 caracteres)</small></label>                                                    
                                                    <div class="col-sm-10">
                                                        <input type="text" id="locality" name="locality"
                                                            class="form-control @error('locality') is-invalid @enderror"
                                                            value="{{ old('locality',$order->locality) }}" maxlength="200">
                                                        @error('locality')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="component_id"
                                                        class="col-sm-2 col-form-label">Componente</label>
                                                    <div class="col-sm-10">
                                                        <select id="component_id" name="component_id"
                                                            class="form-control @error('component_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Componente ---</option>
                                                            @foreach ($components as $component)
                                                                <option value="{{ $component->id }}"
                                                                    @if ($component->id == old('component_id',$order->component_id)) selected @endif>
                                                                    {{ $component->description }}</option>
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
                                                        <select id="order_state_id" name="order_state_id"
                                                            class="form-control @error('order_state_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Estado ---</option>
                                                            @foreach ($order_states as $order_state)
                                                                <option value="{{ $order_state->id }}"
                                                                    @if ($order_state->id == old('order_state_id',$order->order_state_id)) selected @endif>
                                                                    {{ $order_state->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('order_state_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="comments" class="col-sm-2 col-form-label">Comentarios
                                                        <br><small>(Hasta 300 caracteres)</small></label>
                                                    <div class="col-sm-10">
                                                        <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror"
                                                            maxlength="300">{{ old('comments',$order->comments) }}</textarea>
                                                        @error('comments')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <br>
                                                <div class="form-group text-center">
                                                    <button id="guardar" type="submit"
                                                        class="btn btn-primary">Modificar Orden</button>
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

    $('#date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });   
    

});
</script>
@endpush