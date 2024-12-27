@extends('layouts.app')

@push('styles')
<style type="text/css">
.table td, .table th {
    padding: 0.2rem 0.5rem;
    font-size: 14px
}
.tab-content.card-block {
    padding: 1.25rem 0.5rem;
}
}
    .columna1 { width: 1%; text-align: center;}
    .columna2 { width: 10%; text-align: left;}
    .columna3 { width: 9%; text-align: left;}
    .columna4 { width: 16%; text-align: left;}
    .columna5 { width: 2%; text-align: center;}
    .columna6 { width: 4%; text-align: center;}
    .columna7 { width: 4%; text-align: center;}
    .columna8 { width: 3%; text-align: center;}
    .columna9 { width: 3%; text-align: left;}
    .columna10 { width: 3%; text-align: center;}
    .columna11 { width: 9%; text-align: left;}
    .columna12 { width: 10%; text-align: left;}

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
                    <i class="fa fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Visualizar Pedido</h5>
                        <span>Pedido Nº {{ $order->id }}</span>
                    </div>
                    <br>
                    <br>
                    {{-- <h6>Estado Actual: {{ $order->orderState->id." - ".$order->orderState->description }}</h6> --}}
                    <h5><p style="font-size: 17px; font-weight: bold; color:#FF0000">Estado Actual: {{ $order->orderState->id." - ".$order->orderState->description }}</p></h5>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('coordinations.index') }}">Pedidos</a>
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
                                            {{-- <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."-".$order->description }} --}}
                                            {{-- @if ($order->covid==0)
                                                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                                            @else
                                                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                                                <label style="font-size: 16px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID</label></h5>
                                            @endif
                                             --}}
                                            {{-- PARA MOSTRAR SI ES COVID Y SI ES URGENCIA IMPOSTERGABLE --}}
                                            @if ($order->covid==0)
                                                @if ($order->unpostponable==0)
                                                    <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description."-".($order->rejected_obs) }}
                                                    {{-- <h5>{{ ($order->rejected_obs) }}     --}}
                                                @else
                                                    <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description."-".($order->rejected_obs) }}
                                                    <label style="color:red;font-weight: bold;background-color:yellow">(URGENCIA IMPOSTERGABLE)</span> </td></label></h5>
                                                    {{-- <h5>{{ ($order->rejected_obs) }}     --}}
                                                @endif
                                            @else
                                                @if ($order->unpostponable==0)
                                                    <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                                                    <label style="font-size: 16px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID</label></h5>
                                                @else
                                                    <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                                                        <label style="font-size: 16px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID </label>
                                                        <label style="color:red;font-weight: bold;background-color:yellow"> (URGENCIA IMPOSTERGABLE)</label></h5>
                                                @endif
                                            @endif

                                            @if ($order->urgency_state == "ALTA")
                                                <label class="label label-danger m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                                            @else
                                                @if ($order->urgency_state == "MEDIA")
                                                    <label class="label label-warning m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                                                @else
                                                    <label class="label label-info m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                                                @endif
                                            @endif

                                            <h5><p style="font-size: 17px; font-weight: bold; color:blue">SIMESE: {{ is_null($order->simese->first()) ? '' : number_format($order->simese->first()['simese'],'0', ',','.')."/".$order->simese->first()['year'] }}</p></h5>

                                            @if ($order->open_contract == 1)
                                                <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto</a></h5>
                                            @else
                                                @if ($order->open_contract == 2)
                                                    <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Cerrado</a></h5>
                                                @else
                                                    <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto con MontoMin y MontoMáx</a></h5>
                                                @endif
                                            @endif
                                            <br>
                                            <br>
                                            {{-- SE MUESTRA MOTIVO DEL RECHAZO SI EXISTE  --}}
                                            {{-- @if ($order->actual_state == 10) --}}
                                                <h5 style="font-size: 15px; font-weight: bold; color:blue">{{ is_null($order->rejected_obs) ? " " : "OBSERVACIÓN DE DGAF: ".($order->rejected_obs) }}</h5>
                                            {{-- @endif --}}
                                        </div>

                                        <div class="col-sm-2">
                                                <button class="btn btn-primary dropdown-toggle waves-effect" type="button" id="acciones" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones</button>
                                                <div class="dropdown-menu" aria-labelledby="acciones" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">

                                                {{-- *** DERIVAR A LICITACIONES, COMPRAS MENORES O EXCEPCIONES PARA PROCESAR PEDIDO UNA VEZ QUE DGAF DIO SU OK**** --}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->modality_id, [1,2,3,7,8]) && in_array($order->actual_state, [125]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveOrder({{ $order->id }});">Derivar Pedido a Licitaciones</a>
                                                @else
                                                    @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->modality_id, [4,5,6]) && in_array($order->actual_state, [125]))
                                                        <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveOrder({{ $order->id }});">Derivar Pedido a Compras Menores</a>
                                                    @else
                                                        @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->modality_id, [9,10]) && in_array($order->actual_state, [125]))
                                                            <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveOrder({{ $order->id }});">Derivar Pedido a Procesos Complementarios y Excepciones</a>
                                                        @endif
                                                    @endif
                                                @endif

                                                {{-- *** DERIVAR A DGAF PARA AUTORIZACIÓN DE PEDIDO **** --}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->actual_state, [2]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveOrderPedido({{ $order->id }});">Derivar Pedido para Autorización de DGAF</a>
                                                @endif

                                                {{-- *** DERIVAR A PAC PARA PROCESAR PEDIDO SIN IMPORTAR MODALIDAD (ACTUALMENTE 07-2021) **** --}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->actual_state, [5]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick=" deriveOrderPAC({{ $order->id }});">Derivar Pedido a Planificación (PAC)</a>
                                                @endif

                                                {{-- //Acción para COORDINACIÓN cuando hay estado 110 - GENERADO DICTAMEN DE PBC EN AJ--}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->actual_state, [110]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveDictamen({{ $order->id }});">Derivar Dictamen de PBC a DGAF</a>
                                                @endif

                                                {{-- //Acción para COORDINACIÓN cuando hay estado 150 - GENERADO DICTAMEN DE EVALUACIÓN EN AJ--}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->actual_state, [150]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveDictamenEVAL({{ $order->id }});">Derivar Dictamen de Evaluación a DGAF</a>
                                                @endif

                                                {{-- //Acción para COORDINACIÓN cuando hay estado 160 - V°B°-VERIFICADO DICTAMEN DE EVALUACIÓN EN DGAF--}}
                                                @if (Auth::user()->hasPermission(['coordinations.orders.derive']) && in_array($order->actual_state, [160]))
                                                    <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="javascript::void(0);" onclick="deriveAdjudica({{ $order->id }});">Derivar Proceso para Adjudicación</a>
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
                                            <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab"><i class="fa fa-tasks"></i> Datos del Pedido</a>
                                            <div class="slide"></div>
                                        </li>

                                        @if ($order->actual_state <= 20)
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab2" role="tab"><i class="fa fa-briefcase"></i> Empresas cotizantes</a>
                                                <div class="slide"></div>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab2" role="tab"><i class="fa fa-briefcase"></i> Empresas participantes</a>
                                                <div class="slide"></div>
                                            </li>
                                        @endif


                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab3" role="tab"><i class="fa fa-list"></i> Ítems</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab4" role="tab"><i class="fa fa-file-pdf-o"></i> Formularios</a>
                                            <div class="slide"></div>
                                        </li>

                                        {{-- <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab5" role="tab"><i class="fa fa-file-text-o"></i> SIMESE Relacionado</a>
                                            <div class="slide"></div>
                                        </li> --}}

                                        {{-- @if ($order->actual_state > 27) --}}
                                        {{-- @if ($order->actual_state <> 27 && $order->actual_state <> 99) --}}
                                            <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab6" role="tab"><i class="fa fa-file-archive-o"></i> Archivos</a>
                                                    <div class="slide"></div>
                                            </li>
                                        {{-- @endif                                                                                                                             --}}
                                    </ul>
                                    <div class="tab-content card-block">
                                        <div class="tab-pane active" id="tab1" role="tabpanel">
                                            <h4 class="text-center f-w-600">Datos Proyecto de PAC</h4>
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Dependencia:</label></td>
                                                        <td><label class="col-form-label f-w-600">Responsable:</label></td>
                                                        <td><label class="col-form-label f-w-600">Modalidad:</label></td>
                                                        <td><label class="col-form-label f-w-600">DNCP PAC:</label></td>
                                                        <td><label class="col-form-label f-w-600">AÑO:</label></td>
                                                        <td><label class="col-form-label f-w-600">Fecha de Inicio:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->dependency->description }}</td>
                                                        <td>{{ $order->responsible }}</td>
                                                        <td>{{ $order->modality->description }}</td>
                                                        <td>{{ $order->dncpPacIdFormat() }}</td>
                                                        <td>{{ $order->year }}</td>
                                                        <td>{{ $order->beginDateFormat() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Línea Presupuestaria:</label></td>
                                                        <td><label class="col-form-label f-w-600">Fuente de Financiamiento:</label></td>
                                                        <td><label class="col-form-label f-w-600">Organismo Financiero:</label></td>
                                                        {{-- <td colspan="2"><label class="col-form-label f-w-600">Monto total:</label></td> --}}
                                                        <td colspan="2" style="font-size: 16px;color:blue;font-weight: bold">{{ 'Gs. '.$order-> totalAmountFormat() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->subProgram->budgetStructure() }}</td>
                                                        <td>{{ $order->fundingSource->description }}</td>
                                                        <td>{{ $order->financialOrganism->description }}</td>
                                                        <td colspan="2">{{ 'Gs. '.$order->totalAmountFormat() }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h4 class="text-center f-w-600">Requisitos de Solicitud de Adquisición de Bienes y Servicios</h4>
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Ad Referendum:</label></td>
                                                        <td><label class="col-form-label f-w-600">Plurianualidad:</label></td>
                                                        <td><label class="col-form-label f-w-600">Sistema de Adjudicación por:</label></td>
                                                        <td><label class="col-form-label f-w-600">Sub Objeto de Gasto:</label></td>
                                                        <td><label class="col-form-label f-w-600">Fonacide:</label></td>
                                                        <td><label class="col-form-label f-w-600">Modalidad del Llamado:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->ad_referendum ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->plurianualidad ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->system_awarded_by }}</td>
                                                        <td>{{ $order->expenditureObject->code }}</td>
                                                        <td>{{ $order->fonacide ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->modality->description }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><label class="col-form-label f-w-600">La convocante aceptará catálogos, anexos técnicos, folletos y otros textos:</label></td>
                                                        <td><label class="col-form-label f-w-600">Se considerarán ofertas alternativas:</label></td>
                                                        <td><label class="col-form-label f-w-600">Se utilizará la modalidad de contrato abierto:</label></td>
                                                        <td colspan="2"><label class="col-form-label f-w-600">El período de tiempo estimado de funcionamiento de los bienes:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $order->catalogs_technical_annexes ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->alternative_offers ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->open_contract ? "SÍ" : "NO" }}</td>
                                                        <td colspan="2">{{ $order->period_time }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Autorización del Fabricante:</label></td>
                                                        <td><label class="col-form-label f-w-600">Anticipo financiero, porcentaje, monto:</label></td>
                                                        <td colspan="3"><label class="col-form-label f-w-600">Especificaciones Técnicas detalladas del bien o servicio a ser adquirido,
                                                        en caso de obras anexar el programa de entrega, en caso de combustibles describir el valor en cupos y tarjetas:</label></td>
                                                        <td><label class="col-form-label f-w-600">Solicitud de muestras:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->manufacturer_authorization ? "SÍ" : "NO" }}</td>
                                                        <td>{{ $order->financial_advance_percentage_amount ? "SÍ" : "NO" }}</td>
                                                        <td colspan="3">{{ $order->technical_specifications }}</td>
                                                        <td>{{ $order->samples ? "SÍ" : "NO" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Plan de entregas:</label></td>
                                                        <td colspan="3"><label class="col-form-label f-w-600">Propuesta de representantes de miembros del Comité de Evaluación:</label></td>
                                                        <td colspan="2"><label class="col-form-label f-w-600">Garantía del Llamado:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->delivery_plan }}</td>
                                                        <td colspan="3">{{ $order->evaluation_committee_proposal }}</td>
                                                        <td colspan="2">{{ $order->contract_guarantee }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6"><label class="col-form-label f-w-600">Condiciones de Pago:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">{{ $order->payment_conditions }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6"><label class="col-form-label f-w-600">Garantía del Bien o Servicio:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">{{ $order->product_guarantee }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Administrador del Contrato:</label></td>
                                                        <td><label class="col-form-label f-w-600">Vigencia del Contrato:</label></td>
                                                        <td colspan="2"><label class="col-form-label f-w-600">Documentos adicionales que deberá presentar el oferente que
                                                            demuestran que los bienes ofertados cumplen con las especificaciones técnicas:</label></td>
                                                        <td colspan="2"><label class="col-form-label f-w-600">Documentos adicionales que deberá presentar el oferente que demuestran
                                                            que el oferente se halla calificado para ejecutar el contrato:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->contract_administrator }}</td>
                                                        <td>{{ $order->contract_validity }}</td>
                                                        <td colspan="2">{{ $order->additional_technical_documents }}</td>
                                                        <td colspan="2">{{ $order->additional_qualified_documents }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><label class="col-form-label f-w-600">Planilla de Precios (Anexo 1):</label></td>
                                                        <td colspan="2"><label class="col-form-label f-w-600">Título de propiedad, planos aprobados por la municipalidad, licencia ambiental:</label></td>
                                                        <td><label class="col-form-label f-w-600">Medio Magnético:</label></td>
                                                        <td><label class="col-form-label f-w-600">Datos de la persona referente:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $order->price_sheet }}</td>
                                                        <td colspan="2">{{ $order->property_title }}</td>
                                                        <td>{{ $order->magnetic_medium }}</td>
                                                        <td>{{ $order->referring_person_data }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h4 class="text-center f-w-600">Datos Análisis de Precio Referencial</h4>
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Lugar y fecha:</label></td>
                                                        <td><label class="col-form-label f-w-600">Resolución DNCP Nº:</label></td>
                                                        <td><label class="col-form-label f-w-600">Fecha Resolución DNCP:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $order->form4_city.', '.$order->form4DateFormat() }}</td>
                                                        <td>{{ $order->dncp_resolution_number }}</td>
                                                        <td>{{ $order->dncpResolutionDateFormat() }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        @if ($order->actual_state <= 20)
                                            <div class="tab-pane" id="tab2" role="tabpanel">
                                                <table id="budget_request_providers" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Empresa</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @for ($i = 0; $i < count($order->budgetRequestProviders); $i++)
                                                        <tr>
                                                            <td>{{ ($i+1) }}</td>
                                                            <td>{{ $order->budgetRequestProviders[$i]->provider->description }}</td>
                                                        </tr>
                                                    @endfor
                                                    </tbody>
                                                </table>
                                                <div class="text-right">
                                                {{-- En caso de no tener solicitudes de presupuesto --}}
                                                @if($order->providers->count() == 0)
                                                    @if (Auth::user()->hasPermission(['admin.budget_request_providers.create', 'plannings.budget_request_providers.create']) || $order->dependency_id == Auth::user()->dependency_id)
                                                        <a href="{{ route('orders.budget_request_providers.create', $order->id) }}" class="btn btn-primary">Agregar</a>
                                                    @endif
                                                @else
                                                    @if (Auth::user()->hasPermission(['admin.budget_request_providers.update', 'plannings.budget_request_providers.update']) || $order->dependency_id == Auth::user()->dependency_id)
                                                        <a href="{{ route('orders.budget_request_providers.edit', $order->id) }}" class="btn btn-warning">Editar</a>
                                                    @endif
                                                    @if (Auth::user()->hasPermission(['admin.budget_request_providers.delete']) || $order->dependency_id == Auth::user()->dependency_id)
                                                        <button type="button" title="Borrar" class="btn btn-danger" onclick="deleteProviders({{ $order->id }})">
                                                            Borrar
                                                        </button>
                                                    @endif
                                                @endif
                                                </div>
                                            </div>
                                        @else
                                            {{-- PARA EMPRESAS PARTICIPANTES --}}
                                            <div class="tab-pane" id="tab2" role="tabpanel">
                                                {{-- @php
                                                    var_dump($order->budgetRequestProviders->where('request_provider_type', 3)->count());
                                                @endphp --}}

                                                <table id="budget_request_providers" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-weight: bold; color:red">#</th>
                                                            <th style="font-weight: bold; color:red">Empresa participante</th>
                                                            <th style="font-weight: bold; color:red">RUC</th>
                                                            <th style="font-weight: bold; color:red">Teléfono</th>
                                                            <th style="font-weight: bold; color:red">Email para Ofertas</th>
                                                            <th style="font-weight: bold; color:red">Email para Ord. Compras</th>
                                                            <th style="font-weight: bold; color:red">Representante</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @for ($j = 0; $j < count($order->budgetRequestProviders);($j++))
                                                        <tr>
                                                            {{-- Muestra las empresas invitadas (request_provider_type=3) --}}
                                                            @if ($order->budgetRequestProviders[$j]->request_provider_type==3)
                                                                <td>{{ ($j+1) }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->description }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->ruc }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->telefono }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->email_oferta }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->email_ocompra }}</td>
                                                                <td>{{ $order->budgetRequestProviders[$j]->provider->representante }}</td>
                                                            @endif
                                                        </tr>
                                                    @endfor
                                                    </tbody>
                                                </table>
                                                {{-- <div class="text-right">
                                                    En caso de no tener pedidos de tipo 3 (empresas participantes)
                                                    @if($order->budgetRequestProviders->where('request_provider_type', 3)->count() == 0)
                                                        Si pedido está anulado no muestra botón agregar
                                                        @if ($order->actual_state == 0)
                                                        @else
                                                            Se pregunta por permisos de Licitaciones
                                                            @if (Auth::user()->hasPermission(['admin.budget_request_providers.create','exceptions.budget_request_providers.create']))
                                                                <a href="{{ route('orders.budget_request_providers.create_providers_participants', $order->id) }}" class="btn btn-primary">Agregar Empresa participante</a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        Si pedido está anulado no muestra botón agregar
                                                        @if ($order->actual_state == 0)
                                                        @else
                                                            @if (Auth::user()->hasPermission(['admin.budget_request_providers.update','exceptions.budget_request_providers.update']))
                                                                <a href="{{ route('orders.budget_request_providers.edit_providers_participants', $order->id) }}" class="btn btn-success">Editar Empresas participantes</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div> --}}
                                            </div>
                                        @endif

                                        <div class="tab-pane" id="tab3" role="tabpanel">
                                            <div class="row">
                                                <table id="items" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Lote</th>
                                                            <th>Ítem</th>
                                                            <th>Cód. de Catál.</th>
                                                            <th>Descripción</th>
                                                            <th>EETT</th>
                                                            <th>Present.</th>
                                                            <th>U.M.</th>
                                                            {{-- Mostramos ítemes de Contrato Abierto --}}
                                                            @if ($order->open_contract == 1)
                                                                <th>Precio Unitario</th>
                                                                <th>Pedido Mínimo</th>
                                                                <th>Pedido Máximo</th>
                                                                <th>Monto Mínimo</th>
                                                                <th>Monto Máximo</th>
                                                                {{-- <th>Acciones</th> --}}
                                                            @else
                                                                {{-- Mostramos ítemes de Contrato Cerrado --}}
                                                                @if ($order->open_contract == 2)
                                                                    <th>Cantidad</th>
                                                                    <th>Precio Unitario</th>
                                                                    <th>Monto Total</th>
                                                                    {{-- <th>Acciones</th> --}}
                                                                @else
                                                                {{-- Mostramos ítemes de Contrato Abierto con Mmin y Mmax --}}
                                                                    <th>Cantidad</th>
                                                                    <th>Precio Unitario IVA INCL.</th>
                                                                    <th>Monto Mínimo</th>
                                                                    <th>Monto Máximo</th>
                                                                    {{-- <th>Acciones</th> --}}
                                                                @endif
                                                            @endif

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @for ($i = 0; $i < count($order->items); $i++)
                                                        <tr>
                                                            <td>{{ ($i+1) }}</td>
                                                            <td>{{ $order->items[$i]->batch }}</td>
                                                            <td>{{ $order->items[$i]->item_number }}</td>
                                                            @if ($order->items[$i]->level5CatalogCode->code == '99999999-9999')
                                                                <td class="columna3" style="color:red;font-weight: bold">{{ $order->items[$i]->level5CatalogCode->code }}</td>
                                                                <td style="color:red;font-weight: bold">{{ $order->items[$i]->level5CatalogCode->description }}</td>
                                                            @else
                                                                <td class="columna3"> {{ $order->items[$i]->level5CatalogCode->code }}</td>
                                                                <td>{{ $order->items[$i]->level5CatalogCode->description }}</td>
                                                            @endif
                                                            <td>{{ $order->items[$i]->technical_specifications }}</td>
                                                            <td>{{ $order->items[$i]->orderPresentation->description }}</td>
                                                            <td>{{ $order->items[$i]->orderMeasurementUnit->description }}</td>

                                                            {{-- Mostramos ítemes de Contrato Abierto --}}
                                                            @if ($order->open_contract == 1)
                                                                <td class="columna12" style="text-align: center">{{ 'Gs. '.$order->items[$i]->unitPriceFormat() }}</td>
                                                                <td class="columna9"style="text-align: center">{{ $order->items[$i]->min_quantityFormat() }}</td>
                                                                <td class="columna10"style="text-align: center">{{ $order->items[$i]->max_quantityFormat() }}</td>
                                                                <td class="columna11"style="text-align: center">{{ 'Gs. '.$order->items[$i]->totalAmount_min_Format() }}</td>
                                                                <td class="columna12">{{ 'Gs. '.$order->items[$i]->totalAmountFormat() }}</td>
                                                            @else
                                                                {{-- Mostramos ítemes de Contrato Cerrado --}}
                                                                @if ($order->open_contract == 2)
                                                                    <td class="columna8"style="text-align: center">{{ $order->items[$i]->quantityFormat() }}</td>
                                                                    <td class="columna12" style="text-align: center">{{ 'Gs. '.$order->items[$i]->unitPriceFormat() }}</td>
                                                                    <td class="columna12">{{ 'Gs. '.$order->items[$i]->totalAmountFormat() }}</td>
                                                                @else
                                                                    {{-- Mostramos ítemes de Contrato Abierto con Mmin y Mmax --}}
                                                                    <td class="columna10"style="text-align: center">{{ $order->items[$i]->quantityFormat() }}</td>
                                                                    <td class="columna12" style="text-align: center">{{ 'Gs. '.$order->items[$i]->unitPriceFormat() }}</td>
                                                                    <td class="columna11"style="text-align: center">{{ 'Gs. '.$order->items[$i]->totalAmount_min_Format() }}</td>
                                                                    <td class="columna12">{{ 'Gs. '.$order->items[$i]->totalAmountFormat() }}</td>
                                                                @endif
                                                            @endif


                                                            <td style="white-space:nowrap">
                                                                {{-- <button type="button" title="Listado de Precios Referenciales" class="btn btn-primary btn-icon" onclick="itemAwardHistories({{ $order->items[$i]->id }})">
                                                                    <i class="fa fa-list"></i>
                                                                </button> --}}
                                                            @if (Auth::user()->hasPermission(['admin.items.update']) || $order->dependency_id == Auth::user()->dependency_id)
                                                                {{-- <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateItem({{ $order->items[$i]->id }})">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button> --}}
                                                            @endif
                                                            @if (Auth::user()->hasPermission(['admin.items.delete']) || $order->dependency_id == Auth::user()->dependency_id)
                                                                {{-- <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItem({{ $order->items[$i]->id }})">
                                                                    <i class="fa fa-trash"></i>
                                                                </button> --}}
                                                            @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-right">
                                                @if (Auth::user()->hasPermission(['admin.items.create']) || $order->dependency_id == Auth::user()->dependency_id)
                                                    <a href="{{ route('orders.items.create', $order->id) }}" class="btn btn-primary">Agregar ítem</a>
                                                    <a href="{{ route('orders.items.uploadExcel', $order->id)}}" title="Cargar Archivo EXCEL" class="btn btn-success btn-icon">
                                                    <i class="fa fa-upload text-white"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <span style="font-size: 16px; font-weight: bold; color:red;background-color:yellow;" >MONTO TOTAL DEL LLAMADO: {{ $order->totalAmountFormat() }}</span>
                                    </div>


                                        <div class="tab-pane" id="tab4" role="tabpanel">
                                            <table id="forms" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Formulario</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Formulario 1</td>
                                                        <td><a href="/pdf/reporte1/{{ $order->id }}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o"></i> &nbsp;Ver Formulario 1</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Formulario 2</td>
                                                        <td><a href="/pdf/reporte2/{{ $order->id }}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o"></i> &nbsp;Ver Formulario 2</a></td>
                                                    </tr>
                                                    {{-- Verificamos que el pedido tenga ítems cargados --}}
                                                    @if ($order->items->count() > 0)
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Formulario 3</td>
                                                        <td><a href="/pdf/reporte3/{{ $order->id }}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o"></i> &nbsp;Ver Formulario 3</a></td>
                                                    </tr>
                                                    @endif
                                                    {{-- Verificamos que el pedido tenga ítems y solicitudes de presupuesto cargados --}}
                                                    @if ($order->items->count() > 0 && $order->budgetRequestProviders->count() > 0)
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Formulario 4</td>
                                                        <td><a href="/pdf/reporte4/{{ $order->id }}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o"></i> &nbsp;Ver Formulario 4</a></td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="tab5" role="tabpanel">
                                            <label class="col-form-label f-w-600">Documentos SIMESE relacionados al pedido:</label>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        {{-- <th>Año</th> --}}
                                                        <th>Nro.SIMESE/Año</th>
                                                        <th>Dependencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i=0; $i < count($related_simese); $i++)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        {{-- <td>{{ $related_simese[$i]->year }}</td> --}}
                                                        <td>{{ number_format($related_simese[$i]->simese,'0', ',','.') }}-{{ $related_simese[$i]->year }}</td>
                                                        {{-- <td>{{ $related_simese[$i]->simese }}</td> --}}
                                                        <td>{{ $related_simese[$i]->dependency->description }}</td>
                                                    </tr>
                                                    @endfor
                                                    @for ($i=0; $i < count($related_simese_user); $i++)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        {{-- <td>{{ $related_simese_user[$i]->year }}</td> --}}
                                                        <td>{{ number_format($related_simese_user[$i]->simese,'0', ',','.') }}-{{ $related_simese_user[$i]->year }}</td>
                                                        {{-- <td>{{ $related_simese_user[$i]->simese }}</td> --}}
                                                        <td>{{ $related_simese_user[$i]->dependency->description }}</td>
                                                    </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                            <div class="text-right">
                                                {{-- Caso en que la dependencia del usuario no haya cargado ningun documento simese --}}
                                                @if(count($related_simese_user) == 0 && Auth::user()->hasPermission(['admin.simese.create', 'orders.simese.create', 'coordinations.simese.create']))
                                                <a href="{{ route('orders.simese.create', $order->id) }}" class="btn btn-primary">Cargar N° SIMESE</a>
                                                @elseif(Auth::user()->hasPermission(['admin.simese.update', 'orders.simese.update', 'coordinations.simese.update']))
                                                {{-- Caso la dependencia del usuario ya cargo documentos simese --}}
                                                <a href="{{ route('orders.simese.edit', $order->id, 0) }}" class="btn btn-warning">Editar Documentos</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab6" role="tabpanel">
                                            <label class="col-form-label f-w-600">Archivos cargados al pedido:</label>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descripción</th>
                                                        <th>Dependencia</th>
                                                        <th>Fecha/Hora</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i=0; $i < count($other_files); $i++)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        <td>{{ $other_files[$i]->description }}</td>
                                                        <td>{{ $other_files[$i]->dependency->description }}</td>
                                                        <td>{{ $other_files[$i]->updated_atDateFormat() }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/files/'.$other_files[$i]->file) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                            <a href="{{ route('orders.files.download', $other_files[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa fa-download"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endfor
                                                        @for ($i=0; $i < count($user_files); $i++)
                                                        <tr>
                                                            <td>{{ $i+1 }}</td>
                                                            <td>{{ $user_files[$i]->description }}</td>
                                                            <td>{{ $user_files[$i]->dependency->description }}</td>
                                                            <td>{{ $user_files[$i]->updated_atDateFormat() }}</td>
                                                            <td>
                                                                <a href="{{ asset('storage/files/'.$user_files[$i]->file) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                                <a href="{{ route('orders.files.download', $user_files[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa fa-download"></i></a>
                                                                <button title="Eliminar Archivo" onclick="deleteFile({{ $user_files[$i]->id }})" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endfor
                                                </tbody>
                                            </table>
                                            <div class="text-right">
                                                <a href="{{ route('orders.files.create', $order->id) }}" class="btn btn-primary">Cargar Archivos</a>
                                            </div>
                                                {{-- Mostrar esto una vez que Adjudicaciones haya notificado al Oferente --}}
                                                @if ($order->actual_state == 65)
                                                    <div class="col-sm-10 text-center">
                                                        {{-- <h6>Adjuntar: Nota de Adjudicación</h6> --}}
                                                        <h5><p style="font-size: 18px; font-weight: bold; color:#FF0000;background-color:yellow">Adjuntar: Nota de Adjudicación</p></h5>
                                                    </div>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5 style="font-size: 14px; font-weight: bold; color:red" class="text-left">Movimientos del pedido</h5>
                                </div>
                                <div class="card-block">
                                    <div class="latest-update-box">
                                    @foreach ($order->ordersOrderStates()->orderBy('id', 'desc')->get() as $item)
                                        <div class="row p-t-20 p-b-30 borde-alternado">
                                            <div class="col-auto text-right update-meta p-r-0">
                                                <i class="update-icon ring"></i>
                                            </div>
                                            <div class="col p-l-5">
                                                <a href="javascript:void(0);">
                                                    <h6 style="font-size: 14px; font-weight: bold; color:Black">{{ $item->orderState->id}}-{{ $item->orderState->description}}</h6>
                                                </a>
                                                {{-- <p class="text-muted m-b-0">{{ $item->creatorUser->getFullName()}}</p> --}}
                                                <p style="font-size: 14px; font-weight: bold; color:Black">{{ $item->creatorUser->getFullName()}}</p>
                                                <small style="font-size: 13px; font-weight: bold; color:Black">Fecha: {{ $item->createdAtDateFormat()}}</small>
                                            </div>
                                        </div>
                                    @endforeach
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
$(document).ready(function(){
    deleteOrders = function(order){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar el registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/orders/'+order,
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.href =  '/orders/';
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
            });
          }
        }
      );
    };

    deriveOrder = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar para Procesar Pedido?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar para Procesar Pedido?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveOrder/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deriveOrderPedido = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar Pedido para Revisión en DGAF?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Pedido para Revisión en DGAF?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveOrderPedido/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deriveOrderPAC = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar para procesar Pedido en Planificación (PAC)?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Pedido a Planificación (PAC)?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveOrderPAC/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deriveOrderPCYE = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar para procesar Pedido en Procesos Complementarios y Excepciones?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Pedido a Procesos Complementarios y Excepciones?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveOrderPCYE/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deriveAdjudica = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar para Procesar Adjudicación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#55dd6b",
            confirmButtonText: "Derivar para Procesar Adjudicación?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveAdjudica/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };


    recibeOrder = function(order_id){
      swal({
            title: "Atención",
            text: "Recibir Pedido para Dictaminar PBC?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Recibir Pedido para Dictamen de PBC",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/recibeOrder/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    recibeOrderCVE = function(order_id){
      swal({
            title: "Atención",
            text: "Recibir Pedido de CVE para Dictaminar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Recibir Pedido de CVE para Dictaminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/recibeOrderCVE/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };


    deriveDictamen = function(order_id){
      swal({
            title: "Atención",
            text: "Está agregado el Dictamen de PBC?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Dictamen?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveDictamen/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deriveDictamenEVAL = function(order_id){
      swal({
            title: "Atención",
            text: "Está verificado el Dictamen de Evaluación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Dictamen Verificado",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveDictamenEVAL/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    // deriveDictamen = function(order_id){
    //   swal({
    //         title: "Atención",
    //         text: "Derivar Dictamen de PBC?",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Derivar Dictamen de PBC?",
    //         cancelButtonText: "Cancelar",
    //     },
    //     function(isConfirm){
    //       if(isConfirm){
    //         $.ajax({
    //             url : '/coordinations/deriveDictamen/'+order_id,
    //             method: 'POST',
    //             data: '_token='+'{{ csrf_token() }}',
    //             success: function(data){
    //                 try{
    //                     response = (typeof data == "object") ? data : JSON.parse(data);
    //                     if(response.status == "success"){
    //                         swal({
    //                             title: "Éxito!",
    //                             text: response.message,
    //                             type: "success"
    //                         },
    //                         function(isConfirm){
    //                             location.reload();
    //                         });
    //                     }else{
    //                         swal("Error!", response.message, "error");
    //                     }
    //                 }catch(error){
    //                     swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
    //                     console.log(error);
    //                 }
    //             },
    //             error: function(error){
    //                 swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
    //                 console.log(error);
    //             }
    //         });
    //       }
    //     }
    //   );
    // };

    deriveDictamenCVE = function(order_id){
      swal({
            title: "Atención",
            text: "Derivar Dictamen de Pedido CVE?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Dictamen de Pedido CVE?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveDictamenCVE/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    recibeOrderEVAL = function(order_id){
      swal({
            title: "Atención",
            text: "Recibir Pedido para Dictaminar Evaluación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Recibir Pedido para Dictamen de Evaluación",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/recibeOrderEVAL/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };


    deriveDictamenEVAL = function(order_id){
      swal({
            title: "Atención",
            text: "Esta adjunto el Dictamen de Evaluación de Ofertas?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Derivar Dictamen de Evaluación de Ofertas?",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/coordinations/deriveDictamenEVAL/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    anuleDerive = function(order_id){
      swal({
            title: "Atención",
            text: "Desea anular derivación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, Anular derivación",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                url : '/orders/anuleDerive/'+order_id,
                method: 'POST',
                data: '_token='+'{{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            swal({
                                title: "Éxito!",
                                text: response.message,
                                type: "success"
                            },
                            function(isConfirm){
                                location.reload();
                            });
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "OcurrióOOOO un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
          }
        }
      );
    };

    deleteFile = function(file){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar el registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/orders/files/'+file+'/delete/',
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
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
