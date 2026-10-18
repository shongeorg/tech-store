<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductDetailResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Публічні JSON-ендпоінти каталогу для Astro-фронтенду.
 */
class CatalogController
{
    /**
     * GET /api/v1/health — не потребує контролера.
     */

    /**
     * GET /api/v1/categories — список активних категорій.
     *
     * @return AnonymousResourceCollection<CategoryResource>
     */
    public function categories(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::query()
                ->where('is_active', true)
                ->orderBy('sort')
                ->withCount(['products' => fn (Builder $query) => $query->where('is_active', true)])
                ->get()
        );
    }

    /**
     * GET /api/v1/products — каталог із фільтрами, сортуванням та пагінацією.
     *
     * Параметри: q, category, brands[], stock, featured, sort, per_page, page, price_min, price_max, memory[].
     *
     * @return AnonymousResourceCollection<ProductCardResource>
     */
    public function products(Request $request): AnonymousResourceCollection
    {
        $query = Product::query()->where('is_active', true)->with('category');

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function (Builder $where) use ($search) {
                $like = str_replace('%', '\%', mb_strtolower($search));

                $where->whereRaw('lower(title) like ?', ["%{$like}%"])
                    ->orWhereRaw('lower(brand) like ?', ["%{$like}%"]);
            });
        }

        $categorySlug = (string) $request->query('category', '');
        if ($categorySlug !== '') {
            $query->whereHas('category', function (Builder $where) use ($categorySlug) {
                $where->where('slug', $categorySlug)->where('is_active', true);
            });
        }

        $brands = array_values(array_filter((array) $request->query('brands', []), fn ($b) => is_string($b) && $b !== ''));
        if ($brands !== []) {
            $query->whereIn('brand', $brands);
        }

        $stock = (string) $request->query('stock', '');
        if (in_array($stock, ['in', 'order'], true)) {
            $query->where('stock', $stock);
        }

        $priceMin = $request->query('price_min');
        if (is_numeric($priceMin)) {
            $query->where('price', '>=', (int) $priceMin);
        }

        $priceMax = $request->query('price_max');
        if (is_numeric($priceMax)) {
            $query->where('price', '<=', (int) $priceMax);
        }

        $memories = array_values(array_filter((array) $request->query('memory', []), fn ($m) => is_string($m) && $m !== ''));
        if ($memories !== []) {
            $query->where(function (Builder $where) use ($memories) {
                foreach ($memories as $memory) {
                    $where->orWhereJsonContains('memory', $memory);
                }
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        match ((string) $request->query('sort', 'pop')) {
            'rating' => $query->orderByDesc('rating')->orderByDesc('reviews_count'),
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'new' => $query->orderByDesc('badge_kind')->orderByDesc('id'),
            'discount' => $query->orderByRaw('coalesce(old_price, price) - price desc')->orderByDesc('id'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating')->orderBy('id'),
        };

        $perPage = min(24, max(1, (int) $request->query('per_page', 12)));

        return ProductCardResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/v1/products/{slug} — повна картка товару.
     */
    public function show(string $slug): ProductDetailResource
    {
        $product = Product::query()
            ->where('is_active', true)
            ->where('slug', $slug)
            ->with('category')
            ->first();

        abort_if($product === null, 404);

        return new ProductDetailResource($product);
    }

    /**
     * GET /api/v1/stats — агрегати для бейджів на астро-сторінці.
     */
    public function stats(): JsonResponse
    {
        $price = Product::query()->where('is_active', true)
            ->selectRaw('min(price) as min_price, max(price) as max_price, count(*) as total')
            ->first();

        return response()->json([
            'products' => (int) ($price->total ?? 0),
            'in_stock' => Product::query()->where('is_active', true)->where('stock', 'in')->count(),
            'categories' => Category::query()->where('is_active', true)->count(),
            'featured' => Product::query()->where('is_featured', true)->count(),
            'min_price' => (int) ($price->min_price ?? 0),
            'max_price' => (int) ($price->max_price ?? 0),
        ]);
    }
}
