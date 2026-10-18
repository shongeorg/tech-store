@extends('layouts.admin')

@section('title')Замовлення №{{ $order->id }} — Адмінка@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center gap-3">
    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-1 text-body-sm font-semibold text-blue-600 hover:underline">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> До списку замовлень
    </a>
    <span class="badge {{ $order->status === 'new' ? 'bg-orange-50 text-orange-600' : ($order->status === 'done' ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'canceled' ? 'badge-action' : 'badge-muted')) }}">
        {{ $labels[$order->status] }}
    </span>
</div>

<div class="grid items-start gap-4 lg:grid-cols-[1fr_340px]">
    <div class="flex flex-col gap-4">
        {{-- Склад замовлення --}}
        <div class="card overflow-x-auto">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="text-body-md font-bold">Склад замовлення № ТП-{{ $order->id }}</h2>
            </div>
            <table class="w-full text-body-sm">
                <thead class="bg-slate-50 text-caption text-slate-500">
                    <tr>
                        <th class="px-4 py-2 text-left">Товар</th>
                        <th class="px-4 py-2 text-right">Ціна</th>
                        <th class="px-4 py-2 text-center">К-сть</th>
                        <th class="px-4 py-2 text-right">Разом</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-2">
                                @if ($item->product_id && $item->slug)
                                    <a href="{{ route('product', $item->slug) }}" class="font-semibold hover:text-blue-600">{{ $item->title }}</a>
                                @else
                                    <span class="font-semibold">{{ $item->title }}</span>
                                @endif
                            </td>
                            <td class="tabular px-4 py-2 text-right">{{ format_price($item->price) }}</td>
                            <td class="tabular px-4 py-2 text-center">{{ $item->qty }}</td>
                            <td class="tabular px-4 py-2 text-right font-bold">{{ format_price($item->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-slate-200 bg-slate-50">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right text-on-surface-variant">Вартість товарів</td>
                        <td class="tabular px-4 py-2 text-right font-bold">{{ format_price($order->subtotal) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right text-on-surface-variant">
                            Знижка ({{ $order->promo_code ?? 'промокод' }})
                        </td>
                        <td class="tabular px-4 py-2 text-right font-bold text-emerald-600">-{{ format_price($order->discount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right text-body-md font-bold">Разом до сплати</td>
                        <td class="tabular px-4 py-2 text-right text-price-display-md text-blue-700">{{ format_price($order->total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if ($order->comment)
            <div class="card p-4">
                <h2 class="mb-1 text-body-md font-bold">Коментар клієнта</h2>
                <p class="text-body-sm text-on-surface-variant">{{ $order->comment }}</p>
            </div>
        @endif
    </div>

    {{-- Панель керування --}}
    <aside class="flex flex-col gap-4">
        <div class="card p-4">
            <h2 class="mb-3 text-body-md font-bold">Змінити статус</h2>
            <form action="{{ route('admin.orders.status', $order) }}" method="post" class="flex gap-2">
                @csrf
                <select name="status" class="input py-2 text-body-sm">
                    @foreach ($labels as $key => $label)
                        <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary px-4 py-2 text-body-sm">Зберегти</button>
            </form>
        </div>

        <div class="card p-4 text-body-sm">
            <h2 class="mb-3 text-body-md font-bold">Клієнт і доставка</h2>
            <dl class="flex flex-col gap-2">
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Отримувач</dt>
                    <dd class="text-right font-semibold">{{ $order->name }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Телефон</dt>
                    <dd class="tabular text-right font-semibold">{{ $order->phone }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Пошта</dt>
                    <dd class="text-right font-semibold">{{ $order->email ?? '—' }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Місто</dt>
                    <dd class="text-right font-semibold">{{ $order->city }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Відділення</dt>
                    <dd class="text-right font-semibold">{{ $order->address ?? '—' }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Доставка</dt>
                    <dd class="text-right font-semibold">{{ \App\Models\Order::DELIVERY_LABELS[$order->delivery] ?? $order->delivery }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Оплата</dt>
                    <dd class="text-right font-semibold">{{ \App\Models\Order::PAYMENT_LABELS[$order->payment] ?? $order->payment }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Дата</dt>
                    <dd class="tabular text-right font-semibold">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-on-surface-variant">Клієнт сайту</dt>
                    <dd class="text-right font-semibold">{{ $order->user?->name ?? 'Гість' }}</dd>
                </div>
            </dl>
        </div>
    </aside>
</div>
@endsection
