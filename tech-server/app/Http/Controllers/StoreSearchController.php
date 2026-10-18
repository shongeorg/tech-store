<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreSearchController extends StoreCatalogController
{
    /**
     * Catalog-like search results page (server-side, paginated).
     */
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $cat = trim((string) $request->query('cat', ''));

        $query = Product::query()->where('is_active', true)->with('category');

        if ($q !== '') {
            $query->where(function (Builder $sub) use ($q): void {
                $sub->where('title', 'ilike', "%{$q}%")
                    ->orWhere('brand', 'ilike', "%{$q}%")
                    ->orWhere('sku', 'ilike', "%{$q}%")
                    ->orWhere('description', 'ilike', "%{$q}%");
            });
        }

        $category = Category::query()->where('slug', $cat)->first();
        if ($category instanceof Category) {
            $query->where('category_id', $category->id);
        }

        $crumbs = [
            ['label' => 'Головна', 'href' => route('home')],
        ];
        if ($category instanceof Category) {
            $crumbs[] = ['label' => $category->name, 'href' => route('catalog.category', $category->slug)];
        }
        $crumbs[] = ['label' => 'Пошук'];

        return $this->renderListing($request, $query, $category, [
            'title' => 'Результати пошуку',
            'headerTitle' => $q !== '' ? "Пошук: «{$q}»" : 'Пошук товарів',
            'description' => 'Знайдено товари за вашим запитом у каталозі ТЕХНОПРОСТІР.',
            'crumbs' => $crumbs,
            'showTradeIn' => false,
            'searchQuery' => $q,
            'selectedCategorySlug' => $cat,
        ]);
    }
}
