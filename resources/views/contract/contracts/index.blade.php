@extends('layouts.app')

@push('styles')
<style type="text/css">

p.centrado {

}
</style>
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fas fa-list bg-c-blue"></i>
                    <div class="d-inline">                                               
                        <h5>Listado de Contratos de Obras</h5>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <br>
                        {{-- si es de UOC, role = 30 o si es contratista role = 4 no muestra reportes --}}
                        @if (Auth::user()->role->id == 30 || Auth::user()->role->id == 4)
                        @else    
                            <a href="pdf/panel_orders1" class="btn btn-outline-primary" target="_blank"> TOTAL ORDENES</a>
                            <a href="pdf/tablero" class="btn btn-outline-danger" target="_blank"> TABLERO</a>
                            <a href="/orders/exportarorders2" class="btn btn-outline-success">TOTAL ORDENES EXCEL</a>
                        @endif
                    </div>
                </div>                
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
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
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="contracts" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Dependencia</th>
                                                    <th>Llamado</th>
                                                    <th>IDDNCP</th>
                                                    <th>Año</th>
                                                    <th>Link DNCP</th>
                                                    <th>N°/Año</th>
                                                    <th>Contrato</th>
                                                    <th>Monto</th>
                                                    <th>Contratista</th>
                                                    <th>Estado</th>
                                                    <th>Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < count($contracts); $i++)
                                                    <tr>
                                                        <td style="max-width: 10px"> {{ ($i+1) }}</td>
                                                        <td> {{ $contracts[$i]->dependency->description }}</td>
                                                        <td style="max-width: 2500px"> {{ $contracts[$i]->description }}</td>
                                                        <td> {{ number_format($contracts[$i]->iddncp,'0', ',','.') }} </td>
                                                        <td> {{ number_format($contracts[$i]->year_adj,'0', ',','.') }} </td>
                                                        <td style="color:#ff0000">{{ $contracts[$i]->linkdncp }}</td>

                                                        <td> {{ $contracts[$i]->number_year }}</td>

                                                        @if ($contracts[$i]->open_contract == 1)
                                                            <td>Contrato Abierto</td>
                                                        @else
                                                            <td>Contrato Cerrado</td>
                                                        @endif

                                                        <td style="max-width: 150px"> Gs.{{ number_format($contracts[$i]->total_amount,'0', ',','.') }} </td>

                                                        <td>{{ $contracts[$i]->provider->description }}</td>

                                                        @if (in_array($contracts[$i]->contractState->id, [2,3,6]))
                                                            <td style="color:#ff0000">{{ $contracts[$i]->contractState->description }}</td>
                                                        @else
                                                            @if (in_array($contracts[$i]->contractState->id, [4]))
                                                                <td style="color:red;font-weight: bold;background-color:yellow">{{ $contracts[$i]->contractState->description }}</td>
                                                            @else
                                                                <td style="color:green">{{ $contracts[$i]->contractState->description }}</td>
                                                            @endif
                                                        @endif

                                                        <td>{{ $contracts[$i]->contractType->description }}</td>
                                                        <td>
                                                            <a href="{{ route('contracts.show', $contracts[$i]->id) }}" class="btn btn-outline-success">Ver Más</a>
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
        $(document).ready(function() {
            $('#contracts').DataTable({
                "columnDefs": [
                    {
                        "targets": 5, // Índice de la columna que deseas personalizar
                        "data": "linkdncp",
                        "render": function (data, type, row, meta) {
                        // Puedes personalizar el contenido de la columna aquí
                        return '<a href="' + data + '" target="_blank" style="color:blue">Link DNCP</a>'; // Suponiendo que el campo a enlazar está en el índice 2
                        }
                    }
                ]
            });
        });
    </script>
@endpush
