<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreCheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('toast', 'Кошик порожній');
        }

        return view('store.checkout', [
            'items' => $this->cart->items(),
            'totals' => $this->cart->totals(),
            'cities' => ['Київ', 'Львів', 'Дніпро', 'Одеса', 'Харків', 'Вінниця', 'Запоріжжя', 'Полтава', 'Чернівці', 'Івано-Франківськ'],
        ]);
    }

    public function store(Request $request)
    {
        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return redirect()->route('cart');
        }

        $totals = $this->cart->totals();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'city' => ['required', 'string', 'max:120'],
            'branch' => ['required', 'string', 'max:250'],
            'delivery' => ['required', 'in:novaposhta,pickup,courier,ukrposhta'],
            'payment' => ['required', 'in:card,installments,cod,invoice'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::query()->create([
            'user_id' => $request->user()?->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['branch'],
            'delivery' => $data['delivery'],
            'payment' => $data['payment'],
            'comment' => $data['comment'] ?? null,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['promoDiscount'],
            'total' => $totals['total'],
            'promo_code' => $totals['promoCode']?->code,
            'status' => 'new',
        ]);

        foreach ($items as $item) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'title' => $item['product']->title,
                'slug' => $item['product']->slug,
                'price' => $item['product']->price,
                'qty' => $item['qty'],
                'total' => $item['product']->price * $item['qty'],
            ]);
        }

        $this->cart->clear();

        session(['last_order_id' => $order->id]);

        return redirect()->route('store.success')->with('toast', 'Замовлення успішно оформлено!');
    }
}
