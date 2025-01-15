<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UsersController;

use App\Http\Controllers\Admin\PositionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\DependencyTypesController;
use App\Http\Controllers\Admin\ModalitiesController;
use App\Http\Controllers\Admin\OrderStatesController;
use App\Http\Controllers\Admin\UocTypesController;
use App\Http\Controllers\Admin\FundingSourcesController;
use App\Http\Controllers\Admin\FinancialOrganismsController;
use App\Http\Controllers\Admin\FinancialLevelsController;
use App\Http\Controllers\Admin\DependenciesController;
use App\Http\Controllers\Admin\ProvidersController;
use App\Http\Controllers\Admin\RegionsController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\DistrictsController;
use App\Http\Controllers\Admin\ExpenditureObjectsController;
use App\Http\Controllers\Admin\FilesController;
use App\Http\Controllers\Admin\OrderMeasurementUnitsController;
use App\Http\Controllers\Admin\OrderPresentationsController;
use App\Http\Controllers\Admin\ProgramMeasurementUnitsController;
use App\Http\Controllers\Admin\ProgramTypesController;
use App\Http\Controllers\Admin\ProgramsController;
use App\Http\Controllers\Admin\SubProgramsController;
use App\Http\Controllers\Admin\Level1CatalogCodeController;
use App\Http\Controllers\Admin\Level5CatalogCodeController;
use App\Http\Controllers\Order\OrdersController;
use App\Http\Controllers\Order\ItemsAdjudicaController;
use App\Http\Controllers\Order\BudgetRequestProvidersController;
use App\Http\Controllers\DeriveOrder\DeriveOrdersController;
use App\Http\Controllers\Order\SimeseOrdersController;
use App\Http\Controllers\Order\OrdersFilesController;


//DESDE ACA SE USA PARA EL SISTEMA DE CONTRATOS Y POLIZAS
use App\Http\Controllers\Contract\ContractsController;
use App\Http\Controllers\Contract\ContractsFilesController;
use App\Http\Controllers\Contract\ItemsController;
use App\Http\Controllers\Contract\ItemAwardHistoriesController;
use App\Http\Controllers\Contract\OrdersEjecsController;
use App\Http\Controllers\Dgaf\DgafsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Contract\ItemsOrdersController;


// use App\Http\Controllers\Report\PdfsController;
use App\Http\Controllers\Report\ReportsController;
// use PHPJasper\PHPJasper;
use App\Http\Controllers\EmailController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::post('/login', [HomeController::class, 'checkLogin'])->name('checkLogin');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
Route::get('/', [HomeController::class, 'index'])->name('home');
// obtener el token csrf
Route::get('/token', function (Request $request) {
    $token = csrf_token();
    return response()->json(['status' => 'success', 'token' => $token], 200);
});

