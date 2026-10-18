<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    public const SESSION_KEY = 'cart';

    public const PROMO_KEY = 'promo_code';

    /**
     * Items in the cart: [['product' => Product, 'qty' => int], ...]
     *
     * @return Collection<int, array{product: Product, qty: int}>
     */
    public function items(): Collection
    {
        $raw = Session::get(self::SESSION_KEY, []);
        if (! is_array($raw) || $raw === []) {
            return collect();
        }

        $qtyById = collect($raw)->map(fn ($qty) => max(1, (int) $qty));
        $products = Product::query()
            ->where('is_active', true)
            ->whereIn('id', $qtyById->keys())
            ->get()
            ->keyBy('id');

        return $qtyById
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(fn ($qty, $id) => ['product' => $products[$id], 'qty' => $qty])
            ->values();
    }

    /**
     * Number of items (sum of quantities).
     */
    public function count(): int
    {
        return (int) collect(Session::get(self::SESSION_KEY, []))->sum();
    }

    /**
     * @return array{T: string, subtotal: int, discount: int, total: int, promoCode: ?PromoCode, promoDiscount: int, count: int}
     */
    public function totals(): array
    {
        $items = $this->items();
        $subtotal = $items->sum(fn (array $item) => $item['product']->price * $item['qty']);

        $promoCode = $this->appliedPromo();
        $promoDiscount = 0;
        if ($promoCode !== null && $subtotal > 0) {
            $promoDiscount = min($promoCode->amount, $subtotal);
        }

        return [
            'count' => $items->sum(fn (array $item) => $item['qty']),
            'subtotal' => $subtotal,
            'promoDiscount' => $promoDiscount,
            'discount' => $promoDiscount,
            'total' => max(0, $subtotal - $promoDiscount),
            'promoCode' => $promoCode,
        ];
    }

    public function appliedPromo(): ?PromoCode
    {
        $code = Session::get(self::PROMO_KEY);

        if (! is_string($code) || $code === '') {
            return null;
        }

        return PromoCode::query()->where('code', mb_strtoupper($code))->where('is_active', true)->first();
    }

    public function add(int $productId, int $qty = 1): void
    {
        $cart = Session::get(self::SESSION_KEY, []);
        $cart[$productId] = max(1, (int) ($cart[$productId] ?? 0) + $qty);

        Session::put(self::SESSION_KEY, $cart);
    }

    public function setQty(int $productId, int $qty): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if ($qty < 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min(99, $qty);
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$productId]);

        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::PROMO_KEY);
    }

    /**
     * Apply a promo code by name. Returns whether the code was valid.
     */
    public function applyPromo(string $code): bool
    {
        $normalized = mb_strtoupper(trim($code));
        $promo = PromoCode::query()->where('code', $normalized)->where('is_active', true)->first();

        Session::put(self::PROMO_KEY, $promo instanceof PromoCode ? $promo->code : $normalized);
        Session::save();

        return $promo instanceof PromoCode;
    }

    public function clearPromo(): void
    {
        Session::forget(self::PROMO_KEY);
    }
}
