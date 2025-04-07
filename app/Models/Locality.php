<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'district_id'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
