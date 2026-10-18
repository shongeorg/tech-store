<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'activeProducts' => Product::where('is_active', true)->count(),
                'categories' => Category::count(),
                'orders' => Order::count(),
                'ordersToday' => Order::whereDate('created_at', today())->count(),
                'newOrders' => Order::where('status', 'new')->count(),
                'revenue' => (int) (clone new Order)->newQuery()->where('status', '!=', 'canceled')->sum('total'),
            ],
            'recentOrders' => Order::query()->latest()->limit(8)->with('items')->get(),
            'byCategory' => Product::query()
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->groupBy('categories.name')
                ->orderByDesc('count')
                ->select('categories.name', DB::raw('count(*) as count'))
                ->limit(9)
                ->get(),
        ]);
    }
}
