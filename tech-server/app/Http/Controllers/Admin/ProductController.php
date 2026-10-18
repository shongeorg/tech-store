<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with('category')
            ->when($q !== '', fn ($query) => $query->where(fn ($sub) => $sub
                ->where('title', 'ilike', "%{$q}%")
                ->orWhere('sku', 'ilike', "%{$q}%")
                ->orWhere('brand', 'ilike', "%{$q}%")))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', ['products' => $products, 'q' => $q]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('sort')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $product = new Product;
        $this->fillAndSave($product, $request);

        return redirect()->route('admin.products.index')->with('toast', 'Товар створено');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('sort')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->fillAndSave($product, $request);

        return redirect()->route('admin.products.index')->with('toast', 'Товар оновлено');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('toast', 'Товар видалено');
    }

    /**
     * Live Unsplash image search for the admin form.
     */
    public function imageSearch(Request $request)
    {
        $data = $request->validate(['q' => ['required', 'string', 'max:120']]);

        $key = (string) config('services.unsplash.key');
        if ($key === '') {
            return response()->json(['results' => []], 503);
        }

        $response = Http::timeout(10)->get('https://api.unsplash.com/search/photos', [
            'query' => $data['q'],
            'per_page' => 6,
            'orientation' => 'landscape',
            'client_id' => $key,
        ]);

        if ($response->failed()) {
            return response()->json(['results' => []], 502);
        }

        $results = collect($response->json('results', []))->map(fn ($item) => [
            'thumb' => $item['urls']['thumb'] ?? null,
            'regular' => $item['urls']['regular'] ?? null,
        ])->all();

        return response()->json(['results' => $results]);
    }

    private function fillAndSave(Product $product, Request $request): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', "unique:products,slug,{$product->id}"],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand' => ['required', 'string', 'max:100'],
            'short' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1'],
            'old_price' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'badge_kind' => ['nullable', 'in:hit,new,action,muted,null'],
            'installment' => ['nullable', 'string', 'max:50'],
            'sku' => ['nullable', 'string', 'max:100'],
            'stock' => ['required', 'in:in,order'],
            'stock_text' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'emoji' => ['nullable', 'string', 'max:8'],
            'images' => ['nullable', 'string'],
            'specs' => ['nullable', 'string'],
            'memory' => ['nullable', 'string'],
            'colors' => ['nullable', 'string'],
            'full_specs' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ]);

        $badgeKind = $data['badge_kind'] ?? null;
        unset($data['badge_kind']);
        $product->badge_kind = in_array($badgeKind, ['hit', 'new', 'action', 'muted'], true) ? $badgeKind : null;

        $product->fill(collect($data)->except(['images', 'specs', 'memory', 'colors', 'full_specs'])->all());

        $product->rating = (float) ($data['rating'] ?? 0);

        foreach (['specs' => 'specs', 'memory' => 'memory', 'colors' => 'colors', 'full_specs' => 'full_specs'] as $field) {
            $json = trim((string) ($data[$field] ?? ''));
            if ($json !== '') {
                $decoded = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $product->{$field} = $decoded;
                }
            } else {
                $product->{$field} = null;
            }
        }

        $images = array_values(array_filter(explode("\n", str_replace("\r", '', (string) ($data['images'] ?? ''))), fn ($v) => trim($v) !== ''));
        $product->images = $images === [] ? null : $images;

        $product->is_active = $request->boolean('is_active', true);
        $product->is_featured = $request->boolean('is_featured', false);

        $product->save();
    }
}
