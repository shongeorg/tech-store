@extends('layouts.admin')

@section('title')Замовлення — Адмінка@endsection

@section('content')
<div class="mb-4 flex flex-wrap gap-2">
    <a href="{{ route('admin.orders.index') }}" class="chip {{ $currentStatus === '' ? 'is-active' : '' }}">Всі</a>
    @foreach ($statuses as $s)
        <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="chip {{ $currentStatus === $s ? 'is-active' : '' }}">{{ $labels[$s] }}</a>
    @endforeach
</div>

<div class="card overflow-x-auto">
    <table class="w-full min-w-3xl text-body-sm">
        <thead class="bg-slate-50 text-caption text-slate-500">
            <tr>
                <th class="px-3 py-2 text-left">№</th>
                <th class="px-3 py-2 text-left">Дата</th>
                <th class="px-3 py-2 text-left">Клієнт</th>
                <th class="px-3 py-2 text-left">Місто / доставка</th>
                <th class="px-3 py-2 text-right">Сума</th>
                <th class="px-3 py-2 text-left">Статус</th>
                <th class="px-3 py-2"><span class="sr-only">Дії</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $o)
                <tr class="border-t border-slate-100">
                    <td class="px-3 py-2 font-semibold text-blue-700">ТП-{{ $o->id }}</td>
                    <td class="tabular px-3 py-2 text-slate-500">{{ $o->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-3 py-2">
                        {{ $o->name }}<br />
                        <span class="text-caption text-outline">{{ $o->phone }}</span>
                    </td>
                    <td class="px-3 py-2 text-on-surface-variant">{{ $o->city }} · {{ \App\Models\Order::DELIVERY_LABELS[$o->delivery] ?? $o->delivery }}</td>
                    <td class="tabular px-3 py-2 text-right font-bold">{{ format_price($o->total) }}</td>
                    <td class="px-3 py-2">
                        <span class="badge {{ $o->status === 'new' ? 'bg-orange-50 text-orange-600' : ($o->status === 'done' ? 'bg-emerald-50 text-emerald-700' : ($o->status === 'canceled' ? 'badge-action' : 'badge-muted')) }}">
                            {{ $labels[$o->status] }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right">
                        <a href="{{ route('admin.orders.show', $o) }}" class="btn-outline px-3 py-1.5 text-caption font-semibold">Деталі</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-3 py-8 text-center text-on-surface-variant">Замовлень немає</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.pagination', ['paginator' => $orders])
@endsection
