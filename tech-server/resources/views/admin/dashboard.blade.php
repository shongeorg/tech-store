@extends('layouts.admin')

@section('title')Дашборд — Адмінка@endsection

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ([
        ['Товарів у каталозі', number_format($stats['products']), 'inventory_2', 'text-blue-600 bg-blue-50'],
        ['Активних товарів', number_format($stats['activeProducts']), 'check_circle', 'text-emerald-600 bg-emerald-50'],
        ['Категорій', number_format($stats['categories']), 'category', 'text-slate-600 bg-slate-100'],
        ['Промокодів', \App\Models\PromoCode::count(), 'sell', 'text-orange-600 bg-orange-50'],
    ] as [$label, $value, $icon, $chip])
        <div class="card p-4">
            <div class="flex items-center justify-between">
                <p class="text-caption font-bold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                <span class="grid h-8 w-8 place-items-center rounded-md {{ $chip }}">
                    <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                </span>
            </div>
            <p class="tabular mt-2 text-headline-lg">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ([
        ['Замовлень за все', number_format($stats['orders']), 'receipt_long'],
        ['Нових замовлень', number_format($stats['newOrders']), 'fiber_new'],
        ['Замовлень сьогодні', number_format($stats['ordersToday']), 'today'],
        ['Виторг (без скас.), ₴', format_price($stats['revenue']), 'payments'],
    ] as [$label, $value, $icon])
        <div class="card p-4">
            <p class="flex items-center gap-2 text-caption font-bold uppercase tracking-wide text-slate-500">
                <span class="material-symbols-outlined text-[16px] text-blue-600">{{ $icon }}</span>{{ $label }}
            </p>
            <p class="tabular mt-2 text-headline-lg">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-2">
    {{-- Останні замовлення --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h2 class="text-body-md font-bold">Останні замовлення</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-body-sm font-semibold text-blue-600 hover:underline">Всі замовлення →</a>
        </div>
        <table class="w-full text-body-sm">
            <thead class="bg-slate-50 text-caption text-slate-500">
                <tr>
                    <th class="px-4 py-2 text-left">№</th>
                    <th class="px-4 py-2 text-left">Клієнт</th>
                    <th class="px-4 py-2 text-right">Сума</th>
                    <th class="px-4 py-2 text-left">Статус</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $o)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-2 font-semibold text-blue-700">ТП-{{ $o->id }}</td>
                        <td class="px-4 py-2">{{ $o->name }}</td>
                        <td class="tabular px-4 py-2 text-right font-semibold">{{ format_price($o->total) }}</td>
                        <td class="px-4 py-2">
                            <span class="badge {{ $o->status === 'new' ? 'bg-orange-50 text-orange-600' : ($o->status === 'done' ? 'bg-emerald-50 text-emerald-700' : 'badge-muted') }}">
                                {{ \App\Models\Order::STATUS_LABELS[$o->status] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-on-surface-variant">Замовлень поки немає</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Розподіл товарів за категоріями --}}
    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-4 py-3">
            <h2 class="text-body-md font-bold">Товари за категоріями</h2>
        </div>
        <ul class="divide-y divide-slate-100 text-body-sm">
            @foreach ($byCategory as $row)
                <li class="flex items-center justify-between px-4 py-2">
                    <span>{{ $row->name }}</span>
                    <span class="tabular font-semibold text-blue-700">{{ $row->count }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
