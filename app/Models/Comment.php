<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class   Comment extends Model
{

    protected $guarded = [];
    public function commentable() : \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
