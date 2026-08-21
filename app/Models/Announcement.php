<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];
}