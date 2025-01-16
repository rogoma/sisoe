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
                            <a href="{{ route('contracts.index') }}">Contratos</a>
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
                                        {{-- <h5>Listado Precios Referenciales del Ítem Nro {{ $item->item_number }}</h5> --}}
                                        {{-- <h5>Producto {{ $item->level5_catalog_code->description }}</h5> --}}
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>                                
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="items" class="table table-striped table-bcontracted nowrap">
                                            <thead>
                                                <tr>                                                    
                                                    <th>N° item</th>
                                                    <th>Id_rubro</th>
                                                    <th>Rubro</th>
                                                    <th>Cant.</th>
                                                    <th>Unid.</th>
                                                    <th>Precio UNIT. MO</th>
                                                    <th>Precio UNIT. MAT</th>
                                                    <th>Precio TOT. MO</th>
                                                    <th>Precio TOT. MAT</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < count($items); $i++)
                                                    <tr>                                                        
                                                        <td>{{ $items[$i]->item_number }}</td>
                                                        <td>{{ $items[$i]->rubro_id }}</td>
                                                        
                                                        {{-- debe mostrar description de rubro --}}
                                                        <td>{{ $items[$i]->rubro_id }}</td> 
                                                        
                                                        <td>{{ $items[$i]->quantity }}</td>

                                                        {{-- debe mostrar description de tipo de unidad de medida --}}
                                                        <td>{{ $items[$i]->quantity }}</td>

                                                        <td>{{ number_format($items[$i]->unit_price_mo,'0', ',','.') }} </td>
                                                        <td>{{ number_format($items[$i]->unit_price_mat,'0', ',','.') }} </td>
                                                        <td>{{ number_format($items[$i]->tot_price_mo,'0', ',','.') }} </td>
                                                        <td>{{ number_format($items[$i]->tot_price_mat,'0', ',','.') }} </td>                           

                                                        <td>
                                                            {{-- @if (Auth::user()->hasPermission(['admin.items.update','contracts.items.update'])) --}}
                                                                <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateItem({{ $items[$i]->id }})">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            {{-- @endif --}}

                                                            {{-- @if (Auth::user()->hasPermission(['admin.items.delete','contracts.items.update'])) --}}
                                                            <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItemAwardHistories({{$items[$i]->id }})">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            {{-- @endif --}}
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


@push('scripts')
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#contracts').DataTable({
        //         "columnDefs": [
        //             {
        //                 // { "width": "30%", "targets": 0 },  // Define el ancho de la primera columna
        //                 // { "width": "30%", "targets": 1 },  // Define el ancho de la segunda columna
        //                 "targets": 5, // Índice de la columna que deseas personalizar
        //                 "data": "linkdncp",
        //                 "render": function (data, type, row, meta) {
        //                 // Puedes personalizar el contenido de la columna aquí
        //                 return '<a href="' + data + '" target="_blank" style="color:blue">Link DNCP</a>'; // Suponiendo que el campo a enlazar está en el índice 2
        //                 }
        //             }
        //         ]
        //     });
        // });

    </script>
@endpush
