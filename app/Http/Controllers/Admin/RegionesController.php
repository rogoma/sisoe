<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Region;
use Illuminate\Validation\Rule;

class RegionesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.regions.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.regions.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.regions.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regiones = Region::all();
        return view('admin.regiones.index', compact('regiones'));
    }

    public function create()
    {
        return view('admin.regiones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|unique:regiones|max:255',
        ]);

        Region::create($request->all());

        return redirect()->route('regiones.index')
                         ->with('success', 'Región creada correctamente.');
    }

    public function edit($id)
    {        
        $regiones = Region::find($id);        
        return view('admin.regiones.update', compact('regiones'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'description' => [
                'string',
                'required',
                'max:25',
                Rule::unique('regiones')->ignore($id),
            ],            
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos la región
        $regiones = Region::find($id);        
        $regiones->description = $request->input('description');
        $regiones->modifier_user_id = $request->user()->id;  // usuario logueado
        $regiones->save();               

        return redirect()->route('regiones.index')->with('success', 'Región modificado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        // Chequeamos que el usuario actual disponga de permisos de eliminacion
        // if(!$request->user()->hasPermission(['admin.regiones.delete'])){
        //     return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        // }

        $regiones = Region::find($id);        
        // Eliminamos en caso de no existir usuarios vinculados a dptos
        
        $regiones->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la Región ' . $regiones->description, 'code' => 200], 200);
    }
}
