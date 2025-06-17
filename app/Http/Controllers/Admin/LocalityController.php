<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\District;
use App\Models\Locality;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocalityExport;


class LocalityController extends Controller

{

    //Para exportar Localidades a Excel
    public function exportarlocalities()
    {
        return Excel::download(new LocalityExport, 'Localidades.xlsx');

    }


    public function index()
    {
        // $districts = District::all();
        // return view('admin.districts.index', compact('districts'));

        $localities = Locality::orderBy('district_id')->get();
        return view('admin.localities.index', compact('localities'));
    }

    public function create()
    {
        $districts = District::where('id', '!=', 9999)
                         ->orderBy('id')
                         ->get();

        return view('admin.localities.create', compact('districts'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'description' => 'required|string|max:255',
        //     'district_id' => 'required|exists:districts,id',
        // ]);

        // Locality::create($request->all());

        // return redirect()->route('admin.localities.index')->with('success', 'Localidad creada exitosamente.');


        $rules = array(
            'description' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $locality = new Locality;        
        $locality->description = $request->input('description');
        $locality->district_id = $request->input('district_id');        
        $locality->creator_user_id = $request->user()->id;  // usuario logueado
        $locality->save();

        return redirect()->route('admin.localities.index')->with('success', 'Localidad creada exitosamente.');

    }

    public function edit(Locality $locality)
    {
        $districts = District::all();
        return view('admin.localities.edit', compact('locality', 'districts'));
    }

    public function update(Request $request, Locality $locality)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        $locality->update($request->all());

        return redirect()->route('admin.localities.index')->with('success', 'Localidad actualizada exitosamente.');
    }

    public function destroy(Locality $locality)
    {
        $locality->delete();
        return redirect()->route('admin.localities.index')->with('success', 'Localidad eliminada exitosamente.');
    }





}

