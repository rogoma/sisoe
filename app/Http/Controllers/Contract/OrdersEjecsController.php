<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Order;
use App\Models\Contract;
use App\Models\Policy;
use App\Models\Item;
use App\Models\File;
use App\Models\Department;
use App\Models\District;
use App\Models\Level5CatalogCode;
use App\Models\OrderPresentation;
use App\Models\OrderMeasurementUnit;
use App\Models\OrderState;
use App\Models\Component;
use Brick\Math\Internal\Calculator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;
use App\Exports\OrdersExport;
use App\Exports\OrdersExport2;
use App\Exports\OrdersExport3;

class OrdersEjecsController extends Controller
{
    protected $postMaxSize;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.orders.index','orders.orders.index'];
        $create_permissions = ['admin.orders.create', 'orders.orders.create'];
        $update_permissions = ['admin.orders.update', 'orders.orders.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update

        // obtenemos el tamaño permitido de subida de archivos del servidor
        if (is_numeric(ini_get('post_max_size'))) {
            $postMaxSize = ini_get('post_max_size');
        }else{
            $metric = strtoupper(substr(ini_get('post_max_size'), -1));
            $postMaxSize = (int) ini_get('post_max_size');

            switch ($metric) {
                case 'K':
                    $postMaxSize = $postMaxSize * 1024;
                    break;
                case 'M':
                    $postMaxSize = $postMaxSize * 1048576;
                    break;
                case 'G':
                    $postMaxSize = $postMaxSize * 1073741824;
                    break;
                default:
                    $postMaxSize = 8 * 1024 * 1024;
                    break;
            }
        }
        // $this->postMaxSize = $postMaxSize;
        //MÁXIMO PERMITIDO 2 MEGAS POR CADA ARCHIVO
        $this->postMaxSize = 1048576 * 2;
    }

