<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->orderBy('sort')
            ->withCount('products')
            ->get();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:categories,slug'],
            'emoji' => ['nullable', 'string', 'max:8'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);

        Category::create($data + [
            'nav_name' => $data['name'],
            'is_active' => true,
            'sort' => $data['sort'] ?? 99,
        ]);

        return back()->with('toast', 'Категорію створено');
    }

    public function destroy(Category $category)
    {
        $hasProducts = Product::query()->where('category_id', $category->id)->exists();

        if ($hasProducts) {
            return back()->with('toast', 'Спочатку видаліть або перенесіть товари цієї категорії');
        }

        $category->delete();

        return back()->with('toast', 'Категорію видалено');
    }
}
