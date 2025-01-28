<?php

namespace App\Http\Controllers\Contract;
use App\Http\Controllers\Controller;

use App\Models\Department; // Nombre del modelo cambiado
use App\Models\District; // Nombre del modelo cambiado
use Illuminate\Http\Request;

class TableController extends Controller // Nombre del controlador cambiado
{
    public function index()
{
    $data = [
        [
            'id' => 1,
            'title' => 'Archivo 1',
            'files' => [
                ['name' => 'Documento A', 'url' => '/files/00-REGION OCCIDENTAL/1. Cartel de Obras.xlsx'],
                ['name' => 'Documento B', 'url' => '/files//00-REGION OCCIDENTAL/1. Cartel de Obras.xlsx']
            ]
        ],
        [
            'id' => 2,
            'title' => 'Archivo 2',
            'files' => [
                ['name' => 'Documento C', 'url' => '/files/00-REGION OCCIDENTAL/1. Cartel de Obras.xlsx'],
                ['name' => 'Documento D', 'url' => '/files/00-REGION OCCIDENTAL/1. Cartel de Obras.xlsx']
            ]
        ]
    ];

    return response()->json($data);
}
}
