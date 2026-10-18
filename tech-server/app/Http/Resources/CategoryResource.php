<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'nav_name' => $this->nav_name,
            'emoji' => $this->emoji,
            'icon' => $this->icon,
            'products_count' => (int) ($this->products_count ?? $this->products()->where('is_active', true)->count()),
        ];
    }
}
