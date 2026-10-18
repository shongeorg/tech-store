@extends('layouts.store')

@section('title')Замовлення оформлено — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="shell flex justify-center py-space-2xl">
    <div class="w-full max-w-2xl">
        <div class="card p-space-xl text-center">
            <span class="mx-auto mb-space-md grid h-16 w-16 place-items-center rounded-full bg-emerald-100">
                <span class="material-symbols-outlined text-4xl text-emerald-600">check_circle</span>
            </span>
            <h1 class="text-headline-xl tracking-tight">Замовлення оформлено</h1>
            <p class="mt-space-sm text-body-md text-on-surface-variant">
                Дякуємо за покупку! Ми надіслали підтвердження на вашу пошту та SMS-повідомлення.
            </p>

            @if ($order)
                <dl class="mt-space-lg flex flex-col gap-space-sm rounded-lg bg-surface-container-low p-space-md text-left text-body-md">
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Номер замовлення</dt>
                        <dd class="tabular font-bold text-blue-700">№ ТП-{{ $order->id }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Сума</dt>
                        <dd class="tabular font-bold">{{ format_price($order->total) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Отримувач</dt>
                        <dd class="font-semibold">{{ $order->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Спосіб доставки</dt>
                        <dd class="text-right font-semibold">{{ \App\Models\Order::DELIVERY_LABELS[$order->delivery] ?? $order->delivery }}, {{ $order->address }}, {{ $order->city }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Спосіб оплати</dt>
                        <dd class="text-right font-semibold">{{ \App\Models\Order::PAYMENT_LABELS[$order->payment] ?? $order->payment }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-space-sm">
                        <dt class="text-on-surface-variant">Орієнтовна дата доставки</dt>
                        <dd class="tabular font-semibold text-emerald-600">{{ \Illuminate\Support\Carbon::now()->addDays(2)->translatedFormat('d.m.Y') }}</dd>
                    </div>
                </dl>
            @else
                <p class="mt-space-lg rounded-lg bg-surface-container-low p-space-md text-body-md text-on-surface-variant">
                    Інформація про останнє замовлення недоступна — оформіть нове замовлення.
                </p>
            @endif

            <div class="mt-space-lg flex flex-col gap-space-sm sm:flex-row">
                <a href="{{ route('profile') }}" class="btn-outline flex-1 py-3 text-body-md font-semibold">Мої замовлення</a>
                <a href="{{ route('catalog') }}" class="btn-primary flex-1 py-3 text-body-md">Продовжити покупки</a>
            </div>

            <p class="mt-space-md text-body-sm text-on-surface-variant">
                Служба турботи ТЕХНОПРОСТІР: <b>0 800 300 777</b> (щодня 08:00–21:00, безкоштовно по Україні)
            </p>
        </div>
    </div>
</div>
@endsection
