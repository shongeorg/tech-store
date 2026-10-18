<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'short',
        'brand',
        'price',
        'old_price',
        'rating',
        'reviews_count',
        'badge',
        'badge_kind',
        'installment',
        'sku',
        'stock',
        'stock_text',
        'description',
        'specs',
        'memory',
        'colors',
        'full_specs',
        'images',
        'emoji',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'integer',
        'old_price' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
        'specs' => 'array',
        'memory' => 'array',
        'colors' => 'array',
        'full_specs' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
