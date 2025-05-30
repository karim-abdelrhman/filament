<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function authors() : belongsToMany
    {
        return $this->belongsToMany(User::class , 'post_user' )->withPivot(['order'])->withTimestamps();
    }

    public function comments() : MorphMany
    {
        return $this->morphMany(Comment::class , 'commentable');
    }
}
