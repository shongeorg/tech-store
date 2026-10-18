<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductDetailResource extends JsonResource
{
    /**
     * Повна картка товару.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge((new ProductCardResource($this))->toArray($request), [
            'short' => $this->short,
            'description' => $this->description,
            'full_specs' => collect($this->full_specs ?? [])->map(fn ($row) => spec_pair($row))->values()->all(),
            'memory' => string_list($this->memory ?? []),
            'colors' => collect($this->colors ?? [])->values()->all(),
            'images' => image_list($this->images ?? []),
        ]);
    }
}
