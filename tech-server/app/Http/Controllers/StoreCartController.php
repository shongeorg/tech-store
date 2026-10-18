<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreCartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View
    {
        $items = $this->cart->items();
        $totals = $this->cart->totals();

        $upsellsLike = ['apple-airpods-pro-2', 'samsung-990-pro-2tb', 'ecoflow-river-3'];

        return view('store.cart', [
            'items' => $items,
            'totals' => $totals,
            'upsells' => Product::query()->where('is_active', true)->whereIn('slug', $upsellsLike)->get(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product' => ['required', 'string', 'exists:products,slug'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()->where('slug', $data['product'])->first();
        if ($product === null || ! $product->is_active) {
            return back()->with('toast', 'Товар недоступний');
        }

        $this->cart->add($product->id, (int) ($data['qty'] ?? 1));

        if ($request->boolean('redirect_cart') || $request->get('buy') === 'now') {
            return redirect()->route('cart')->with('toast', 'Товар додано в кошик');
        }

        return back()->with('toast', "«{$product->title}» додано в кошик");
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->cart->setQty($data['product_id'], $data['qty']);

        return back()->with('toast', 'Кошик оновлено');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $this->cart->remove($data['product_id']);

        return back()->with('toast', 'Товар видалено з кошика');
    }

    public function promo(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40'],
        ]);

        if ($this->cart->applyPromo($data['code'])) {
            $promo = $this->cart->appliedPromo();

            return redirect()->route('cart')->with(
                'toast',
                sprintf('Промокод застосовано: −%s', format_price($promo->amount ?? 0))
            );
        }

        return redirect()->route('cart')->with('toast', 'Такого промокоду не існує');
    }

    public function promoClear()
    {
        $this->cart->clearPromo();

        return redirect()->route('cart')->with('toast', 'Промокод скасовано');
    }
}
