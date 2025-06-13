<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\District;
use App\Models\Locality;
use Illuminate\Http\Request;

class LocalityController extends Controller
{
    public function index()
    {
        // $districts = District::all();
        // return view('admin.districts.index', compact('districts'));
        
        $localities = Locality::all();
        return view('admin.localities.index', compact('localities'));
    }

    public function create()
    {
        $localities = Locality::where('id', '!=', 9999)
                         ->orderBy('id')
                         ->get();
        return view('admin.localities.create', compact('localities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        Locality::create($request->all());

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

