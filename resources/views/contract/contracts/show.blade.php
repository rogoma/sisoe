@extends('layouts.app')

@push('styles')
    <style type="text/css">
        .table td,
        .table th {
            padding: 0.2rem 0.5rem;
            font-size: 14px
        }

        .tab-content.card-block {
            padding: 1.25rem 0.5rem;
        }
        }

        .columna1 {
            width: 1%;
            text-align: center;
        }

        .columna2 {
            width: 5%;
            text-align: left;
        }

        .columna3 {
            width: 9%;
            text-align: left;
        }

        .columna4 {
            width: 6%;
            text-align: left;
        }

        .columna5 {
            width: 2%;
            text-align: center;
        }

        .columna6 {
            width: 4%;
            text-align: center;
        }

        .columna7 {
            width: 4%;
            text-align: center;
        }

        .columna8 {
            width: 3%;
            text-align: center;
        }

        .columna9 {
            width: 3%;
            text-align: left;
        }

        .columna10 {
            width: 3%;
            text-align: center;
        }

        .columna11 {
            width: 9%;
            text-align: left;
        }

        .columna12 {
            width: 10%;
            text-align: left;
        }

        p.centrado {}
    </style>
@endpush

@section('content')
    {{-- <div class="container"> --}}
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-list bg-c-blue"></i>
                        <div class="d-inline">
                            <h5>Visualizar Contratos</h5>
                            <span>Contrato Nº {{ $contract->number_year }}</span>
                            <br><br>
                            <h5>
                                <p style="font-size: 17px; font-weight: bold; color:#FF0000;">Estado Actual:
                                    {{ $contract->contractState->id . ' - ' . $contract->contractState->description }}</p>
                            </h5>
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
                                        <div class="row">
                                            <div class="col-sm-10 text-left">
                                                <h5>Llamado:
                                                    {{ $contract->description . ' - ' . $contract->modality->description . ' - Contrato N° ' . $contract->number_year . ' - ' . $contract->provider->description }}
                                                </h5>
                                            </div>
                                            <div class="col-sm-10 text-left">
                                                <h5 style="font-size: 17px; font-weight: bold; color:blue">Dependencia
                                                    Responsable: {{ $contract->dependency->description }}</h5>
                                            </div>

                                            <div class="col-sm-2">
                                                @if (Auth::user()->hasPermission(['admin.contracts.update']))
                                                    {{-- @if (in_array($contract->contract_state_id, [1, 2])) --}}
                                                    <button class="btn btn-primary dropdown-toggle waves-effect"
                                                        type="button" id="acciones" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="true">Acciones</button>
                                                    {{-- @endif --}}
                                                @endif

                                                <div class="dropdown-menu" aria-labelledby="acciones"
                                                    data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    {{-- Verificamos permisos de edición del usuario --}}
                                                    @if (
                                                        (Auth::user()->hasPermission(['contracts.contracts.update']) && $contract->contract_state_id >= 1) ||
                                                            Auth::user()->hasPermission(['admin.contracts.update']))
                                                        <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;"
                                                            class="dropdown-item waves-effect f-w-600"
                                                            href="{{ route('contracts.edit', $contract->id) }}">Editar
                                                            Contrato</a>
                                                    @endif

                                                    @if (Auth::user()->hasPermission(['admin.contracts.delete']) ||
                                                            Auth::user()->hasPermission(['contracts.contracts.delete']))
                                                        {{-- <a href="#" style="font-size: 14px; font-weight: bold; color:red;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" onclick="deleteContract('{{ $contract->id }}')">Eliminar Llamado</a> --}}
                                                        {{-- <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItem({{ $contract->id }})"><i class="fa fa-trash"></i></button>                                                         --}}
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-block">
                                        <ul class="nav nav-tabs md-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tab0"
                                                    role="tab"><i class="fa fa-tasks"></i> Datos del Contrato</a>
                                                <div class="slide"></div>
                                            </li>
                                            @if (Auth::user()->hasPermission(['admin.items.create', 'contracts.rubros.import']))
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab5" role="tab"><i
                                                            class="fa fa-file-excel-o"></i> Cargar Rubros </a>
                                                    <div class="slide"></div>
                                                </li>
                                            @endif
                                            {{-- @if (Auth::user()->hasPermission(['admin.items.create', 'contracts.rubros.show'])) --}}
                                            @if (Auth::user()->hasPermission(['admin.items.create', 'contracts.items.show']))
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab5" role="tab"><i
                                                            class="fa fa-file-excel-o"></i> Rubros Cargados </a>
                                                    <div class="slide"></div>
                                                </li>
                                            @endif
                                            @if (Auth::user()->hasPermission(['admin.users.create', 'contracts.users.create']))
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab3" role="tab"><i
                                                            class="fa fa-user-o"></i> Asignar Fiscal <br>(Si contrato está
                                                        en Curso)</a>
                                                    <div class="slide"></div>
                                                </li>
                                            @endif
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab1" role="tab"><i
                                                        class="fa fa-external-link"></i> Eval.Técnica</a>
                                                <div class="slide"></div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab2" role="tab"><i
                                                        class="fa fa-clone"></i> Órdenes de Ejec.</a>
                                                <div class="slide"></div>
                                            </li>
                                            {{-- <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab7" role="tab"><i
                                                        class="fa fa-file-pdf-o"></i> Plazos/Prórrogas</a>
                                                <div class="slide"></div>
                                            </li> --}}
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab4" role="tab"><i
                                                        class="fa fa-file-pdf-o"></i> Reportes</a>
                                                <div class="slide"></div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab6" role="tab"><i
                                                        class="fa fa-file-archive-o"></i> Archivos</a>
                                                <div class="slide"></div>
                                            </li>
                                        </ul>

                                        <div class="tab-content card-block">
                                            <div class="tab-pane active" id="tab0" role="tabpanel">
                                                <h5 class="text-center fw-bold">Datos del Contrato</h5>
                                                <br>
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead class="table-info text-center">
                                                        <tr>
                                                            <th>Nombre del Llamado</th>
                                                            <th>Tipo Llamado</th>
                                                            <th>IDDNCP</th>
                                                            <th class="w-25 text-center">Link DNCP</th>
                                                            <th>N° Contrato/Año</th>
                                                            <th>AÑO</th>
                                                            <th colspan="2">Fecha firma contrato</th>
                                                            <!-- Abarca dos columnas -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="max-width: 900px">{{ $contract->description }}</td>
                                                            <td style="max-width: 900px">
                                                                {{ $contract->modality->description }}</td>
                                                            <td>{{ number_format($contract->iddncp, '0', ',', '.') }}</td>
                                                            <td class="w-25">{{ $contract->linkdncp }} </td>
                                                            <td>{{ $contract->number_year }}</td>
                                                            <td>{{ number_format($contract->year_adj, '0', ',', '.') }}
                                                            </td>
                                                            <td colspan="2">{{ $contract->signDateFormat() }}</td>
                                                            <!-- Abarca dos columnas -->
                                                        </tr>
                                                    </tbody>

                                                    <thead class="table-info text-center">
                                                        <tr>
                                                            <th>Contratista</th>
                                                            <th>Estado</th>
                                                            <th>Modalidad</th>
                                                            <th>Organismo Financiador</th>
                                                            <th>Abierto/Cerrado</th>
                                                            <th>Tipo</th>
                                                            <th>Monto Mínimo</th>
                                                            <th>Monto Máximo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $contract->provider->description }}</td>
                                                            <td
                                                                class="{{ in_array($contract->contract_state_id, [2, 3, 4, 6]) ? 'text-danger' : 'text-success' }}">
                                                                {{ $contract->contractState->description }}
                                                            </td>
                                                            <td>{{ $contract->modality->description }}</td>
                                                            <td>{{ $contract->financialOrganism->description }}</td>
                                                            <td>{{ $contract->open_contract == 1 ? 'Contrato Abierto' : 'Contrato Cerrado' }}
                                                            </td>
                                                            <td>{{ $contract->contractType->description }}</td>
                                                            <td class="fw-bold text-primary">Gs.
                                                                {{ $contract->minimAmountFormat() }}</td>
                                                            <td class="fw-bold text-primary">Gs.
                                                                {{ $contract->totalAmountFormat() }}</td>
                                                        </tr>
                                                    </tbody>

                                                    <thead class="table-info text-center">
                                                        <tr>
                                                            <th colspan="2">Dependencia Responsable</th>
                                                            <th colspan="2">Administrador del Contrato</th>
                                                            <th colspan="4">Comentarios</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2">{{ $contract->dependency->description }}
                                                            </td>
                                                            <td colspan="2">{{ $contract->contractAdmin->name ?? '-' }}
                                                                {{ $contract->contractAdmin->lastname ?? '-' }}</td>
                                                            <td colspan="4">{{ $contract->comments }}</td>
                                                        </tr>
                                                    </tbody>

                                                    <thead class="table-info text-center">
                                                        <tr>
                                                            <th>Fiscal 1</th>
                                                            <th>Fiscal 2</th>
                                                            <th>Fiscal 3</th>
                                                            <th>Fiscal 4</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $contract->fiscal1->name ?? '-' }}
                                                                {{ $contract->fiscal1->lastname ?? '-' }} -
                                                                {{ $contract->fiscal1->position->description ?? '-' }}</td>
                                                            <td>{{ $contract->fiscal2->name ?? '-' }}
                                                                {{ $contract->fiscal2->lastname ?? '-' }} -
                                                                {{ $contract->fiscal2->position->description ?? '-' }}</td>
                                                            <td>{{ $contract->fiscal3->name ?? '-' }}
                                                                {{ $contract->fiscal3->lastname ?? '-' }} -
                                                                {{ $contract->fiscal3->position->description ?? '-' }}</td>
                                                            <td>{{ $contract->fiscal4->name ?? '-' }}
                                                                {{ $contract->fiscal4->lastname ?? '-' }} -
                                                                {{ $contract->fiscal4->position->description ?? '-' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                            </div>

                                            <div class="tab-pane" id="tab1" role="tabpanel">
                                                <label class="col-form-label f-w-700">Archivos de Evaluaciones Técnicas:</label>
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción de la Evaluación</th>
                                                            <th>Archivo generado por:</th>
                                                            <th>Fecha/Hora</th>
                                                            <th style="width: 190px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < count($user_files_eval); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td style="max-width: 800px"> {{ $user_files_eval[$i]->description }}</td>
                                                                <td style="max-width: 500px"> {{ $user_files_eval[$i]->user->name}} {{ $user_files_eval[$i]->user->lastname }}</td>
                                                                <td style="max-width: 200px"> {{ $user_files_eval[$i]->updated_atDateFormat() }}</td>
                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $user_files_eval[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $user_files_eval[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                    <button title="Eliminar Archivo"
                                                                        onclick="deleteFile({{ $user_files_eval[$i]->id }})"
                                                                        class="btn btn-danger"><i
                                                                            class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor

                                                        @for ($i = 0; $i < count($other_files_eval); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $other_files_eval[$i]->description }}</td>
                                                                {{ $user_files_eval[$i]->user->name}} {{ $user_files_eval[$i]->user->lastname }}
                                                                </td>
                                                                <td>{{ $other_files_eval[$i]->updated_atDateFormat() }}
                                                                </td>
                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $other_files_eval[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $other_files_eval[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="text-center">
                                                    @if (Auth::user()->hasPermission(['admin.evals.create', 'contracts.evals.create']))
                                                        @if (in_array($contract->contract_state_id, [1, 2]))
                                                            <a href="{{ route('contracts.files.create_eval', $contract->id) }}"
                                                                class="btn btn-primary">Cargar Evaluación</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- ORDENES DE EJECUCIÓN - MUESTRA CON SEARCH --}}
                                            <link rel="stylesheet"
                                                href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                                            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                                            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                                            <div class="tab-pane" id="tab2" role="tabpanel">
                                                <table id="items" class="display; table table-striped table-bordered"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>#</th> --}}
                                                            <th>Fiscal</th>
                                                            <th>N° OE</th>
                                                            <th>Fecha Orden</th>
                                                            <th>Monto Orden</th>
                                                            <th>Distrito-Localidad</th>
                                                            <th>Sub-Componente</th>
                                                            <th>Plazo Inicio</th>
                                                            <th>Plazo días</th>
                                                            <th>Fecha Alerta</th>
                                                            <th>Plazo Final</th>
                                                            <th>Estado</th>
                                                            {{-- <th>Referencia</th> --}}
                                                            <th style="width: 190px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($contract->orders->sortBy('id') as $index => $order)
                                                            <tr>

                                                                <td style="color:black;text-align: left;width: 150px;">
                                                                    {{ $order->creatorUser->name }}{{ $order->creatorUser->lastname }}-{{ $order->creatorUser->position->description }}
                                                                </td>
                                                                <td style="text-align: center;width: 60px;">
                                                                    {{ $order->component_code }} - {{ $order->number }}
                                                                </td>
                                                                <td style="text-align: center;width: 25px;">
                                                                    {{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                                                {{-- old('sign_date', date('d/m/Y', strtotime($order->created_at))) --}}
                                                                <td style="text-align: center;width: 100px;">
                                                                    {{ $order->totalAmountFormat() }}</td>
                                                                <td style="text-align: left;width: 120px;">
                                                                    {{ $order->district->description }} -
                                                                    {{ $order->locality }}</td>
                                                                <td style="text-align: left;width: 350px;">
                                                                    {{ $order->component->code }}-{{ $order->component->description }}
                                                                </td>

                                                                {{-- FECHA ACUSE CONTRATISTA --}}
                                                                <td style="color:#ff0000;text-align: left;width: 25px;">
                                                                    @if ($order->sign_date)
                                                                        {{ \Carbon\Carbon::parse($order->sign_date)->format('d/m/Y') }}
                                                                    @endif
                                                                </td>

                                                                {{-- PLAZO --}}
                                                                <td style="color:#ff0000;text-align: center;width: 15px;">
                                                                    {{ $order->plazo }}</td>

                                                                {{-- FECHA ALERTA 03 DIAS ANTES NO PINTA SI YA ESTA FINALIZADO ESTADO 4 --}}
                                                                <td
                                                                    style="text-align: left; width: 20px; 
                                                                    @if ($order->orderState->id == 4) background-color: white; color: black;
                                                                    
                                                                    @elseif ($order->orderState->id == 1 && $order->sign_date) 
                                                                        @php
                                                                            $fechaCalculada = \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo - 3);
                                                                        @endphp

                                                                        @if ($fechaCalculada->lte(\Carbon\Carbon::now()))background-color: yellow; color: black; @endif
                                                                    @endif">

                                                                    @if ($order->sign_date)
                                                                        @php
                                                                            $fechaCalculada = \Carbon\Carbon::parse($order->sign_date,)->addDays($order->plazo - 3);
                                                                        @endphp

                                                                        @if ($order->orderState->id == 1 && $fechaCalculada->lte(\Carbon\Carbon::now()))
                                                                            {{($fechaCalculada)->format('d/m/Y')}}   
                                                                            <strong>FECHA ALERTA</strong>
                                                                        @else
                                                                            {{ $fechaCalculada->format('d/m/Y') }}
                                                                        @endif
                                                                    @endif
                                                                </td>


                                                                {{-- PLAZO FINAL CALCULA SI FECHA PLAZO ES IGUAL A FECHA ACTUAL Y PONE EN ROJO - NO PINTA SI YA ESTA FINALIZADO ESTADO 4 --}}
                                                                <td style="text-align: left; width: 25px; 
                                                                @php
                                                                    $fechaVencimiento = $order->sign_date ? \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo) : null;
                                                                @endphp

                                                                @if ($order->orderState->id == 4) 
                                                                    background-color: white; color: black; 
                                                                @elseif ($order->orderState->id == 1 && $fechaVencimiento && \Carbon\Carbon::now()->gt($fechaVencimiento))
                                                                    background-color: red; color: white;
                                                                @endif">
                                                                
                                                                @if ($order->sign_date)
                                                                    @if ($order->orderState->id == 1 && $fechaVencimiento && \Carbon\Carbon::now()->gt($fechaVencimiento))
                                                                        {{ $fechaVencimiento->format('d/m/Y') }}   
                                                                        <strong> PLAZO VENCIDO</strong>
                                                                    @else
                                                                        {{ $fechaVencimiento->format('d/m/Y') }}
                                                                    @endif
                                                                @endif
                                                            </td>

                                                            </td>
                                                            


                                                                {{-- SI ES ESTADO 5 "ANULADO" SE MUESTRA EN ROJO --}}
                                                                @if (in_array($order->orderState->id, [5]))
                                                                    <td
                                                                        style="color:#ff0000;text-align: left;width: 50px;">
                                                                        {{ $order->orderState->description }}</td>
                                                                @else
                                                                    {{-- SI ES ESTADO 10 "SIN FIRMA" SE MUESTRA EN ROJO Y AMARILLO --}}
                                                                    @if (in_array($order->orderState->id, [10]))
                                                                        <td
                                                                            style="color:white;background-color:red;width: 120px;">
                                                                            {{ $order->orderState->description }}</td>
                                                                    @else
                                                                        @if (in_array($order->orderState->id, [11]))
                                                                            <td
                                                                                style="color:#ff0000;background-color:yellow;width: 120px;">
                                                                                {{ $order->orderState->description }}</td>
                                                                        @else
                                                                            @if ($order->orderState->id == 4)
                                                                                <td
                                                                                    style="color:white;background-color:green">
                                                                                    {{ $order->orderState->description }} -
                                                                                    {{ !empty($order->sign_date_fin) ? \Carbon\Carbon::parse($order->sign_date_fin)->format('d/m/Y') : 'Sin fecha' }}
                                                                                </td>
                                                                            @else
                                                                                <td style="color:rgb(41, 128, 0)">
                                                                                    {{ $order->orderState->description }}
                                                                                </td>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                {{-- <td style="max-width: 200px">{{ $order->comments }}</td> --}}

                                                                {{-- Muestra si estado de llamado es En curso --}}
                                                                <td>
                                                                    {{-- Para Desanular si estado = 5 (anulado) --}}
                                                                    @if (in_array($order->orderState->id, [5]))
                                                                        @if (Auth::user()->hasPermission(['admin.orders.desanule']))
                                                                            <button type="button" title="Desanular"
                                                                                class="btn btn-success btn-icon"
                                                                                onclick="DesanuleOrder({{ $order->id }})"><i
                                                                                    class="fa fa-check"></i></button>
                                                                        @endif
                                                                    @endif

                                                                    {{-- Para mostra datos de acuerdo a estados de la Orden  --}}
                                                                    @if (in_array($order->orderState->id, [1, 2, 3, 10, 11]))
                                                                        @if (Auth::user()->hasPermission(['admin.orders.update', 'orders.orders.update']))
                                                                            {{-- ACA PREGUNTAMOS SI LA ORDEN ES DEL MISMO USUARIO LOGUEADO --}}
                                                                            @if (Auth::user()->id == $order->creator_user_id)
                                                                                <button type="button" title="Editar"
                                                                                    class="btn btn-warning btn-icon"
                                                                                    onclick="updateOrder({{ $order->id }})">
                                                                                    <i class="fa fa-pencil"></i>
                                                                                </button>
                                                                            @endif

                                                                            @if ($order->items->count() > 0)
                                                                                {{-- <button type="button"
                                                                                    title="Orden con Rubros"
                                                                                    class="btn btn-primary btn-icon"
                                                                                    onclick="itemOrder({{ $order->id }})">
                                                                                    <i class="fa fa-list"></i>
                                                                                </button> --}}
                                                                                {{-- MOSTRAR PDF DE ORDEN --}}
                                                                                <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                    title="Ver Orden" target="_blank"
                                                                                    class="btn btn-success btn-icon"><i
                                                                                        class="fa fa-eye"></i></a>

                                                                                {{-- ACA PREGUNTAMOS SI LA ORDEN ES DEL MISMO USUARIO LOGUEADO --}}
                                                                                @if (Auth::user()->id == $order->creator_user_id)
                                                                                    <button type="button" title="Anular"
                                                                                        class="btn btn-danger btn-icon"
                                                                                        onclick="anuleOrder({{ $order->id }})"><i
                                                                                            class="fa fa-ban"></i></button>
                                                                                @endif

                                                                                {{-- Editar y Cambiar Rubros de la orden --}}
                                                                                @if (Auth::user()->id == $order->creator_user_id && $order->orderState->id == 1)
                                                                                    <button type="button" title="Editar Rubros"
                                                                                        class="btn btn-secondary btn-icon"
                                                                                        onclick="itemContraRubro({{ $order->id }}, {{ $order->contract->id }}, {{ $order->component->id }})">
                                                                                        <i
                                                                                            class="fa fa-recycle"></i></button>
                                                                                @endif

                                                                                {{-- Generar Eventos para prorroga de fecha de vencimiento del plazo de la orden --}}
                                                                                @if (Auth::user()->id == $order->creator_user_id && $order->orderState->id == 1)
                                                                                    <button type="button" title="Cargar Eventos"
                                                                                        class="btn btn-primary btn-icon">
                                                                                        {{-- onclick="itemContraRubro({{ $order->id }}, {{ $order->contract->id }}, {{ $order->component->id }})"> --}}
                                                                                        <i class="fa fa-clock-o"></i></button>
                                                                                @endif
                                                                            @else
                                                                                {{-- ACA PREGUNTAMOS SI LA ORDEN ES DEL MISMO USUARIO LOGUEADO --}}
                                                                                @if (Auth::user()->id == $order->creator_user_id)
                                                                                    <button type="button"
                                                                                        title="Importar Rubros de Contrato"
                                                                                        class="btn btn-primary btn-icon"
                                                                                        onclick="itemContraRubro({{ $order->id }}, {{ $order->contract->id }}, {{ $order->component->id }})">
                                                                                        <i
                                                                                            class="fa fa-download text-white"></i>
                                                                                    </button>
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                        {{-- Muestra botones si no son fiscales --}}
                                                                        @if (Auth::user()->hasPermission(['admin.orders.show', 'orders.orders.view']))
                                                                            @if ($order->items->count() > 0)
                                                                                {{-- <button type="button"
                                                                                    title="Orden con Rubros"
                                                                                    class="btn btn-primary btn-icon"
                                                                                    onclick="itemOrder({{ $order->id }})">
                                                                                    <i class="fa fa-list"></i>
                                                                                </button> --}}

                                                                                {{-- MOSTRAR PDF DE ORDEN --}}
                                                                                <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                    title="Ver Orden" target="_blank"
                                                                                    class="btn btn-success btn-icon"><i
                                                                                        class="fa fa-eye"></i></a>
                                                                                {{-- @else
                                                                                <span style="color:#ff0000;background-color:yellow">Falta agregar rubros </span>  --}}
                                                                            @endif
                                                                        @endif
                                                                    @endif

                                                                    @if (in_array($order->orderState->id, [4]))
                                                                         {{-- MOSTRAR PDF DE ORDEN --}}
                                                                         <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                            title="Ver Orden" target="_blank"
                                                                            class="btn btn-success btn-icon"><i
                                                                                class="fa fa-eye"></i></a>
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                <div class="text-right">
                                                    {{-- @if (Auth::user()->hasPermission(['contracts.contracts.create', 'admin.orders.create'])) --}}
                                                    @if (Auth::user()->hasPermission(['admin.orders.create', 'orders.orders.create']))
                                                        {{-- Si pedido está anulado no muestra agregar ítems --}}
                                                        @if (in_array($contract->contract_state_id, [1]))
                                                            <a href="{{ route('contracts.orders.create', $contract->id) }}"
                                                                class="btn btn-primary">Agregar Orden</a>
                                                        @endif
                                                    @endif
                                                </div>
                                                <br><br><br>
                                                {{-- ACA DEBEN IR VALORES CALCULADOS DE ACUERDO A LA GENERACIÓN DE ORDENES --}}
                                                <div
                                                    style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>MONTO DEL CONTRATO:</u> {{ $contract->totalAmountFormat() }}
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>MONTO COMPROMETIDO:</u>
                                                        {{ number_format($contract->compro_amount, 0, ',', '.') }}
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: red; background-color: white; padding: 10px;">
                                                        <u>MONTO UTILIZADO:</u> 0
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>SALDO DEL CONTRATO:</u> {{ $contract->totalAmountFormat() }}
                                                        {{-- SALDO DEL CONTRATO: {{ $contract->totalAmountFormat() }} --}}
                                                    </div>
                                                </div>
                                                {{-- <br>
                                                <span style="font-size: 16px; font-weight: bold; color:WHITE;background-color:GREEN;">MONTO DEL CONTRATO: {{ $contract->totalAmountFormat() }}</span>
                                                <br>
                                                <span style="font-size: 16px; font-weight: bold; color:red;background-color:yellow;">MONTO COMPROMETIDO: 0 </span>
                                                <br>
                                                <span style="font-size: 16px; font-weight: bold; color:WHITE;background-color:BLUE;">SALDO DEL CONTRATO: {{ $contract->totalAmountFormat() }}</span>
                                                <br> --}}
                                            </div>

                                            <div class="tab-pane" id="tab7" role="tabpanel">
                                                <label class="col-form-label f-w-600">Eventos de Ordenes:</label>
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción</th>
                                                            <th>Tipo de Evento:</th>
                                                            <th>Fecha/Hora</th>
                                                            <th style="width: 200px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < count($user_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $user_files_con[$i]->description }}</td>
                                                                <td>{{ $user_files_con[$i]->dependency->description }}</td>
                                                                <td>{{ $user_files_con[$i]->updated_atDateFormat() }}</td>
                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $user_files_con[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $user_files_con[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                    <button title="Eliminar Archivo"
                                                                        onclick="deleteFile({{ $user_files_con[$i]->id }})"
                                                                        class="btn btn-danger"><i
                                                                            class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor

                                                        @for ($i = 0; $i < count($other_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $other_files_con[$i]->description }}</td>
                                                                <td>{{ $other_files_con[$i]->dependency->description }}
                                                                </td>
                                                                <td>{{ $other_files_con[$i]->updated_atDateFormat() }}
                                                                </td>
                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $other_files_con[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $other_files_con[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                                <div class="text-right">
                                                    @if (Auth::user()->hasPermission(['admin.files.create', 'contracts.files.create']))
                                                        @if (in_array($contract->contract_state_id, [1, 2]))
                                                            <a href="{{ route('contracts.files.create_con', $contract->id) }}"
                                                                class="btn btn-primary">Cargar Evento</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab4" role="tabpanel">
                                                <label class="col-form-label f-w-600">Reportes:</label>
                                                <table id="forms" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Reporte</th>
                                                            <th>Detalle del Reporte</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($contract->items->count() > 0)
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Componentes de Sistemas</td>
                                                                {{-- <td><a href="/pdf/panel_contracts/{{ $contract->id }}"
                                                                        class="btn btn-default" target="_blank"><i
                                                                            class="fa fa-file-pdf-o"></i> &nbsp;Componentes
                                                                        de Sistemas de Abastecimiento de Agua</a></td> --}}
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="tab-pane" id="tab3" role="tabpanel">
                                                <label class="col-form-label f-w-600">Asignación de Fiscales del
                                                    Contrato:</label>
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre del Fiscal 1</th>
                                                            <th>Nombre del Fiscal 2</th>
                                                            <th>Nombre del Fiscal 3</th>
                                                            <th>Nombre del Fiscal 4</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $contract->fiscal1->name ?? '-' }}
                                                                {{ $contract->fiscal1->lastname ?? '-' }} -
                                                                {{ $contract->fiscal1->position->description ?? '-' }}
                                                            </td>
                                                            <td>{{ $contract->fiscal2->name ?? '-' }}
                                                                {{ $contract->fiscal2->lastname ?? '-' }} -
                                                                {{ $contract->fiscal2->position->description ?? '-' }}
                                                            </td>
                                                            <td>{{ $contract->fiscal3->name ?? '-' }}
                                                                {{ $contract->fiscal3->lastname ?? '-' }} -
                                                                {{ $contract->fiscal3->position->description ?? '-' }}
                                                            </td>
                                                            <td>{{ $contract->fiscal4->name ?? '-' }}
                                                                {{ $contract->fiscal4->lastname ?? '-' }} -
                                                                {{ $contract->fiscal4->position->description ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="text-center">
                                                    @if (Auth::user()->hasPermission(['admin.fiscales.create', 'contracts.fiscales.create']))
                                                        @if (in_array($contract->contract_state_id, [1]))
                                                            @if ($contract->fiscal1_id === null)
                                                                <a href="{{ route('contracts.asign', $contract->id) }}"
                                                                    class="btn btn-danger">Asignar Fiscal</a>
                                                            @else
                                                                <a href="{{ route('contracts.asign', $contract->id) }}"
                                                                    class="btn btn-danger">Reasignar Fiscal</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab5" role="tabpanel">
                                                {{-- <label class="col-form-label f-w-400">Componentes:</label> --}}
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Nombre de Componente con rubros cargados</th>
                                                            <th style="width: 120px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    {{-- php para contar totales de rubros x contratos --}}
                                                    <tbody>
                                                        @php $counter = 1; @endphp
                                                        @foreach ($items_contract as $item)
                                                            <tr>
                                                                <td>
                                                                    <p>{{ $counter }}</p>
                                                                </td>
                                                                <td>
                                                                    <p>{{ $item->component->code }} -
                                                                        {{ $item->component->description }}</p>
                                                                </td>

                                                                <td>
                                                                    <button type="button" title="Componente con Rubros"
                                                                        class="btn btn-primary btn-icon"
                                                                        onclick="itemRubro({{ $item->contract->id . ',' . $item->component->id }})">
                                                                        <i class="fa fa-list"></i>
                                                                    </button>
                                                                    {{-- MOSTRAR PDF DE ORDEN --}}
                                                                    {{-- <a href="/pdf/panel_contracts10/{{ $order->id }}" title="Ver Orden" target="_blank" class="btn btn-success btn-icon"><i class="fa fa-eye"></i></a> --}}

                                                                    {{-- OJO -> Si no tiene movimiento en Orden --}}
                                                                    @if (Auth::user()->hasPermission(['admin.items.delete', 'contracts.items.delete']))
                                                                        <button type="button" title="Eliminar Componente"
                                                                            class="btn btn-danger btn-icon"
                                                                            onclick="anuleRubro ({{ $item->contract->id . ',' . $item->component->id }})">
                                                                            <i class="fa fa-ban"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @php $counter++; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="text-center">
                                                    @if (Auth::user()->hasPermission(['admin.items.create', 'contracts.items.create']))
                                                        @if (in_array($contract->contract_state_id, [1, 2]))
                                                            {{-- <a href="{{ route('orders.items.uploadExcel', $contract->id) }}" --}}
                                                            <a href="{{ route('contracts.files.uploadExcelRubros', $contract->id) }}"
                                                                class="btn btn-primary">Importar Planillas con Rubros
                                                                (Excel)</a>
                                                        @endif
                                                    @endif
                                                </div>
                                                <br>
                                                {{-- <span style="font-size: 16px; font-weight: bold; color:red;background-color:yellow;">SUMATORIA DE RUBROS CARGADOS: {{ $contract->totalAmountFormat() }}</span> --}}
                                                <br><br><br><br>
                                                <div class="float-rigth">
                                                    @if (Auth::user()->hasPermission(['contracts.items.create']))
                                                        <h6 style="color:blue">Archivos Excel de Componentes (Formato Zip)
                                                            para Descargar y realizar importación de rubros </h6>
                                                        <a href="excel/pedidos" title="Descargar Planillas Reg. Oriental"
                                                            class="btn btn-danger" target="_blank">Planillas Reg.
                                                            Oriental</a>
                                                        <a href="excel/pedidos2"
                                                            title="Descargar Planillas Reg. Occidental"
                                                            class="btn btn-danger" target="_blank">Planillas Reg.
                                                            Occidental</a>
                                                        <a href="excel/pedidos3"
                                                            title="Descargar Planilla Todos los Componentes Reg. Oriental"
                                                            class="btn btn-danger" target="_blank">Planilla Todos los
                                                            Componentes Reg. Oriental</a>
                                                        <a href="excel/pedidos4"
                                                            title="Descargar Planilla Todos los Componentes Reg. Occidental"
                                                            class="btn btn-danger" target="_blank">Planilla Todos los
                                                            Componentes Reg. Occidental</a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab6" role="tabpanel">
                                                <label class="col-form-label f-w-600">Archivos del contrato:</label>
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción</th>
                                                            <th>Archivo generado por:</th>
                                                            <th>Fecha/Hora</th>
                                                            {{-- <th>Acciones</th> --}}
                                                            <th style="width: 200px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < count($user_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $user_files_con[$i]->description }}</td>
                                                                {{-- <td>{{ $user_files_con[$i]->dependency->description }}</td> --}}
                                                                <td style="max-width: 500px"> {{ $user_files_con[$i]->user->name}} {{ $user_files_con[$i]->user->lastname }}</td>

                                                                <td>{{ $user_files_con[$i]->updated_atDateFormat() }}</td>

                                                                {{-- <td style="max-width: 800px"> {{ $user_files_eval[$i]->description }}</td> --}}
                                                                {{-- <td style="max-width: 500px"> {{ $user_files_eval[$i]->user->name}} {{ $user_files_eval[$i]->user->lastname }}</td> --}}
                                                                {{-- <td style="max-width: 200px"> {{ $user_files_eval[$i]->updated_atDateFormat() }}</td> --}}



                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $user_files_con[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $user_files_con[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                    <button title="Eliminar Archivo"
                                                                        onclick="deleteFile({{ $user_files_con[$i]->id }})"
                                                                        class="btn btn-danger"><i
                                                                            class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor

                                                        @for ($i = 0; $i < count($other_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $other_files_con[$i]->description }}</td>
                                                                {{-- <td>{{ $other_files_con[$i]->dependency->description }}</td> --}}
                                                                <td style="max-width: 500px"> {{ $other_files_con[$i]->user->name}} {{ $other_files_con[$i]->user->lastname }}</td>
                                                                <td>{{ $other_files_con[$i]->updated_atDateFormat() }}
                                                                </td>
                                                                <td>
                                                                    <a href="{{ asset('storage/files/' . $other_files_con[$i]->file) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $other_files_con[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa fa-download"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                                <div class="text-right">
                                                    @if (Auth::user()->hasPermission(['admin.files.create', 'contracts.files.create']))
                                                        @if (in_array($contract->contract_state_id, [1, 2]))
                                                            <a href="{{ route('contracts.files.create_con', $contract->id) }}"
                                                                class="btn btn-primary">Cargar Archivos</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- MOSTRAMOS TIMELINE --}}
                                {{-- <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5 style="font-size: 14px; font-weight: bold; color:red" class="text-left">Movimientos del pedido</h5>
                                </div>
                                <div class="card-block">
                                    <div class="latest-update-box">
                                    @foreach ($contract->contractscontractstates()->contractBy('id', 'desc')->get() as $item)
                                        <div class="row p-t-20 p-b-30 borde-alternado">
                                            <div class="col-auto text-right update-meta p-r-0">
                                                <i class="update-icon ring"></i>
                                            </div>
                                            <div class="col p-l-5">
                                                <a href="javascript:void(0);">
                                                    <h6 style="font-size: 14px; font-weight: bold; color:Black">{{ $item->contractstate->id}}-{{ $item->contractstate->description}}</h6>
                                                </a>
                                                <p style="font-size: 14px; font-weight: bold; color:Black">{{ $item->creatorUser->getFullName()}}</p>
                                                <small style="font-size: 13px; font-weight: bold; color:Black">Fecha: {{ $item->createdAtDateFormat()}}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div> --}}
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

            $('#items').DataTable();

            const table = $('#example').DataTable({
                ajax: '/tables', // URL que devuelve los datos JSON
                columns: [{
                        data: 'title',
                        title: 'Título'
                    },
                    {
                        data: null,
                        title: 'Acción',
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-primary toggle-files" data-id="${row.id}">Ver Archivos</button>
                        <div id="files-${row.id}" class="files-container" style="display: none; margin-top: 10px;">
                            ${row.files.map(file => `<a href="${file.url}" target="_blank">${file.name}</a><br>`).join('')}
                        </div>
                    `;
                        }
                    }
                ]
            });

            // Manejar el despliegue de archivos
            $('#example').on('click', '.toggle-files', function() {
                const id = $(this).data('id');
                $(`#files-${id}`).toggle(); // Mostrar/Ocultar contenedor de archivos
            });

            updateOrder = function(order) {
                location.href = '/contracts/{{ $contract->id }}/orders/' + order + '/edit/';
            }

            anuleOrder = function(order) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea anular la orden?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/orders/' + order,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Orden Anulada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            DesanuleOrder = function(order) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea Desanular la orden?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, desanular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/orders/' + order,

                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Orden Desanulada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            anuleRubro = function(contract, component) {
                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea eliminar el componente cargado?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: `/items_contracts/${contract}/component/${component}/delete`,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        let response = (typeof data === "object") ? data : JSON
                                            .parse(data);
                                        if (response.status === "success") {
                                            swal("Éxito!", "Componente anulado correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "1-Ocurrió un error al procesar la solicitud.",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "2-Ocurrió un error al procesar la solicitud.",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            //lleva a index de ItemsOrdersController
            itemOrder = function(order) {
                location.href = '/orders/' + order + '/items_orders';
            }

            //lleva a indexRubros de ItemsContractsController
            itemContraRubro = function(order, contract, component) {
                location.href = '/orders/' + order + '/items_contracts/' + contract + '/component/' +
                    component + '/itemsRubros';
            }

            //lleva a index de ItemsContractsController
            itemRubro = function(contract, component) {
                location.href = '/items_contracts/' + contract + '/component/' + component + '/items';
            }

            updateItem = function(item) {
                //llamar a Función JS que está en H:\Proyectos\sistedoc\public\js\guardar-tab.js
                // persistirTab();
                location.href = '/contracts/{{ $contract->id }}/items/' + item + '/edit/';
            }

            deleteItem = function(item) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar la póliza?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/items/' + item,

                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Póliza eliminada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };


            updateContracts = function(budget) {
                // persistirTab();
                location.href = '/contracts/{{ $contract->id }}/items_budget/' + budget + '/edit/';
            }

            recibecontract = function(contract_id) {
                $.ajax({
                    url: '/contracts/recibe_contract/' + contract_id,
                    method: 'POST',
                    data: '_token=' + '{{ csrf_token() }}',
                    success: function(data) {
                        try {
                            response = (typeof data == "object") ? data : JSON.parse(data);
                            if (response.status == "success") {
                                swal({
                                        title: "Éxito!",
                                        text: response.message,
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        location.reload();
                                    });
                            } else {
                                swal("Error!", response.message, "error");
                            }
                        } catch (error) {
                            swal("Error!",
                                "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                "error");
                            console.log(error);
                        }
                    },
                    error: function(error) {
                        swal("Error!",
                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                            "error");
                        console.log(error);
                    }
                });
            }

            derivecontract = function(contract_id) {
                $.ajax({
                    url: '/contracts/derive_contract/' + contract_id,
                    method: 'POST',
                    data: '_token=' + '{{ csrf_token() }}',
                    success: function(data) {
                        try {
                            response = (typeof data == "object") ? data : JSON.parse(data);
                            if (response.status == "success") {
                                swal({
                                        title: "Éxito!",
                                        text: response.message,
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        location.reload();
                                    });
                            } else {
                                swal("Error!", response.message, "error");
                            }
                        } catch (error) {
                            swal("Error!",
                                "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                "error");
                            console.log(error);
                        }
                    },
                    error: function(error) {
                        swal("Error!",
                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                            "error");
                        console.log(error);
                    }
                });
            }

            itemAwardHistories = function(item) {
                //lleva a itemawardhistories index
                location.href = '/items/' + item + '/item_award_histories';
            }

            deleteContract = function(id) {
                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea eliminar el llamado?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '{{ route('contracts.delete', ['contract_id' => ':id']) }}'
                                    .replace(':id', id),
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió 1 un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    });
            }


            deleteFile = function(file) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea anular el Archivo?",

                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/files/' + file + '/delete/',
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió 1 error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            deleteObjection = function(objection) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar el registro?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/objections/' + objection,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            deleteObjectionResponse = function(objection, objection_response) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar el registro?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/' + objection + '/objections_responses/' +
                                    objection_response,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

        });
    </script>
@endpush
