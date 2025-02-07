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
                            <h5>Orden de Ejecución N° {{ $order->number }}</h5>
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
                                <a href="{{ route('contracts.index', $order->id) }}">Órdenes</a>
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
                                            <h5>Items de la Orden de Ejecución</h5>
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
                                                                <td style="text-align: center;">{{ $item->rubro->code }}
                                                                </td>
                                                                <td>{{ $item->rubro->description }}</td>

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
                                                        <td colspan="6"></td>
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;"> TOTALES:</td>
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mo"></td> 
                                                            <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mat"></td>                                                        
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
