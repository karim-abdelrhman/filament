<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $fillable = ['description' , 'notes' , 'patient_id' , 'price'];
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
