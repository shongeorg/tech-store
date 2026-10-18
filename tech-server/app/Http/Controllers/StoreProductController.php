<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class StoreProductController extends Controller
{
    public function __invoke(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $category = Category::query()->find($product->category_id) ?? $product->category;

        $related = Product::query()
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $fullSpecs = $product->full_specs !== null && $product->full_specs !== []
            ? $product->full_specs
            : [
                ['group' => 'Основні характеристики', 'rows' => [
                    ...($product->specs ?? []),
                    ['Бренд', $product->brand],
                    ['Гарантія', '12–24 місяці, офіційна'],
                ]],
            ];

        return view('store.product', [
            'p' => $product,
            'category' => $category,
            'related' => $related,
            'fullSpecs' => $fullSpecs,
        ]);
    }
}
