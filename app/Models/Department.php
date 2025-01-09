<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', // Relación con districts
        'name',
        'description',
    ];
    /**
     * Para obtener el vinculo con la tabla districts
     */
    public function district(){
        return $this->belongsTo('App\Models\District');
    }
    // public function districts()
    // {
    //     return $this->hasMany('App\Models\District');
    // }

     /**
     * Relación: Un departamento tiene muchas órdenes.
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}
