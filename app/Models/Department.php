<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['description']; // Nombre en inglés

    public function districts(): HasMany
    {
        return $this->hasMany('App\Models\District');
    }
}
