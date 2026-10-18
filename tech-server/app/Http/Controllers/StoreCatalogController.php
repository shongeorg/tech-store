<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreCatalogController extends Controller
{
    private const PER_PAGE = 12;

    private const SORTS = [
        'pop' => 'За популярністю',
        'rating' => 'За рейтингом',
        'price-asc' => 'Від дешевих до дорогих',
        'price-desc' => 'Від дорогих до дешевих',
        'new' => 'Спочатку новинки',
        'discount' => 'За розміром знижки',
    ];

    /**
     * Full catalog.
     */
    public function index(Request $request): View
    {
        $query = Product::query()->where('is_active', true)->with('category');

        return $this->renderListing($request, $query, null, [
            'title' => 'Каталог товарів',
            'description' => 'Повний асортимент ТЕХНОПРОСТІР: електроніка, комплектуючі та побутова техніка з офіційною гарантією, доставкою Новою Поштою і розтермінуванням 0%.',
            'crumbs' => [
                ['label' => 'Головна', 'href' => route('home')],
                ['label' => 'Каталог товарів'],
            ],
            'showTradeIn' => true,
        ]);
    }

    /**
     * Category listing.
     */
    public function category(Request $request, Category $category): View
    {
        abort_unless($category->is_active, 404);

        $query = $category->products()->where('is_active', true)->with('category');

        return $this->renderListing($request, $query, $category, [
            'title' => $category->name,
            'description' => "Товари категорії «{$category->name}» з офіційною гарантією та доставкою по Україні.",
            'crumbs' => [
                ['label' => 'Головна', 'href' => route('home')],
                ['label' => 'Каталог', 'href' => route('catalog')],
                ['label' => $category->name],
            ],
            'showTradeIn' => true,
            'categoryIcon' => $category->icon,
            'categoryEmoji' => $category->emoji,
        ]);
    }

    /**
     * Shared filter, sort and pagination pipeline for catalog pages.
     *
     * @param  array<string, mixed>  $meta
     */
    protected function renderListing(Request $request, Builder $query, ?Category $category, array $meta): View
    {
        if ($category instanceof Category) {
            $brandCounts = $category->products()->where('is_active', true)->pluck('brand')->countBy();
        } else {
            $brandCounts = Product::query()->where('is_active', true)->pluck('brand')->countBy();
        }

        $brands = (array) $request->query('brands', []);
        $brands = array_values(array_filter($brands, fn ($b) => is_string($b) && $b !== ''));

        if ($brands !== []) {
            $query->whereIn('brand', $brands);
        }

        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');
        if (is_numeric($priceMin) && $priceMin > 0) {
            $query->where('price', '>=', (int) $priceMin);
        }
        if (is_numeric($priceMax) && $priceMax > 0) {
            $query->where('price', '<=', (int) $priceMax);
        }

        $stock = $request->query('stock');
        if ($stock === 'in') {
            $query->where('stock', 'in');
        } elseif ($stock === 'order') {
            $query->where('stock', 'order');
        }

        $this->applySort($query, (string) $request->query('sort', 'pop'));

        $products = $query->paginate(self::PER_PAGE)->withQueryString();

        return view('store.catalog', array_merge($meta, [
            'products' => $products,
            'category' => $category,
            'brandOptions' => $brandCounts->sortDesc()->all(),
            'selectedBrands' => $brands,
            'sorts' => self::SORTS,
            'currentSort' => (string) $request->query('sort', 'pop'),
            'priceMin' => (int) ($priceMin ?? 0),
            'priceMax' => (int) ($priceMax ?? 0),
            'currentStock' => is_string($stock) ? $stock : '',
            'totalForFilter' => $brandCounts->sum(),
        ]));
    }

    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'rating' => $query->orderByDesc('rating')->orderByDesc('reviews_count'),
            'price-asc' => $query->orderBy('price'),
            'price-desc' => $query->orderByDesc('price'),
            'new' => $query->orderByDesc('badge_kind')->orderByDesc('id'),
            'discount' => $query->orderByRaw('coalesce(old_price, price) - price desc')->orderByDesc('id'),
            default => $query->orderBy('is_featured', 'desc')->orderByDesc('rating')->orderBy('id'),
        };
    }
}
