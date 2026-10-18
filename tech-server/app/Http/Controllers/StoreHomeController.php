<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class StoreHomeController extends Controller
{
    public function __invoke(): View
    {
        $homeSlugs = ['smartphones', 'laptops', 'tv', 'appliances', 'components', 'home', 'wearables', 'gaming'];

        return view('store.home', [
            'homeCategories' => Category::query()->whereIn('slug', $homeSlugs)->orderBy('sort')->get(),
            'hits' => Product::query()->where('is_active', true)->where('badge_kind', 'hit')->limit(4)->get(),
            'novelties' => Product::query()
                ->where('is_active', true)
                ->whereIn('badge_kind', ['new'])
                ->orderByDesc('id')
                ->limit(4)
                ->get(),
            'dailyDeal' => Product::query()->where('is_active', true)->where('slug', 'ecoflow-delta-2-max')->first()
                ?? Product::query()->where('is_active', true)->where('badge_kind', 'hit')->first(),
            'brandsRow' => ['Apple', 'Samsung', 'ASUS', 'Lenovo', 'Sony', 'Xiaomi', 'Bosch', 'EcoFlow', 'LG', 'Philips'],
        ]);
    }
}
