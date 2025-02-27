<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
use App\Models\ItemContract;
use Brick\Math\Internal\Calculator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
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
        $index_permissions = ['admin.orders.index', 'orders.orders.index'];
        $create_permissions = ['admin.orders.create', 'orders.orders.create'];
        $update_permissions = ['admin.orders.update', 'orders.orders.update'];

        $this->middleware('checkPermission:' . implode(',', $index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:' . implode(',', $create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:' . implode(',', $update_permissions))->only(['edit', 'update']);   // Permiso para update

        // obtenemos el tamaño permitido de subida de archivos del servidor
        if (is_numeric(ini_get('post_max_size'))) {
            $postMaxSize = ini_get('post_max_size');
        } else {
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
        if (
            !$request->user()->hasPermission(['admin.items.index', 'process_orders.items.index', 'derive_orders.items.index', 'plannings.items.index']) &&
            $order->dependency_id != $request->user()->dependency_id
        ) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtenemos los items del pedido
        $items = $order->items;
        return view('order.items.index', compact('order', 'items'));
    }


    public function show(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        

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
        if (empty($search)) {
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search = '%' . $search . '%';
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
        if (empty($search4)) {
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search4 = '%' . $search4 . '%';
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

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        // if($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create']) || $contract->dependency_id == $request->user()->dependency_id){
        if ($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create'])) {
            // return view('contract.contracts.show', compact('contract','user_files_pol','user_files_con','other_files_pol','other_files_con'));
        } else {
            return back()->with('error', 'No tiene los suficientes permisos para agregar órdenes.');
        }

        // Obtener los component_id asociados al contrato desde ItemContract
        $componentIds = ItemContract::where('contract_id', $contract_id)
            ->pluck('component_id'); // Extrae solo los IDs de los componentes

        // Obtener los componentes filtrados por los IDs obtenidos
        if ($request->user()->position->id == 11) { //Fiscal de Geología id 11
            $components = Component::whereIn('id', $componentIds)
                ->whereIn('id', [1, 2]) // Filtra los IDs 1 y 2 (Componentes 1.1 Pozo A y 1.2 Pozo B)
                ->orderBy('id')
                ->get();
        } else { //Otros Fiscales (Obras y Electromecánica)
            $components = Component::whereIn('id', $componentIds) //Muestra todos los componentes
                ->whereNotIn('id', [1, 2]) // Excluye los IDs 1 y 2
                ->orderBy('id')
                ->get();
        }

        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();
        $item_contract = ItemContract::where('contract_id', $contract_id)->get();

        return view('contract.orders.create', compact(
            'contract',
            'order_states',
            'components',
            'departments',
            'districts'
        ));
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
        if (
            !$request->user()->hasPermission(['admin.items.create']) &&  $order->dependency_id != $request->user()->dependency_id
        ) {
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
        $request->validate([
            'component_id' => 'required|integer',
            'plazo' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('component_id'), [1, 2, 3, 4, 5, 6, 7, 10, 11, 14, 15, 16, 17]) && $value > 30) {
                        $fail('El plazo no puede ser mayor a 30 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [12, 13]) && $value > 45) {
                        $fail('El plazo no puede ser mayor a 45 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [8, 9]) && $value > 60) {
                        $fail('El plazo no puede ser mayor a 60 días para este Sub-componente');
                    }
                },
            ],
        ]);

        $rules = array(
            'number' => 'required|integer|min:1', // Asegúrate de que sea un número válido
            // 'total_amount' => 'nullable|string|max:9223372036854775807',
            // 'sign_date' => 'date_format:d/m/Y',
            'component_id' => 'required|numeric',
            // 'order_state_id'=> 'required|numeric',
            'locality' => 'required|string|max:100',
            'reference' => 'nullable|max:500',
            'comments' => 'nullable|max:200',
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
        $order->component_id = $request->input('component_id');
        $component = Component::find($order->component_id);  // Assuming you have a Component model
        $componentCode = $component ? $component->code : null; // Handle the case where the component is not found                
        $order->component_code = $componentCode;
        $order->number = $request->input('number');
        $order->sign_date = $request->filled('sign_date') ? date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date')))) : null;
        $order->locality = $request->input('locality');
        $order->component_id = $request->input('component_id');
        //CUANDO SE GRABA POR VEZ PRIMERA ASUME ESTADO 10= Pendiente Fecha Acuse recibo Contratista
        $order->order_state_id = 11; // toma estado pendiente de carga de rubros
        $order->total_amount = 0;
        $order->reference = $request->input('reference');
        $order->comments = $request->input('comments');
        $order->plazo = $request->input('plazo');
        $order->district_id = $request->input('district_id');
        $order->creator_user_id = $request->user()->id;  // usuario logueado
        $order->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Orden agregada correctamente'); // Caso usuario posee rol pedidos
    }

    //PARA CALCULA NUMERO DE ORDEN DE ACUERDO AL COMPONENTE
    public function getMaxNumber(Request $request)
    {
        $componentId = $request->query('component_id');

        if (!$componentId) {
            return response()->json(['success' => false, 'message' => 'Component ID no proporcionado.']);
        }

        $maxNumber = Order::where('component_id', $componentId)->max('number');


        return response()->json([
            'success' => true,
            'number' => $maxNumber,
        ]);
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
        if (!$request->user()->hasPermission(['admin.orders.update', 'orders.orders.update']) &&  $contract->dependency_id != $request->user()->dependency_id) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtener los component_id asociados al contrato desde ItemContract
        $componentIds = ItemContract::where('contract_id', $contract_id)
            ->pluck('component_id'); // Extrae solo los IDs de los componentes

        // Obtener los componentes filtrados por los IDs obtenidos
        if ($request->user()->position->id == 11) { //Fiscal de Geología id 11
            $components = Component::whereIn('id', $componentIds)
                ->whereIn('id', [1, 2]) // Filtra los IDs 1 y 2 (Componentes 1.1 Pozo A y 1.2 Pozo B)
                ->orderBy('id')
                ->get();
        } else { //Otros Fiscales (Obras y Electromecánica)
            $components = Component::whereIn('id', $componentIds) //Muestra todos los componentes
                ->whereNotIn('id', [1, 2]) // Excluye los IDs 1 y 2
                ->orderBy('id')
                ->get();
        }

        $order = Order::findOrFail($order_id);
        // $components = Component::orderBy('id')->get(); //ordenado por id componente
        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();

        return view('contract.orders.update', compact('contract', 'order', 'components', 'order_states', 'departments', 'districts'));
    }


    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 2 = CERRADO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contract_id, $order_id)
    {
        $request->validate([
            'component_id' => 'required|integer',
            'plazo' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('component_id'), [1, 2, 3, 4, 5, 6, 7, 10, 11, 14, 15, 16, 17]) && $value > 30) {
                        $fail('El plazo no puede ser mayor a 30 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [12, 13]) && $value > 45) {
                        $fail('El plazo no puede ser mayor a 45 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [8, 9]) && $value > 60) {
                        $fail('El plazo no puede ser mayor a 60 días para este Sub-componente');
                    }
                },
            ],
        ]);

        $contract = Contract::findOrFail($contract_id);
        $order = Order::findOrFail($order_id);

        $rules = [
            'component_id' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request, $order) {
                    $existingRecord = DB::table('orders')
                        ->where('component_id', $request->input('component_id'))                        
                        ->where('number', $request->input('number'))
                        ->where('id', '!=', $order->id) // Permite ignorar el registro actual si se está editando
                        ->exists();
    
                    if ($existingRecord) {
                        $fail('Ya existe un una Orden con Sub-Componente-Nro, verifique');
                    }
                }
            ],
            'locality' => 'required|string|max:100',
            'reference' => 'nullable|string|max:500',
            'comments' => 'nullable|string|max:200',
            'plazo' => 'required|numeric',
            'district_id' => 'required|numeric',
        ];

        // Valida los datos de entrada
        $validatedData = $request->validate($rules);

        // Actualiza la orden con los datos validados
        $order->update($validatedData);

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // $order_actual = $order->order_state_id = $request->input('order_state_id');

        // 10 CONTRATISTA 11 RUBROS
        if (is_null($request->input('sign_date')) ) {
            $order->order_state_id = 10;
        }else{
            $order->order_state_id = 1;
        }

        //SI FECHA NO ES NULL Y ESTADO NO SE CAMBIO, SE CAMBIA A ESTADO 1 = "En curso"
        // if (!is_null($request->input('sign_date')) && $order->order_state_id == 11) {
        //     $order->order_state_id = 10;        
        // }

        

        // if (is_null($order->sign_date) && $order->order_state_id <> 10) {
        //     $order->order_state_id = 10;        
        // }

        // //SI FECHA ES NULL Y ESTADO ES DIFERENTE A 10 , SE CAMBIA A ESTADO 10 = "En curso"
        

        $order->sign_date = $request->filled('sign_date') ? date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date')))) : null;
        $order->locality = $request->input('locality');
        $order->component_id = $request->input('component_id');
        $component = Component::find($order->component_id);  // Assuming you have a Component model
        $componentCode = $component ? $component->code : null; // Handle the case where the component is not found                       
        $order->component_code = $componentCode;
        $order->number = $request->input('number');
        $order->reference = $request->input('reference');
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
        if (!$request->user()->hasPermission(['admin.orders.delete', 'orders.orders.delete']) && $order->contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para anular la orden.', 'code' => 200], 200);
        }

        //ARREGLAR ESTO PARA QUE NO ELIMINE SI EXISTEN ITEMS O RUBROS
        // Chequeamos si existen items_oi referenciando al item
        // if($order->itemAwardHistories->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'Orden no puede eliminarse, posee carga de rubros, verificar ', 'code' => 200], 200);
        // }

        // ANULAR Cambia a estado 5 = "Anulado" si es que Estado de la orden está en 1 (En curso)
        if ($order->order_state_id == 1) {

            $order->order_state_id = 5;
            $order->save();

            session()->flash('status', 'success');
            session()->flash('message', 'Orden anulada' . $order->number);

            return response()->json([
                'status' => 'success',
                'message' => 'Orden anulada correctamente' . $order->number,
                'code' => 200
            ], 200);
        } else {
            // DESANULAR Cambia a estado 1 = "En curso" si es que Estado de la orden está en 5 (Anulado)
            if ($order->order_state_id == 5) {

                $order->order_state_id = 1;
                $order->save();

                session()->flash('status', 'success');
                session()->flash('message', 'Orden Desanulada' . $order->number);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Orden Desanulada correctamente' . $order->number,
                    'code' => 200
                ], 200);
            }
        }


        // DESANULAR Cambia a estado 1 = "En curso" si es que Estado de la orden está en 5 (Anulado)
        // if ($order->order_state_id = 5) {

        //     $order->order_state_id = 1;
        //     $order->save();    

        //     session()->flash('status', 'success');
        //     session()->flash('message', 'Orden Desanulada' . $order->number);

        //     return response()->json([
        //     'status' => 'success',
        //     'message' => 'Orden Desanulada correctamente'. $order->number,
        //     'code' => 200
        //     ], 200);
        // }

        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza eliminada correctamente'); // Caso usuario posee rol pedidos
        // return response()->json(['status' => 'success', 'message' => 'Póliza eliminada correctamente', 'code' => 200], 200);
        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza modificada correctamente'); // Caso usuario posee rol pedidos
    }
}
