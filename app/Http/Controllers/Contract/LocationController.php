<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts($departmentId)
    {
        $districts = District::where('department_id', $departmentId)->get();

        return response()->json($districts);
    }
}
