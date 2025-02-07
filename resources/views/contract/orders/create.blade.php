@extends('layouts.app')

{{-- @extends('layouts.app') --}}

@section('content')

    <head>
        <title>Crear Orden</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

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
                                <a href="{{ route('contracts.show', $contract->id) }}">Contrato N°
                                    {{ $contract->number_year }}</a>
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
                                        <label id="fecha_actual" name="fecha_actual"
                                            style="font-size: 20px;color: #FF0000;float: left;"
                                            for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                        <h3 style="text-align: center;">Agregar Orden</h3>
                                    </div>
                                    <div class="card-block">
                                        <form method="POST" action="{{ route('contracts.orders.store', $contract->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="container">

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="number" class="col-form-label">N° de Orden</label>
                                                        <input type="text" id="number" name="number_display"
                                                            class="form-control @error('number') is-invalid @enderror"
                                                            value="{{ old('number', $nextContractNumber) }}" maxlength="23"
                                                            disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ old('number', $nextContractNumber) }}">
                                                        @error('number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="total_amount" class="col-form-label">Monto</label>
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control @error('total_amount') is-invalid @enderror"
                                                            value="{{ old('total_amount', 0) }}" maxlength="23" disabled>
                                                        @error('total_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="component_id" class="col-form-label">Componente</label>
                                                    <select id="component_id" name="component_id"
                                                        class="form-control @error('component_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Componente ---</option>
                                                        @foreach ($components as $component)
                                                            <option value="{{ $component->id }}"
                                                                @if ($component->id == old('component_id')) selected @endif>
                                                                {{ $component->code }}-{{ $component->description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('component_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button id="showItemsButton" type="button" class="btn btn-primary">Mostrar Rubros</button>
                                    </div>
                                    <br>

                                    {{-- <div class="text-center mt-4">                                        
                                        <button id="saveButton1" type="submit" class="btn btn-primary">Grabar Orden</button>
                                    </div> --}}
                                    <br>
                                    </form>
                                </div>

                                <!-- Tabla para mostrar los items -->
                                <div class="container mt-4">
                                    <table id="itemsTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
@endsection

<!-- Agregar jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#department_id').on('change', function() {
                var departmentId = $(this).val();
                $('#district_id').empty().append('<option value="">--- Seleccionar Distrito ---</option>');

                if (departmentId) {
                    $.ajax({
                        url: '/fetch-districts',
                        type: 'GET',
                        data: {
                            department_id: departmentId
                        },
                        success: function(data) {
                            $.each(data, function(key, district) {
                                $('#district_id').append('<option value="' + district
                                    .id +
                                    '">' + district.description + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching districts:', xhr.responseText);
                        }
                    });
                }
            });

            let table = $('#itemsTable').DataTable();

            $('#showItemsButton').on('click', function() {
                let componentId = $('#component_id').val();

                if (!componentId) {
                    alert('Seleccione un componente primero.');
                    return;
                }

                $.ajax({
                    url: '{{ route('get.items') }}',
                    type: 'GET',
                    data: {
                        component_id: componentId
                    },
                    success: function(response) {
                        table.clear().rows.add(response.data).draw();
                    },
                    error: function() {
                        alert('Error al obtener los datos.');
                    }
                });
            });

            // Validar antes de guardar
            // $('#saveButton').on('click', function(event) {
            //     var districtId = $('#district_id').val();
            //     if (!districtId) {
            //         event.preventDefault(); // Evita que el formulario se envíe
            //         alert('Por favor, seleccione un distrito antes de guardar.');
            //     } else {
            //         // Aquí puedes agregar la lógica para proceder con el guardado
            //         //console.log('Formulario válido. Procediendo con el guardado...');
            //     }
            // });

            $('#component_id').select2();
            $('#order_state_id').select2();
            $('#department_id').select2();
            $('#district_id').select2();

            $('#sign_date').datepicker({
                language: 'es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
@endpush
