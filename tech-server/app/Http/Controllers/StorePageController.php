<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class StorePageController extends Controller
{
    /**
     * Order confirmation page (loads the last order placed in this session).
     */
    public function success(): View
    {
        $orderId = Session::get('last_order_id');
        $order = null;

        if (is_int($orderId) || ctype_digit((string) $orderId)) {
            $order = Order::query()->with('items')->find($orderId);
        }

        return view('store.success', ['order' => $order]);
    }

    /**
     * Personal cabinet: past orders for the user, or the last guest order.
     */
    public function profile(): View
    {
        $user = auth()->user();
        $orders = collect();
        $lastGuestOrder = null;

        if ($user !== null) {
            $orders = Order::query()
                ->where('user_id', $user->id)
                ->with('items')
                ->latest()
                ->paginate(5);
        } else {
            $orderId = Session::get('last_order_id');
            if (is_int($orderId) || ctype_digit((string) $orderId)) {
                $lastGuestOrder = Order::query()->with('items')->find($orderId);
            }
        }

        return view('store.profile', [
            'user' => $user,
            'orders' => $orders,
            'lastGuestOrder' => $lastGuestOrder,
        ]);
    }
}
