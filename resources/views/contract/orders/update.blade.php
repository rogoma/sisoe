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
                                        <div class="col-sm-10 text-left">
                                            <h4>Llamado:
                                                {{ $contract->description . ' - ' . $contract->modality->description . ' - ' . $contract->provider->description }}
                                                Modificar Orden del Contrato N° {{ $contract->number_year }}
                                            </h4>
                                        </div>

                                        
                                        <br><br>
                                        <label id="fecha_actual" name="fecha_actual"
                                            style="font-size: 20px;color: #FF0000;float: left;"
                                            for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                        <h3 style="text-align: center;">Modificar Orden</h3>
                                    </div>
                                    <div class="card-block">
                                        <form method="POST"
                                            action="{{ route('contracts.orders.update', [$contract->id, $order->id]) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="container">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                {{-- <div id="mensaje-componente" style="color: red;"></div> --}}
                                                <div id="mensaje-componente" style="color: red; font-size: 18px; padding: 10px;"></div>
                                                <div id="mensaje-componente2" style="color: red; font-size: 18px; padding: 10px;"></div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="number" class="col-form-label">N° de Orden</label>
                                                        <input type="text" id="number" name="number_display"
                                                            class="form-control @error('number') is-invalid @enderror"
                                                            value="{{ old('number', $order->number) }}" maxlength="23"
                                                            disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ old('number', $order->number) }}">
                                                        @error('number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="total_amount" class="col-form-label">Monto</label>
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control @error('total_amount') is-invalid @enderror"
                                                            value="{{ old('total_amount', number_format($order->total_amount, 0, ',', '.')) }}"
                                                            maxlength="23" disabled>
                                                        @error('total_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="department_id" class="col-form-label">Departamento</label>
                                                        <select id="department_id" name="department_id"
                                                            class="form-control @error('department_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Departamento ---</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    @if ($department->id == old('department_id', $order->department_id)) selected @endif>
                                                                    {{ $department->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('department_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="district_id" class="col-form-label">Distrito</label>
                                                        <select id="district_id" name="district_id"
                                                            class="form-control @error('district_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Distrito ---</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    @if ($district->id == old('district_id', $order->district_id)) selected @endif>
                                                                    {{ $district->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('district_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <label for="locality_id" class="col-form-label">Localidad</label>
                                                        <select id="locality_id" name="locality_id"
                                                            class="form-control @error('locality_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Localidad ---</option>
                                                            @foreach ($localities as $locality)
                                                                <option value="{{ $locality->id }}"
                                                                    @if ($locality->id == old('locality_id', $order->locality_id)) selected @endif>
                                                                    {{ $locality->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('locality_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                
                                                {{-- <div class="form-group row">
                                                    <label for="locality" class="col-sm-6 col-form-label">Localidad (Hasta
                                                        200 caracteres)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" id="locality" name="locality"
                                                            class="form-control @error('locality') is-invalid @enderror"
                                                            value="{{ old('locality', $order->locality) }}"
                                                            maxlength="200">
                                                        @error('locality')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> --}}

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label for="sign_date" class="col-form-label">Fecha acuse recibo Contratista</label>
                                                        <div class="input-group">
                                                            <input type="text" id="sign_date" name="sign_date"
                                                                {{-- DISABLED INGRESO DE FECHA SI LA ORDEN NO TIENE DETALLE DE RUBROS --}}
                                                                class="form-control @error('sign_date') is-invalid @enderror"
                                                                @if ($order->items->count() > 0 && $order->order_state_id == 1) @endif
                                                                value="{{ old('sign_date', !empty($order->sign_date) ? date('d/m/Y', strtotime($order->sign_date)) : '') }}"
                                                                autocomplete="off"
                                                                @if ($order->items->count() == 0) disabled @endif>
                                                            <span class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="show('sign_date');"
                                                                    {{ empty($order->sign_date) ? 'disabled' : '' }}>
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                        @error('sign_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="component_id" class="col-form-label">Componente</label>                                                
                                                        
                                                        <!-- Campo oculto para enviar el valor cuando el select está deshabilitado -->
                                                        <input type="hidden" name="component_id" id="component_id_hidden" value="{{ old('component_id', $order->component_id) }}">
                                                    
                                                        <select id="component_id" name="component_id"
                                                            class="form-control @error('component_id') is-invalid @enderror"
                                                            @if ($order->items->count() > 0 && $order->order_state_id == 10) @endif
                                                            onchange="confirmComponentChange(this)">
                                                            <option value="">--- Seleccionar Componente ---</option>
                                                            @foreach ($components as $component)
                                                                <option value="{{ $component->id }}"
                                                                    @if ($component->id == old('component_id', $order->component_id)) selected @endif>
                                                                    {{ $component->code }}-{{ $component->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    

                                                    <div class="col-sm-3">
                                                        <label for="plazo" class="col-form-label">Plazo de ejecución
                                                            (En días)</label>
                                                        <input type="text" id="plazo" name="plazo"
                                                            class="form-control @error('plazo') is-invalid @enderror"
                                                            @if ($order->items->count() > 0 && $order->order_state_id == 1) @endif
                                                            value="{{ old('plazo', $order->plazo) }}" maxlength="3">
                                                        @error('plazo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                {{-- </div> --}}

                                                {{-- <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label for="order_state_id" class="col-form-label">Estado de la Orden</label>
                                                        <select id="order_state_id" name="order_state_id"
                                                            class="form-control @error('order_state_id') is-invalid @enderror"
                                                            @if ($order->order_state_id == 1) disabled @endif>>
                                                            <option value="">--- Seleccionar Estado ---</option>
                                                            @foreach ($order_states as $order_state)
                                                                <option value="{{ $order_state->id }}"
                                                                    @if ($order_state->id == old('order_state_id', $order->order_state_id)) selected @endif>
                                                                    {{ $order_state->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('order_state_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="col-sm-3">
                                                        <label for="sign_date_fin" class="col-form-label">Fecha final de Orden</label>
                                                        <div class="input-group">                                                            
                                                            <input 
                                                                type="text" 
                                                                id="sign_date_fin" 
                                                                name="sign_date_fin"                                                                
                                                                class="form-control @error('sign_date_fin') is-invalid @enderror"
                                                                value="{{ old('sign_date_fin', isset($order->sign_date_fin) ? date('d/m/Y', strtotime($order->sign_date_fin)) : '') }}"
                                                                autocomplete="off"
                                                                @if ($order->sign_date === null) disabled @endif>                                                    
                                                            <span class="input-group-append">
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-outline-secondary" 
                                                                    onclick="show('sign_date_fin');" 
                                                                    {{ empty($order->sign_date_fin) ? 'disabled' : '' }}>
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                        @error('sign_date_fin')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- <div class="form-group @error('file') has-danger @enderror">                                                        
                                                        <label class="col-form-label">Cargar Archivo: <h7>(Tipo de archivos permitidos: WORD, PDF)</h7></label>
                                                        <input id="file" type="file" class="form-control" name="file">
                                                        @error('file')
                                                            <div class="col-form-label">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="col-sm-9">
                                                        <label for="reference" class="col-form-label">Referencia (Hasta 500 caracteres)</label>
                                                        <textarea id="reference" name="reference" class="form-control @error('reference') is-invalid @enderror"
                                                            maxlength="500">{{ old('reference', $order->reference) }}</textarea>
                                                        @error('reference')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <label for="comments" class="col-form-label">Comentarios (Hasta
                                                            500 caracteres)</label>
                                                        <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror"
                                                            maxlength="500">{{ old('comments', $order->comments) }}</textarea>
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
                                            <br>
                                            <br><p><span style="color: red;">REFERENCIAS: PLAZO EN DIAS:</span></p>
                                            <ul style="color: red;">
                                                REFERENCIAS: PLAZO EN DIAS:
                                            * Fuente de Provisión: 30 *
                                            Equipamiento: 30 *
                                            Tanque: 60 *
                                            Caseta: 30 *
                                            Extensión de línea: 45 *
                                            Red de distribución: 30 *
                                            Aductora: 30 *
                                            Cercado Perim.: 30
                                            </ul>
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
        $(document).ready(function() {            
        // Evento para cargar distritos al cambiar el departamento
        $('#department_id').on('change', function() {
            var departmentId = $(this).val();
            var districtDropdown = $('#district_id');
            var localityDropdown = $('#locality_id');

            // Limpia el combo de distritos
            districtDropdown.empty().append('<option value="">--- Seleccionar Distrito ---</option>');
            localityDropdown.empty().append('<option value="">--- Seleccionar Localidad ---</option>');

            if (departmentId) {
                // Realiza la solicitud AJAX para obtener distritos
                $.ajax({
                    url: '/fetch-districts',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    success: function(data) {
                        // Agrega las opciones al combo de distritos
                        $.each(data, function(key, district) {
                            districtDropdown.append('<option value="' + district
                                .id + '">' + district.description + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching districts:', xhr.responseText);
                    }
                });
            }
        });

        // Evento para cargar localidades al cambiar el distrito
        $('#district_id').on('change', function () {
            var districtId = $(this).val();
            var localityDropdown = $('#locality_id');

            // Limpia el combo de localidades
            localityDropdown.empty().append('<option value="">--- Seleccionar Localidad ---</option>');

            if (districtId) {
                // Realiza la solicitud AJAX para obtener localidades
                $.ajax({
                    url: '/fetch-localities',
                    type: 'GET',
                    data: {
                        district_id: districtId
                    },
                    success: function (data) {
                        // Agrega las opciones al combo de localidades
                        $.each(data, function (key, locality) {
                            localityDropdown.append('<option value="' + locality.id + '">' + locality.description + '</option>');
                        });
                    },
                    error: function (xhr) {
                        console.error('Error fetching localities:', xhr.responseText);
                    }
                });
            }
        });

            // Inicialización de Select2
            $('#component_id, #order_state_id, #department_id, #district_id, #locality_id').select2();

            // Configuración de Datepicker
            $('#sign_date, #sign_date_fin').datepicker({
                language: 'es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "today" // Restringe la selección de fechas futuras
            });

            // Control de habilitación de sign_date_fin
            function toggleSignDateFin() {
                let signDate = $('#sign_date');
                let signDateFin = $('#sign_date_fin');

                if ($.trim(signDate.val()) === "") {
                    signDateFin.val("").prop('disabled', true);
                } else {
                    signDateFin.prop('disabled', false);
                }
            }

            // Ejecutar la función al cargar la página
            toggleSignDateFin();

            // Evento al cambiar sign_date
            $('#sign_date').on('change', toggleSignDateFin);

            // Evento al cambiar sign_date_fin (deshabilitar sign_date si se selecciona)
            $('#sign_date_fin').on('change', function () {
                // $('#sign_date').prop('disabled', $.trim($(this).val()) !== "");
            });

            // Control de habilitación del campo de archivo
            function toggleFileUpload() {
                let fileInput = $('#file');
                let signDateFin = $('#sign_date_fin');

                fileInput.prop('disabled', $.trim(signDateFin.val()) === '');
            }

            // Ejecutar la función al cargar la página para mantener el estado correcto
            toggleFileUpload();

            // Evento al cambiar sign_date_fin
            $('#sign_date_fin').on('change', toggleFileUpload);

            // Manejo del cambio en el select component_id
            let select = document.getElementById("component_id");
            select.dataset.previousValue = select.value; // Guardamos el valor actual
            let orderStateId = {{ $order->order_state_id ?? 'null' }}; // Pasar el estado de la orden desde PHP

            $('#component_id').on('change', function() {
                let previousValue = this.dataset.previousValue; // Valor antes del cambio
                let newValue = this.value; // Nuevo valor seleccionado
                
                // if (newValue !== previousValue && (orderStateId === 10 || orderStateId === 1)) {
                //     let selectedOptionText = this.options[this.selectedIndex].text;
                //     alert("Cambiado por el Componente: " + selectedOptionText +" "+ " Edite rubros para recalcular valores del nuevo Componente");
                //     this.dataset.previousValue = newValue; // Actualizamos el valor guardado
                // }

                if (newValue !== previousValue && (orderStateId === 10 || orderStateId === 1)) {
                    let selectedOptionText = this.options[this.selectedIndex].text;
                    // let mensaje = "Cambiado por el Componente: " + selectedOptionText + " Edite rubros para recalcular valores del nuevo Componente";
                    let mensaje = "Cambiado por el Componente: " + selectedOptionText;
                    let mensaje2 = "Edite rubros para recalcular valores del nuevo Componente";
                    document.getElementById('mensaje-componente').textContent = mensaje;
                    document.getElementById('mensaje-componente2').textContent = mensaje2;
                    // O si prefieres usar html dentro del div
                    // document.getElementById('mensaje-componente').innerHTML = '<span style="color: red;">' + mensaje + '</span>';
                    this.dataset.previousValue = newValue;
                }
                
            });

        });
    </script>
@endpush






{{-- @push('scripts')
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

            $('#sign_date_fin').datepicker({
                language: 'es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
@endpush --}}