Route::middleware('auth')->group(function () {  // Las siguientes funcionalidades son accedidas solamente estando el usuario autenticado
    /********** MODULO ADMINISTRADOR **********/

    //USUARIOS A EXCEL - //PARA GENERAR ARCHIVOS EXCEL PRIMERO SE COLOCA EL GET ANTES DEL RESOURCE
    Route::get('/users/exportarexcel', [UsersController::class, 'exportarExcel']);
    Route::get('/users/create2', [UsersController::class, 'create2'])->name('users.create2');
    Route::get('/users/change_pass', [UsersController::class, 'change_pass'])->name('users.change_pass');

    Route::resource('users', UsersController::class);   // index, create, update, delete


    //ORDER_PRESENTATIONS A EXCEL - //PARA GENERAR ARCHIVOS EXCEL PRIMERO SE COLOCA EL GET ANTES DEL RESOURCE
    Route::get('/order_presentations/exportarexcel', [OrderPresentationsController::class, 'exportarExcel']);
    Route::resource('order_presentations', OrderPresentationsController::class);


    //PARA CAMBIAR PASSWORD
    Route::put('users/{id}/update_pass', [UsersController::class, 'update_pass'])->name('users.update_pass');

    //PARA RESETEAR PASSWORD
    Route::get('users/{id}/reset_pass', [UsersController::class, 'reset_pass'])->name('users.reset_pass');

    //RUTA PARA ENVIAR EMAIL
    Route::get('/send-mail', [EmailController::class, 'sendMail']);

    Route::resource('positions', PositionsController::class);
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('modalities', ModalitiesController::class);
    Route::resource('dependency_types', DependencyTypesController::class);
    Route::resource('uoc_types', UocTypesController::class);

    //MOSTRAR EN EXCEL DEPENDENCIAS
    Route::get('/dependencies/exportarexcel', [DependenciesController::class, 'exportarExcel']);
    Route::resource('dependencies', DependenciesController::class);

    Route::resource('order_states', OrderStatesController::class);
    Route::resource('funding_sources', FundingSourcesController::class);
    Route::resource('financial_organisms', FinancialOrganismsController::class);
    Route::resource('financial_levels', FinancialLevelsController::class);
    Route::resource('providers', ProvidersController::class);
    Route::resource('regions', RegionsController::class);
    Route::resource('departments', DepartmentsController::class);
    Route::resource('districts', DistrictsController::class);
    Route::resource('expenditure_objects', ExpenditureObjectsController::class);
    Route::resource('files', FilesController::class);
    Route::resource('order_measurement_units', OrderMeasurementUnitsController::class);

    Route::resource('program_measurement_units', ProgramMeasurementUnitsController::class);
    Route::resource('program_types', ProgramTypesController::class);
    Route::resource('programs', ProgramsController::class);
    Route::resource('sub_programs', SubProgramsController::class);

    Route::resource('catalog_level1s', Level1CatalogCodeController::class);

    Route::resource('catalog_level5s', Level5CatalogCodeController::class);
    Route::get('catalog_level5s/{id}/edit_c5', [Level5CatalogCodeController::class, 'edit_c5'])->name('catalog_level5s.edit_c5');


    //RECURSOS PARA MANEJAR CONTRACTS (CONTRATOS)
    Route::resource('contracts', ContractsController::class);
    // RUTA PARA EDITAR FORM CONTRATO Y AGREGAR FISCAL
    Route::get('/contracts/orders/{order}/edit', [ContractsController::class, 'asign'])->name('contracts.asign');
    // RUTA PARA ACTUALIZAR CONTRATO CUANDO SE ASOCIA FISCALES
    Route::put('/contracts/orders/{order}/edit', [ContractsController::class, 'update_fiscal'])->name('contracts.update.fiscal');
    

    Route::get('/orders/create', [OrdersEjecsController::class, 'create']);
    Route::post('/orders', [OrdersEjecsController::class, 'store']);
    Route::get('/fetch-districts', [OrdersEjecsController::class, 'fetchDistricts']);



    //RECURSOS DE CONTRACTS PARA MANEJAR ITEMS (POLIZAS)
    Route::resource('contracts.items', ItemsController::class); //Recurso anidado, es igual a /contracts/{contract_id}/items/{item_id}

    //RECURSOS PARA MANEJAR ITEMS AWARDS HISTORIES (ENDOSOS)
    Route::resource('items.item_award_histories', ItemAwardHistoriesController::class); //Recurso anidado, es igual a /contracts/{contract_id}/items/{item_id}

    //RECURSOS DE CONTRACTS PARA MANEJAR ORDERS (ORDENES)
    Route::resource('contracts.orders', OrdersEjecsController::class); //Recurso anidado, es igual a /contracts/{contract_id}/items/{item_id}

    //PARA IMPORTAR EXCEL EN ITEMS DE ORDENES DE EJECUCIÓN
    Route::resource('orders.items_orders', ItemsOrdersController::class); //Recurso anidado, es igual a /contracts/{contract_id}/items/{item_id}
    
    Route::get('/orders/{id}/uploadExcelItem', [ItemsOrdersController::class, 'uploadExcel'])->name('orders.items.uploadExcel');
    Route::post('/orders/{id}/uploadExcel', [ItemsOrdersController::class, 'storeExcel'])->name('orders.items.storeExcel');
    
    // Route::resource('orders.items_orders', ItemsOrdersController::class); //Recurso anidado, es igual a /contracts/{contract_id}/items/{item_id}


    // Route::get('/contracts/{order_id}', [ContractsController::class, 'show'])->name('contracts.show');



    // Route::get('/orders/{id}/uploadExcelItem', [ItemsController::class, 'uploadExcel'])->name('orders.items.uploadExcel');
    // Contrato Abierto
    // Route::post('/orders/{id}/uploadExcel', [ItemsController::class, 'storeExcel'])->name('orders.items.storeExcel');
    // Contrato Cerrado
    // Route::post('/orders/{id}/uploadExcel2', [ItemsController::class, 'storeExcel2'])->name('orders.items.storeExcel2');
    // Contrato Abierto con Mmin y Mmax
    // Route::post('/orders/{id}/uploadExcel3', [ItemsController::class, 'storeExcel3'])->name('orders.items.storeExcel3');







    // SE AGREGA PARA EDITAR PROVEEDORES EN CONTRATOS
    Route::get('orders/{id}/budget_request_providers/{budget}edit_providers_contracts', [BudgetRequestProvidersController::class, 'edit_providers_contracts'])->name('orders.budget_request_providers.edit_providers_contracts');
    Route::put('orders/{id}/budget_request_providers/{budget}edit_providers_contracts', [BudgetRequestProvidersController::class, 'update_providers_contracts'])->name('orders.budget_request_providers.update_providers_contracts');


    /********** MODULO PEDIDOS **********/
    Route::get('/orders/uploadExcel', [OrdersController::class, 'uploadExcel'])->name('orders.uploadExcel');
    Route::post('/orders/uploadExcel', [OrdersController::class, 'storeExcel'])->name('orders.storeExcel');

    //PEDIDOS A EXCEL
    Route::get('/orders/exportarexcel', [OrdersController::class, 'exportarExcel']); // PONER DEBAJO DE LAS RUTAS /orders/uploadExcel para que funcione
    Route::get('/orders/exportarexcel2', [OrdersController::class, 'exportarExcel2']); // PONER DEBAJO DE LAS RUTAS /orders/uploadExcel para que funcione

    Route::resource('orders', OrdersEjecsController::class);    // PONER DEBAJO DE LAS RUTAS /orders/uploadExcel para que funcione

    Route::post('orders/derive/{order_id}', [OrdersController::class, 'derive'])->name('orders.derive');
    Route::post('orders/anuleOrder/{order_id}', [OrdersController::class, 'anuleOrder'])->name('orders.anuleOrder');

    Route::post('orders/anuleDerive/{order_id}', [OrdersController::class, 'anuleDerive'])->name('orders.anuleDerive');


    


    Route::get('/orders/{id}/uploadExcelAw', [ItemsAdjudicaController::class, 'uploadExcelAw'])->name('orders.items_adjudica.uploadExcelAw');
    // Contrato Abierto
    Route::post('/orders/{id}/uploadExcelAw', [ItemsAdjudicaController::class, 'storeExcelAw'])->name('orders.items_adjudica.storeExcelAw');

    //BUSCA ITEMS DE CATALOGO 5
    Route::get('items/search', [ItemsController::class, 'search']);
    //BUSCA ITEMS DE CATALOGO 4
    Route::get('items/search4', [ItemsController::class, 'search4']);


    Route::get('orders/simese/{order_id}/index', [SimeseOrdersController::class, 'index'])->name('orders.simese.index');
    Route::get('orders/simese/{order_id}/create', [SimeseOrdersController::class, 'create'])->name('orders.simese.create');
    Route::post('orders/simese/{order_id}/create', [SimeseOrdersController::class, 'store'])->name('orders.simese.store');
    Route::get('orders/simese/{order_id}/edit', [SimeseOrdersController::class, 'edit'])->name('orders.simese.edit');
    Route::put('orders/simese/{order_id}/edit', [SimeseOrdersController::class, 'update'])->name('orders.simese.update');
    Route::get('orders/files/{order_id}/index', [OrdersFilesController::class, 'index'])->name('orders.files.index');
    Route::get('orders/files/{order_id}/create', [OrdersFilesController::class, 'create'])->name('orders.files.create');
    Route::post('orders/files/{order_id}/create', [OrdersFilesController::class, 'store'])->name('orders.files.store');

    Route::get('orders/files/{order_id}/create_filedncp', [OrdersFilesController::class, 'create_filedncp'])->name('orders.files.create_filedncp');
    Route::post('orders/files/{order_id}/create_filedncp', [OrdersFilesController::class, 'store_filedncp'])->name('orders.files.store_filedncp');

    Route::get('orders/files/{order_id}/create_filedncp_con', [OrdersFilesController::class, 'create_filedncp_con'])->name('orders.files.create_filedncp_con');
    Route::post('orders/files/{order_id}/create_filedncp_con', [OrdersFilesController::class, 'store_filedncp_con'])->name('orders.files.store_filedncp_con');


    Route::get('orders/files/{order_id}/create_cuadro_compar', [OrdersFilesController::class, 'create_cuadro_compar'])->name('orders.files.create_cuadro_compar');
    Route::post('orders/files/{order_id}/create_cuadro_compar', [OrdersFilesController::class, 'store_cuadro_compar'])->name('orders.files.store_cuadro_compar');

    Route::delete('orders/files/{file_id}/delete', [OrdersFilesController::class, 'destroy'])->name('orders.files.delete');

    Route::get('orders/files/{file_id}/download', [OrdersFilesController::class, 'download'])->name('orders.files.download');

    Route::get('excel/pedidos', [OrdersController::class, 'ArchivoPedido'])->name('excel.pedidos');
    Route::get('excel/items', [OrdersController::class, 'ArchivoItem'])->name('excel.items');
    Route::get('excel/items2', [OrdersController::class, 'ArchivoItem2'])->name('excel.items2');
    Route::get('excel/items3', [OrdersController::class, 'ArchivoItem3'])->name('excel.items3');


    //excel/itemsAw


    /********** MODULO DERIVAR PEDIDOS **********/
    Route::get('derive_orders/{order_id}/create', [DeriveOrdersController::class, 'create'])->name('derive_orders.create');
    Route::post('derive_orders/{order_id}/create', [DeriveOrdersController::class, 'store'])->name('derive_orders.store');
    Route::get('derive_orders/{order_id}/edit', [DeriveOrdersController::class, 'edit'])->name('derive_orders.edit');
    Route::put('derive_orders/{order_id}/edit', [DeriveOrdersController::class, 'update'])->name('derive_orders.update');

    Route::post('derive_orders/derivePbc/{order_id}', [DeriveOrdersController::class, 'derivePbc'])->name('derive_orders.derivePbc');

    Route::post('derive_orders/deriveDictamen/{order_id}', [DeriveOrdersController::class, 'deriveDictamen'])->name('derive_orders.deriveDictamen');
    Route::post('derive_orders/deriveDictamenEVAL/{order_id}', [DeriveOrdersController::class, 'deriveDictamenEVAL'])->name('derive_orders.deriveDictamenEVAL');
    Route::post('derive_orders/deriveExcepciones/{order_id}', [DeriveOrdersController::class, 'deriveExcepciones'])->name('derive_orders.deriveExcepciones');

    Route::post('derive_orders/deriveInforme/{order_id}', [DeriveOrdersController::class, 'deriveInforme'])->name('derive_orders.deriveInforme');

    Route::post('derive_orders/deriveInformeUTA/{order_id}', [DeriveOrdersController::class, 'deriveInformeUTA'])->name('derive_orders.deriveInformeUTA');


    /*************** REPORTES DOMPDF **************/
    //REPORTE DE CONTRATOS TODOS
    Route::get('pdf/panel_contracts0', [ReportsController::class, 'generarContracts0'])->name('pdf.panel_contracts0');
    //REPORTE DE CONTRATOS EN CURSO
    Route::get('pdf/panel_contracts1', [ReportsController::class, 'generarContracts1'])->name('pdf.panel_contracts1');
    //REPORTE DE CONTRATOS RESCINDIDOS
    Route::get('pdf/panel_contracts2', [ReportsController::class, 'generarContracts2'])->name('pdf.panel_contracts2');
    //REPORTE DE CONTRATOS CERRADOS
    Route::get('pdf/panel_contracts3', [ReportsController::class, 'generarContracts3'])->name('pdf.panel_contracts3');
    //REPORTE DE CONTRATOS EN PROCESO RESCISIÓN
    Route::get('pdf/panel_contracts6', [ReportsController::class, 'generarContracts6'])->name('pdf.panel_contracts6');
    //REPORTE DE DETALLE DE PÓLIZAS
    Route::get('pdf/panel_contracts4', [ReportsController::class, 'generarContracts4'])->name('pdf.panel_contracts4');
    //REPORTE DE ALERTAS DE VENCIMIENTOS DE PÓLIZAS
    Route::get('pdf/panel_contracts5', [ReportsController::class, 'generarContracts5'])->name('pdf.panel_contracts5');

    //VISTA PARA ELEGIR DEPENDENCIA OPARA MOSTRAR ALERTAS DE VENCIMIENTOS DE PÓLIZAS
    Route::get('pdf/panel_contracts7/{dependency_id}', [ReportsController::class, 'generarContracts7'])->name('pdf.panel_contracts7');
    // Route::get('pdf/panel_contracts/{contract_id}', [ReportsController::class, 'generarContracts'])->name('pdf.panel_contracts');

    //VISTA PARA ALERTAS POR DEPENDENCIA
    Route::get('pdf/panel_contracts9', [ReportsController::class, 'generarContracts9'])->name('pdf.panel_contracts9');


    //REPORTE DE UN CONTRATO ESPECÍFICO
    Route::get('pdf/panel_contracts/{contract_id}', [ReportsController::class, 'generarContracts'])->name('pdf.panel_contracts');

    //REPORTE DE DETALLE DE PÓLIZAS EN EXCEL
    Route::get('/contracts/exportarexcel6', [ContractsController::class, 'exportarExcel6']);

    //REPORTE DE CONTRATISTAS
    Route::get('pdf/pdfContratistas', [ReportsController::class, 'pdfContratistas'])->name('pdf.contratistas');


    Route::get('pdf/users', [ReportsController::class, 'pdfUsers'])->name('pdf.users');
    Route::get('pdf/users2', [ReportsController::class, 'pdfUsers2'])->name('pdf.users2');

    Route::get('pdf/dependencies', [ReportsController::class, 'dependencies'])->name('pdf.dependencies');


    Route::get('pdf/modalities', [ReportsController::class, 'pdfArchivo'])->name('pdf.modalities');
    Route::get('pdf/modalities2', [ReportsController::class, 'generarModalities'])->name('pdf.modalities');
    Route::get('pdf/reporte1/{order_id}', [ReportsController::class, 'generarForm1'])->name('pdf.form1');
    Route::get('pdf/reporte2/{order_id}', [ReportsController::class, 'generarForm2'])->name('pdf.form2');
    Route::get('pdf/panel', [ReportsController::class, 'generarPanel'])->name('pdf.panel');
    Route::get('pdf/tipres', [ReportsController::class, 'pdfTipres'])->name('pdf.tipres');
    Route::get('pdf/tipresunid', [ReportsController::class, 'pdfTipresUnid'])->name('pdf.tipresunid');
    Route::get('pdf/panel_licit', [ReportsController::class, 'generarPanelLicit'])->name('pdf.panel_licit');
    Route::get('pdf/panel_minor', [ReportsController::class, 'generarPanelMinor'])->name('pdf.panel_minor');
    Route::get('pdf/panel_exception', [ReportsController::class, 'generarPanelException'])->name('pdf.panel_exception');

    Route::get('pdf/panel_uta', [ReportsController::class, 'generarPanelUta'])->name('pdf.panel_uta');
    Route::get('pdf/panel_uta2', [ReportsController::class, 'generarPanelUta2'])->name('pdf.panel_uta2');
    Route::get('pdf/panel_uta3', [ReportsController::class, 'generarPanelUta3'])->name('pdf.panel_uta3');

    Route::get('pdf/panel_pedidos/{dependency}', [ReportsController::class, 'generarPanelPedido'])->name('pdf.panel_pedido');
    Route::get('pdf/panel_pedidos2/{dependency}', [ReportsController::class, 'generarPanelPedido2'])->name('pdf.panel_pedido2');

    Route::get('excel', [OrdersController::class, 'excel']);
    Route::get('pdf/reporte3/{order_id}', [ReportsController::class, 'generarForm3'])->name('pdf.form3');

    Route::get('pdf/reporte31/{order_id}', [ReportsController::class, 'generarForm31'])->name('pdf.form31');

    Route::get('pdf/reporte4/{order_id}', [ReportsController::class, 'generarForm4'])->name('pdf.form4');

    Route::get('pdf/prefs/{order_id}', [ReportsController::class, 'pdfPrefs'])->name('pdf.prefs');

    //SECUENCIA LICITACIONES
    Route::get('pdf/secuencia', [ReportsController::class, 'pdfSecuencia'])->name('pdf.secuencia');
    //SECUENCIA COMPRAS MENORES
    Route::get('pdf/secuencia2', [ReportsController::class, 'pdfSecuencia2'])->name('pdf.secuencia2');
    //SECUENCIA CVE
    Route::get('pdf/secuencia3', [ReportsController::class, 'pdfSecuencia3'])->name('pdf.secuencia3');

    //INSTRUCTIVO CAMBIAR PASSWORD
    Route::get('pdf/change_pass', [ReportsController::class, 'pdfChange_pass'])->name('pdf.change_pass');

    //ITEMS SE PASA A EXCEL PEDIDOS
    // Route::get('pedidos/export-excel2/', [ItemsAdjudicaController::class, 'exportExcel2']);
    // Route::get('pdf/panel_uta', [ReportsController::class, 'generarPanelUta'])->name('pdf.panel_uta');



    //ITEMS SE PASA A EXCEL EN AWARDS
    Route::get('items/export-excel/{order_id}', [ItemsAdjudicaController::class, 'exportExcel']);

    //ITEMS SE PASA A EXCEL EN CONTRACTS
    Route::get('items/export-excel3/{order_id}', [ItemsAdjudicaController::class, 'exportExcel3']);


    /********** MODULO CONTRACTS - CONTRATOS Y GARANTIAS **********/
    Route::post('contracts/create}', [ContractsController::class, 'calculo'])->name('contracts.calculo');
    //carga archivos de pólizas
    Route::get('contracts/files/{contract_id}/create', [ContractsFilesController::class, 'create'])->name('contracts.files.create');
    //carga archivos de contratos
    Route::get('contracts/files/{contract_id}/create_con', [ContractsFilesController::class, 'create_con'])->name('contracts.files.create_con');
    //carga archivos de evaluaciones
    Route::get('contracts/files/{contract_id}/create_eval', [ContractsFilesController::class, 'create_eval'])->name('contracts.files.create_eval');

    //almacena archivos de pólizas
    Route::post('contracts/files/{contract_id}/store', [ContractsFilesController::class, 'store'])->name('contracts.files.store');
    //almacena archivos de contratos
    Route::post('contracts/files/{contract_id}/store_con', [ContractsFilesController::class, 'store_con'])->name('contracts.files.store_con');
    //almacena archivos de evaluaciones
    Route::post('contracts/files/{contract_id}/store_eval', [ContractsFilesController::class, 'store_eval'])->name('contracts.files.store_eval');


    Route::get('contracts/files/{file_id}/download', [ContractsFilesController::class, 'download'])->name('contracts.files.download');
    Route::delete('contracts/files/{file_id}/delete', [ContractsFilesController::class, 'destroy'])->name('contracts.files.delete');

    // Route::delete('contracts/{contract_id}/delete', [ContractsController::class, 'destroy'])->name('contracts.delete');
    // Route::delete('/contracts/contract/{contract_id}/delete', 'ContractController@delete')->name('contracts.delete');
    Route::delete('contracts/contract/{contract_id}/delete', [ContractsController::class, 'destroy'])->name('contracts.delete');


    Route::get('contracts/getNotifications', [ContractsController::class, 'getNotifications'])->name('contracts.getNotifications');
    Route::resource('contracts', ContractsController::class);

    // Route::post('contracts/recibe_order/{order_id}', [ContractsController::class, 'recibeOrder'])->name('contracts.recibeOrder');
    // Route::get('contracts/orders/{order_id}/edit', [ContractsController::class, 'edit'])->name('contracts.orders.edit');
    // Route::put('contracts/orders/{order_id}/edit', [ContractsController::class, 'update'])->name('contracts.orders.update');
    // Route::post('contracts/derive_order/{order_id}', [ContractsController::class, 'deriveOrder'])->name('contracts.deriveOrder');


    /********** MODULO DE DGAF **********/
    Route::resource('dgafs', DgafsController::class);

    Route::resource('dgafs.objections', App\Http\Controllers\Dgaf\ObjectionsController::class);
    Route::resource('dgafs.objections_responses', App\Http\Controllers\Dgaf\ObjectionsResponsesController::class);

    Route::post('dgafs/recibeOrder/{order_id}', [DgafsController::class, 'recibeOrder'])->name('dgafs.recibeOrder');
    Route::post('dgafs/recibeOrderDGAF/{order_id}', [DgafsController::class, 'recibeOrderDGAF'])->name('dgafs.recibeOrderDGAF');
    Route::post('dgafs/deriveOrderDGAF/{order_id}', [DgafsController::class, 'deriveOrderDGAF'])->name('dgafs.deriveOrderDGAF');

    Route::post('dgafs/deriveDictamen/{order_id}', [DgafsController::class, 'deriveDictamen'])->name('dgafs.deriveDictamen');

    Route::post('dgafs/recibeOrderEVAL/{order_id}', [DgafsController::class, 'recibeOrderEVAL'])->name('dgafs.recibeOrderEVAL');
    Route::post('dgafs/recibeOrderCVE/{order_id}', [DgafsController::class, 'recibeOrderCVE'])->name('dgafs.recibeOrderCVE');

    Route::post('dgafs/deriveDictamenEVAL/{order_id}', [DgafsController::class, 'deriveDictamenEVAL'])->name('dgafs.deriveDictamenEVAL');
    Route::post('dgafs/deriveDictamenCVE/{order_id}', [DgafsController::class, 'deriveDictamenCVE'])->name('dgafs.deriveDictamenCVE');


    // Route::get('contracts/orders/{order_id}/edit', [ContractsController::class, 'edit'])->name('contracts.orders.edit');

});
