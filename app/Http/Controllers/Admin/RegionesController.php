<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Region;

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

    public function edit(Region $regione)
    {
        return view('regiones.edit', compact('regione'));
    }

    public function update(Request $request, Region $regione)
    {
        $request->validate([
            'description' => 'required|max:255|unique:regiones,description,' . $regione->id,
        ]);

        $regione->update($request->all());

        return redirect()->route('regiones.index')
                         ->with('success', 'Región actualizada correctamente.');
    }

    public function destroy(Region $regione)
    {
        $regione->delete();

        return redirect()->route('regiones.index')
                         ->with('success', 'Región eliminada correctamente.');
    }
}
