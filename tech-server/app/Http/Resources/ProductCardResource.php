<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductCardResource extends JsonResource
{
    /**
     * Картка товару для сітки каталогу (Astro product card).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount = $this->old_price ? (int) round((1 - $this->price / $this->old_price) * 100) : 0;

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'brand' => $this->brand,
            'price' => (int) $this->price,
            'old_price' => $this->old_price !== null ? (int) $this->old_price : null,
            'discount' => $discount,
            'rating' => (float) $this->rating,
            'reviews_count' => (int) $this->reviews_count,
            'badge' => $this->badge,
            'badge_kind' => $this->badge_kind,
            'installment' => $this->installment,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'stock_text' => $this->stock_text,
            'emoji' => $this->emoji,
            'image' => image_list($this->images ?? [])[0] ?? null,
            'specs' => collect($this->specs ?? [])->map(fn ($row) => spec_pair($row))->values()->all(),
            'category' => [
                'slug' => $this->category?->slug,
                'name' => $this->category?->name,
            ],
        ];
    }
}
