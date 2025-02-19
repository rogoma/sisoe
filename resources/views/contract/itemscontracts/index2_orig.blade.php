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
                            <label for="order_id">Order ID:</label>
                            <input type="text" id="order_id" value="{{ $order->id }}" readonly>
                            <input type="text" id="creator_user_id" value="{{ Auth::user()->id }}" readonly>
                            
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
                                                        <th class="item_number">N° Item</th>
                                                        <th class="rubro-id" style="display: none;">ID Rubro</th>
                                                        <th>Rubro</th>
                                                        <th class="quantity">Cant.</th>
                                                        <th>Unid.</th>
                                                        <th class="price-unit-mo">Precio UNIT. MO</th>
                                                        <th class="price-unit-mat">Precio UNIT. MAT</th>
                                                        <th class="price-total-mo">Precio TOT. MO</th>
                                                        <th class="price-total-mat">Precio TOT. MAT</th>
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
                                                                <td style="display: none;"></td>
                                                                <td style="font-size: 16px; font-weight: bold;">{{ $item->subitem->description }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @else
                                                                <td style="text-align: center;">{{ $item->item_number }}</td>
                                                                <td style="text-align: center; display: none;">{{ $item->rubro->id }}</td>
                                                                <td style="text-align: left;">
                                                                    {{ $item->rubro->code }}-{{ $item->rubro->description }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    <input type="number" class="quantity" data-index="{{ $i }}" value="0" min="0" step="any" style="width: 80px; text-align: center;">
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ $item->rubro->orderPresentations->description }}
                                                                </td>
                                                                <td style="text-align: center;" id="unit_price_mo_{{ $i }}">
                                                                    {{ number_format($item->unit_price_mo, 0, ',', '.') }}
                                                                </td>
                                                                <td style="text-align: center;" id="unit_price_mat_{{ $i }}">
                                                                    {{ number_format($item->unit_price_mat, 0, ',', '.') }}
                                                                </td>
                                                                <td style="text-align: center;" id="total_mo_{{ $i }}">0</td>
                                                                <td style="text-align: center;" id="total_mat_{{ $i }}">0</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mo"></td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mat"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="text-center">                                                
                                                    @if (in_array($contract->contract_state_id, [1, 2]))
                                                    <button id="saveButton" class="btn btn-primary">Guardar datos</button>                                                        
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

<script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#items').DataTable();
        });

        // Guardar datos con AJAX
        $('#saveButton').click(function() {
            const items = [];
            const orderId = $('#order_id').val();
            const creator_user_Id = $('#creator_user_id').val();

            $('#items tbody tr').each(function() {
                const row = $(this);
                items.push({
                    item_number: row.find('.item_number').text(),
                    rubro_id: row.find('.rubro-id').text(),
                    quantity: parseFloat(row.find('.quantity').text()),
                    unit_price_mo: parseFloat(row.find('.price-unit-mo').text()),
                    unit_price_mat: parseFloat(row.find('.price-unit-mat').text()),
                    tot_price_mo: parseFloat(row.find('.price-total-mo').text()),
                    tot_price_mat: parseFloat(row.find('.price-total-mat').text()),
                });
            });

            $.ajax({
                url: '/item-orders',
                type: 'POST',
                data: {
                    items: items,
                    order_id: orderId, 
                    creator_user_id: creator_user_Id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
            });
        });
    </script>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function updateTotals() {
            let totalMo = 0;
            let totalMat = 0;

            $(".quantity").each(function () {
                let index = $(this).data("index");
                
                // Verificar que los elementos existan antes de acceder a ellos
                let totalMoText = $("#total_mo_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";
                let totalMatText = $("#total_mat_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";

                totalMo += parseFloat(totalMoText) || 0;
                totalMat += parseFloat(totalMatText) || 0;
            });

            // Actualizar totales en el footer
            $("#tot_price_mo").text(totalMo.toLocaleString('es-ES'));
            $("#tot_price_mat").text(totalMat.toLocaleString('es-ES'));
        }

        $(".quantity").on("input", function () {
            let index = $(this).data("index");
            let quantity = parseFloat($(this).val()) || 0;
            
            // Verificar que los precios unitarios existan antes de acceder a ellos
            let unitPriceText = $("#unit_price_mo_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";
            let unitPrice2Text = $("#unit_price_mat_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";

            let unitPrice = parseFloat(unitPriceText) || 0;
            let unitPrice2 = parseFloat(unitPrice2Text) || 0;

            // Calcular los totales por fila
            let total = Math.round(quantity * unitPrice);
            let total2 = Math.round(quantity * unitPrice2);

            // Actualizar los valores de las celdas de total
            $("#total_mo_" + index).text(total.toLocaleString('es-ES'));
            $("#total_mat_" + index).text(total2.toLocaleString('es-ES'));

            // Actualizar los totales generales
            updateTotals();
        });

        // Llamar a la función para calcular totales al cargar la página
        updateTotals();

        // Guardar datos al hacer clic en el botón
        
    });

</script> --}}