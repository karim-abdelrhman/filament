<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'color',
        'category_id',
        'content',
        'tags',
        'published',
        'thumbnail',
        'slug'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
