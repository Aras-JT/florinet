<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'price',
        'selling_price',
        'currency',
        'image_url',
        'article_code',
        'stock',
        'properties',
        'published_at',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}
