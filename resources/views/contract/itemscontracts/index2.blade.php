@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">

    <style>
        #items td,
        #items th {
            padding: 4px 8px;
            vertical-align: middle;
            border-left: 1px solid #ddd;
            /* Línea vertical a la izquierda */
            border-right: 1px solid #ddd;
            /* Línea vertical a la derecha */   
        }

        #items {
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
    </style>
@endpush

@section('content')
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-list bg-c-blue"></i>
                        <div class="d-inline">
                            <h5 style="color: red;">
                                Contrato N°: {{ $contract->description }}                            
                            </h5>                        
                        </div>
                        <br>
                        <div class="d-inline">
                            <h5 style="color: red;">Detalle de Rubros de Componente: {{ $items[0]->component->code }} - {{ $items[0]->component->description }} </h5>
                            <br>
                            <h5 style="color: red;">Localidad: {{ $order->locality }} </h5>
                            index2
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
                                <a href="{{ route('contracts.volver', $contract->id) }}">Órdenes</a>                            
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
                                            <h4 style="color: blue;">Rubros para procesar en la Orden de Ejecución N°: {{$order->number}}
                                        </div>
                                        <div class="float-right">
                                        </div>
                                    </div>                                
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="items" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>                                                    
                                                        <th>N° item</th>                                                        
                                                        <th>Rubro (Cod. - Descripción)</th>
                                                        <th>Cant.</th>
                                                        <th>Unid.</th>
                                                        <th>Precio UNIT. MO</th>
                                                        <th>Precio UNIT. MAT</th>
                                                        <th>Precio TOT. MO</th>
                                                        <th>Precio TOT. MAT</th>
                                                        {{-- <th style="width: 120px; text-align: center;">Acciones</th>                                                         --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $tot_price_mo = 0;
                                                        $tot_price_mat = 0;
                                                    @endphp

                                                    @foreach ($items as $i => $item)
                                                        <tr>
                                                            @if ($item->rubro_id == '9999')
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-size: 16px; font-weight: bold;">
                                                                    {{ $item->subitem->description }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @else
                                                                <td style="text-align: center;">{{ $item->item_number }}
                                                                </td>
                                                                <td style="text-align: left;">{{ $item->rubro->code }} - {{ $item->rubro->description }}</td>
                                                                {{-- <td>{{ $item->rubro->description }}</td> --}}

                                                                {{-- verificar este Campo editable para la cantidad --}}
                                                                <td style="text-align: center;">
                                                                    <input type="number" class="quantity"
                                                                        data-index="{{ $i }}" value="0"
                                                                        min="0" step="any"
                                                                        style="width: 80px; text-align: center;">
                                                                </td>                                                               

                                                                <td style="text-align: center;">
                                                                    {{ $item->rubro->orderPresentations->description }}
                                                                </td>
                                                                
                                                                <td style="text-align: center;"
                                                                    id="unit_price_mo_{{ $i }}">
                                                                    {{ number_format($item->unit_price_mo, 0, ',', '.') }}
                                                                </td>

                                                                <td style="text-align: center;" id="unit_price_mat_{{ $i }}"> {{ number_format($item->unit_price_mat, 0, ',', '.') }}
                                                                </td>

                                                                <td style="text-align: center;"
                                                                    id="total_mo_{{ $i }}">
                                                                    0
                                                                </td>
                                                                <td style="text-align: center;"
                                                                    id="total_mat_{{ $i }}">
                                                                    0
                                                                </td>                                                                
                                                            @endif
                                                        </tr>
                                                    @endforeach                                                    
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;"> TOTALES:</td>
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mo"></td> 
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mat"></td>                                                        
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="text-center">                                                
                                                    @if (in_array($contract->contract_state_id, [1, 2]))
                                                        <a href="{{ route('contracts.files.create_eval', $contract->id) }}"
                                                            class="btn btn-primary">Grabar Rubros de Orden</a>
                                                    @endif                                                
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
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    function updateTotals() {
        let totalMo = 0;
        let totalMat = 0;

        $(".quantity").each(function () {
            let index = $(this).data("index");
            
            let totalMoText = $("#total_mo_" + index).text().replace(/\./g, '').replace(',', '.');
            let totalMatText = $("#total_mat_" + index).text().replace(/\./g, '').replace(',', '.');

            totalMo += parseFloat(totalMoText) || 0;
            totalMat += parseFloat(totalMatText) || 0;
        });

        $("#tot_price_mo").text(totalMo.toLocaleString('es-ES')); 
        $("#tot_price_mat").text(totalMat.toLocaleString('es-ES')); 
    }

    $(".quantity").on("input", function () {
        let index = $(this).data("index");
        let quantity = parseFloat($(this).val()) || 0;
        
        let unitPriceText = $("#unit_price_mo_" + index).text().replace(/\./g, '').replace(',', '.');
        let unitPrice2Text = $("#unit_price_mat_" + index).text().replace(/\./g, '').replace(',', '.');

        let unitPrice = parseFloat(unitPriceText) || 0;
        let unitPrice2 = parseFloat(unitPrice2Text) || 0;

        let total = Math.round(quantity * unitPrice);
        let total2 = Math.round(quantity * unitPrice2);

        $("#total_mo_" + index).text(total.toLocaleString('es-ES'));
        $("#total_mat_" + index).text(total2.toLocaleString('es-ES'));

        updateTotals(); // Llamar a la función para actualizar los totales
    });

    updateTotals(); // Asegurar que los totales se calculen al cargar la página
});

</script>


{{-- @extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">

    <style>
        #items td, 
        #items th {
            padding: 4px 8px;
            vertical-align: middle;
            border-left: 1px solid #ddd;  /* Línea vertical a la izquierda */
            border-right: 1px solid #ddd; /* Línea vertical a la derecha */
        }
    
        #items {
            border-collapse: collapse;
        }
    
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
    </style>    
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        <h5 style="color: red;">
                            Contrato N°: {{ $contract->description }}                            
                        </h5>                        
                    </div>
                    <br>
                    <div class="d-inline">
                        <h5 style="color: red;">Detalle de Rubros de Componente: {{ $items[0]->component->code }} - {{ $items[0]->component->description }} </h5>
                        <br>
                        <h5 style="color: red;">Localidad: {{ $order->locality }} </h5>
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
                            <a href="{{ route('contracts.volver', $contract->id) }}">Órdenes</a>                            
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
                                        <h4 style="color: blue;">Rubros para procesar en la Orden de Ejecución N°: {{$order->number}}
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>                                
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="items" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>                                                    
                                                    <th>N° item</th>
                                                    <th>Cod_rubro</th>
                                                    <th>Rubro</th>
                                                    <th>Cant.</th>
                                                    <th>Unid.</th>
                                                    <th>Precio UNIT. MO</th>
                                                    <th>Precio UNIT. MAT</th>
                                                    <th>Precio TOT. MO</th>
                                                    <th>Precio TOT. MAT</th>
                                                    <th style="width: 120px; text-align: center;">Acciones</th>                                                    
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                @php
                                                    $tot_price_mo = 0; 
                                                    $tot_price_mat = 0;    
                                                @endphp
                                                
                                                @foreach ($items as $i => $item)
                                                    <tr>
                                                        @if($item->rubro_id == '9999')
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 16px; font-weight: bold;">{{ $item->subitem->description }}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        @else
                                                            <td style="text-align: center;">{{ $item->item_number }}</td>
                                                            <td style="text-align: center;">{{ $item->rubro->code }}</td>
                                                            <td>{{ $item->rubro->description }}</td>
                                            
                                                            
                                                            <td style="text-align: center;">
                                                                <input type="number" step="0.01" min="0" 
                                                                       value="{{ $item->quantity }}" 
                                                                       class="form-control quantity-input" 
                                                                       data-index="{{ $i }}"
                                                                       oninput="updateTotal({{ $i }})">
                                                            </td>
                                            
                                                            <td style="text-align: center;">{{ $item->rubro->orderPresentations->description }}</td>
                                                            <td style="text-align: center;">{{ number_format($item->unit_price_mo, 2, ',', '.') }}</td>
                                                            <td style="text-align: center;">{{ number_format($item->unit_price_mat, 2, ',', '.') }}</td>
                                                            
                                                            
                                                            <td style="text-align: center;" id="total_mo_{{ $i }}">
                                                                {{ number_format($item->quantity * $item->unit_price_mo, 2, ',', '.') }}
                                                            </td>
                                            
                                                            <td style="text-align: center;">{{ number_format($item->tot_price_mat, 2, ',', '.') }}</td>
                                            
                                                            @php
                                                                $tot_price_mo += $item->quantity * $item->unit_price_mo;
                                                                $tot_price_mat += $item->tot_price_mat;
                                                            @endphp
                                            
                                                            <td style="text-align: center;">
                                                                <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateItem({{ $item->id }})">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                            
                                                                <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItemAwardHistories({{ $item->id }})">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>                                           
                                            
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mo, '0', ',', '.') }}</td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mat, '0', ',', '.') }}</td>
                                                    <td colspan="3"></td>
                                                </tr>
                                            </tfoot>
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


@push('scripts')
    <script type="text/javascript">

    function updateTotal(index) {
        let quantity = parseFloat(document.querySelector(`.quantity-input[data-index="${index}"]`).value) || 0;
        let unitPriceMO = parseFloat(document.getElementById(`total_mo_${index}`).dataset.unitPrice) || 0;
        
        let totalMO = quantity * unitPriceMO;
        
        document.getElementById(`total_mo_${index}`).innerText = totalMO.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    </script>
@endpush --}}