    /**
     * Listado de todos los ítems de un pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.index','process_orders.items.index','derive_orders.items.index','plannings.items.index']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtenemos los items del pedido
        $items = $order->items;
        return view('order.items.index', compact('order', 'items'));
    }

    //Para mostrar Planillas EXCEL Región Oriental guardado en el Proyecto con formato ZIP
    // public function ArchivoPedido(){
    //     header("Content-type: application/zip");
    //     header("Content-Disposition: inline; filename=Planillas Región Oriental.zip");        
    //     readfile("files/Planillas Región Oriental.zip");
    // }

    //Para mostrar Planillas EXCEL Región Occidental guardado en el Proyecto con formato ZIP
    // public function ArchivoPedido2(){
    //     header("Content-type: application/zip");
    //     header("Content-Disposition: inline; filename=Planillas Región Occidental.zip");        
    //     readfile("files/Planillas Región Occidental.zip");
    // }
    /**
     * BUscar codigos de catalogo 5
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // obtenemos los parametros del request
        $search = $request->input('search');

        // en caso de no haberse enviado nada retornamos
        if(empty($search)){
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search = '%'.$search.'%';
        $sql = "SELECT * FROM level5_catalog_codes WHERE code LIKE :search OR lower(description) LIKE lower(:search) LIMIT 10";
        $bindings = array("search" => $search);
        $codigos = DB::select($sql, $bindings);

        // retornamos
        return response()->json($codigos);
    }

    /**
     * BUscar codigos de catalogo 4
     *
     * @return \Illuminate\Http\Response
     */
    public function search4(Request $request)
    {
        // obtenemos los parametros del request
        $search4 = $request->input('search4');

        // en caso de no haberse enviado nada retornamos
        if(empty($search4)){
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search4 = '%'.$search4.'%';
        $sql = "SELECT * FROM level4_catalog_codes WHERE code LIKE :search4 OR lower(description) LIKE lower(:search4) LIMIT 10";
        $bindings = array("search4" => $search4);
        $codigos = DB::select($sql, $bindings);

        // retornamos
        return response()->json($codigos);
    }

    /**
     * Formulario de agregacion de pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        // PARA NUMERAR ORDENES DE ACUERDO A LA CANTIDAD
        $order = Order::where('contract_id', $contract_id)->count();
        $nextContractNumber = $order + 1;

        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        // if($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create']) || $contract->dependency_id == $request->user()->dependency_id){
        if($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create'])){
            // return view('contract.contracts.show', compact('contract','user_files_pol','user_files_con','other_files_pol','other_files_con'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para agregar órdenes.');
        }

        // Chequeamos que haya Fiscal asignado para proceder
        // if($contract->fiscal1_id != null ){

        // }else{
        //     return back()->with('error', 'Para generar una Orden debe asignar un Fiscal');
        // }

        // $components = Component::all();
        $components = Component::orderBy('id')->get();//ordenado por id componente
        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();

        return view('contract.orders.create', compact('contract','order_states','components',
        'post_max_size', 'nextContractNumber', 'departments','districts'));
    }

    // PARA ANIDAR COMBOS
    public function fetchDistricts(Request $request)
    {
        $districts = District::where('department_id', $request->department_id)->get();
        return response()->json($districts);
    }


    /**
     * Formulario de agregacion de items cargando archivo excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadExcel(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // return view('order.items.uploadExcel', compact('order'));
        return view('order.items.uploadExcel', compact('order'));
    }


    /**
     * Funcionalidad de guardado del pedido de ítemes Contrato Abierto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $contract_id)
    {
        $rules = array(
            // 'number' => 'numeric|required|orders,number',
            // 'total_amount' => 'nullable|string|max:9223372036854775807',
            // 'sign_date' => 'date_format:d/m/Y',
            'component_id' => 'required|numeric',
            'order_state_id'=> 'required|numeric',
            'locality' => 'required|string|max:100',
            'comments' => 'nullable|max:300',
            'plazo' => 'required|numeric',
            'department_id' => 'required',
            'district_id' => 'required|numeric'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = new Order;
        $order->contract_id = $contract_id;
        $order->number = $request->input('number');
        $order->sign_date = $request->filled('sign_date') ? date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date')))): null;        
        $order->locality = $request->input('locality');
        $order->component_id = $request->input('component_id');
        $order->order_state_id = $request->input('order_state_id');
        $order->total_amount = 0;
        $order->comments = $request->input('comments');
        $order->plazo = $request->input('plazo');
        $order->district_id = $request->input('district_id');
        $order->creator_user_id = $request->user()->id;  // usuario logueado
        $order->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Orden agregada correctamente'); // Caso usuario posee rol pedidos
    }


    /**
     * Formulario de modificacion de pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $contract_id, $order_id)
    {
        $contract = Contract::findOrFail($contract_id);
        // $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.orders.update','orders.orders.update']) &&  $contract->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $order = Order::findOrFail($order_id);
        $components = Component::orderBy('id')->get();//ordenado por id componente
        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();

        return view('contract.orders.update', compact('contract','order','components','order_states','departments','districts'));
    }


    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 2 = CERRADO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contract_id, $order_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $order = Order::findOrFail($order_id);

        $rules = array(
            // 'number' => 'numeric|required|unique:orders,number',
            // 'total_amount' => 'nullable|string|max:9223372036854775807',
            // 'sign_date' => 'date_format:d/m/Y|required|',
            'component_id' => 'required|numeric',
            'order_state_id'=> 'required|numeric',
            'locality' => 'required|string|max:100',
            'comments' => 'nullable|max:300',
            'plazo' => 'required|numeric',
            // 'department_id' => 'required',
            'district_id' => 'required|numeric'
        );

        // Valida los datos de entrada
        $validatedData = $request->validate($rules);

        // Actualiza la orden con los datos validados
        $order->update($validatedData);

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order->sign_date = $request->filled('sign_date') ? date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date')))): null;
        $order->locality = $request->input('locality');
        $order->component_id = $request->input('component_id');
        $order->order_state_id = $request->input('order_state_id');
        $order->comments = $request->input('comments');
        $order->plazo = $request->input('plazo');
        $order->district_id = $request->input('district_id');
        $order->creator_user_id = $request->user()->id;  // usuario logueado
        $order->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Orden modificada correctamente'); // Caso usuario posee rol pedidos
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $contract_id, $item_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $order = Order::find($item_id);


        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.orders.delete','contracts.orders.delete']) && $order->contract->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para eliminar la póliza.', 'code' => 200], 200);
        }

        //ARREGLAR ESTO PARA QUE NO ELIMINE SI EXISTEN ITEMS O RUBROS
        // Chequeamos si existen items_oi referenciando al item
        // if($order->itemAwardHistories->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'Orden no puede eliminarse, posee carga de rubros, verificar ', 'code' => 200], 200);
        // }

        // Cambia a estado 5 = "eliminado" /no mostrara en listado de ordenes /no piere nro secuencial de ordenes
        $order->order_state_id = 5;
        $order->save();
        // $order->delete();

        session()->flash('status', 'success');
        session()->flash('message', 'Se ha eliminado la orden ' . $order->number);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado la póliza'. $order->number,
            'code' => 200
        ], 200);

        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza eliminada correctamente'); // Caso usuario posee rol pedidos
        // return response()->json(['status' => 'success', 'message' => 'Póliza eliminada correctamente', 'code' => 200], 200);
        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza modificada correctamente'); // Caso usuario posee rol pedidos
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO ABIERTO.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation', 'order_measurement_unit',
            'unit_price','min_quantity','max_quantity','total_amount_min','total_amount');

            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:1',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'min_quantity' => 'numeric|required|max:2147483647',
                    'max_quantity' => 'numeric|required|max:2147483647',
                    'total_amount_min' => 'numeric|required|max:9223372036854775807',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 1){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO ABIERTO');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                // Chequea si existe el código de catálogo5
                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            // $order_amount = $order->total_amount;

            // CONTROLAMOS SI MONTO DE TOTAL ES IGUAL A TOTAL SUMATORIA DE ITEMS
            // if ($order_amount <> $order_amount_items) {
            //     $validator->errors()->add('order_measurement_unit', 'Monto de Ítems: '.$order_amount_items.', no es igual a monto del Pedido, VERIFIQUE ARCHIVO EXCEL');
            //     return back()->withErrors($validator)->withInput()->with('fila', $row);
            // }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->min_quantity = $item['min_quantity'];
                $new_item->max_quantity = $item['max_quantity'];
                $new_item->total_amount_min = $item['total_amount_min'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                //SI ITEM ES AGREGADO DESPUÉS DE PEDIDO EL MONTO DE ITEM QUEDA COMO MONTO TOTAL Y OG1
                $order->amount1 = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO CERRADO.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel2(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        // var_dump($order->items->count());exit();

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation','order_measurement_unit',
            'quantity', 'unit_price', 'total_amount');
            // 'unit_price','min_quantity','max_quantity','total_amount_min','total_amount');


            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:2',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'level5_catalog_code' => 'string|required|max:200',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'quantity' => 'numeric|required|max:2147483647',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 2){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO CERRADO');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO CERRADO MMIN Y MMAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel3(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation','order_measurement_unit',
            'quantity', 'unit_price', 'total_amount_min','total_amount');
            // max_quuantity es igual a quantity


            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:3',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'level5_catalog_code' => 'string|required|max:200',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'quantity' => 'numeric|required|max:2147483647',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'total_amount_min' => 'numeric|required|max:2147483647',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 3){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO CERRADO MMIN Y MMAX.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            // $order_amount = $order->total_amount;

            // CONTROLAMOS SI MONTO DE TOTAL ES IGUAL A TOTAL SUMATORIA DE ITEMS
            // if ($order_amount <> $order_amount_items) {
            //     $validator->errors()->add('order_measurement_unit', 'Monto de Ítems: '.$order_amount_items.', no es igual a monto del Pedido, VERIFIQUE ARCHIVO EXCEL');
            //     return back()->withErrors($validator)->withInput()->with('fila', $row);
            // }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->total_amount_min = $item['total_amount_min'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

}
