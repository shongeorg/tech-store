<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');

        $orders = Order::query()
            ->with('items')
            ->when(in_array($status, Order::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
            'labels' => Order::STATUS_LABELS,
            'currentStatus' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items', 'user'),
            'labels' => Order::STATUS_LABELS,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,confirmed,shipped,done,canceled'],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('toast', 'Статус замовлення оновлено');
    }
}
