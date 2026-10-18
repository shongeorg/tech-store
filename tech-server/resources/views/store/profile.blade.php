@extends('layouts.store')

@section('title')Особистий кабінет — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="shell py-space-md">
    {{-- Профіль --}}
    <div class="card mb-space-md flex flex-wrap items-center gap-space-md p-space-md">
        <span class="grid h-14 w-14 place-items-center overflow-hidden rounded-full bg-blue-100 text-headline-md text-blue-700">
            @if ($user)
                {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
            @else
                Г
            @endif
        </span>
        <div>
            <p class="flex items-center gap-space-sm text-headline-md">
                {{ $user?->name ?? 'Гість' }}
                @if ($user)
                    <span class="badge bg-blue-600 text-white">PRO</span>
                @endif
            </p>
            <p class="text-body-sm text-on-surface-variant">{{ $user?->email ?? 'Увійдіть, щоб бачити всі свої замовлення' }}</p>
        </div>
        <div class="ml-auto flex gap-space-sm">
            @if ($user)
                <a href="{{ route('profile.edit') }}" class="btn-outline px-space-md py-2 text-body-sm">Налаштування</a>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn-outline px-space-md py-2 text-body-sm">Вийти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-primary px-space-md py-2 text-body-sm">Увійти</a>
            @endif
        </div>
    </div>

    {{-- Бонуси --}}
    <div class="mb-space-md flex flex-wrap items-center justify-between gap-space-md rounded-lg bg-blue-600 p-space-md text-white">
        <div>
            <p class="text-caption font-bold uppercase tracking-wide text-blue-100">Програма лояльності</p>
            <p class="tabular text-headline-lg">2 450 ₴ <span class="text-body-md font-normal text-blue-100">ТехноБонус</span></p>
        </div>
        <a href="#" class="rounded-lg bg-white/15 px-space-md py-2 text-body-sm font-semibold hover:bg-white/25">Історія →</a>
    </div>

    <!-- Навігація кабінету -->
    <div class="mb-space-md flex flex-wrap gap-space-sm">
        <span class="btn-primary px-space-lg py-2.5 text-body-md">Мої замовлення ({{ $user ? $orders->total() : ($lastGuestOrder ? 1 : 0) }})</span>
    </div>

    <div class="flex flex-col gap-space-md">
        @forelse ($orders as $order)
            <div class="card p-space-md">
                <div class="mb-space-sm flex flex-wrap items-center justify-between gap-space-sm">
                    <p class="text-body-md font-bold">№ ТП-{{ $order->id }} · {{ $order->created_at->translatedFormat('d.m.Y, H:i') }}</p>
                    <span class="badge {{ $order->status === 'done' ? 'bg-emerald-50 text-emerald-700' : 'bg-orange-50 text-orange-600' }}">
                        {{ \App\Models\Order::STATUS_LABELS[$order->status] }}
                    </span>
                </div>
                <div class="mb-space-sm rounded-lg bg-surface-container-low p-space-sm text-body-sm">
                    <p class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <b>{{ \App\Models\Order::DELIVERY_LABELS[$order->delivery] ?? $order->delivery }}</b> · {{ $order->city }}
                    </p>
                    <p class="mt-1 text-on-surface-variant">Отримувач: {{ $order->name }}, {{ $order->phone }}</p>
                    <p class="mt-1 text-on-surface-variant">{{ $order->address }}</p>
                </div>
                <ul class="mb-space-sm flex flex-col gap-1 text-body-sm text-on-surface-variant">
                    @foreach ($order->items as $item)
                        <li class="flex items-center justify-between">
                            <a href="{{ $item->product_id ? route('product', $item->slug) : '#' }}" class="hover:text-blue-600">{{ $item->title }} × {{ $item->qty }}</a>
                            <span class="tabular font-semibold text-on-surface">{{ format_price($item->total) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="flex items-center justify-between border-t border-outline-variant pt-space-sm">
                    <span class="text-body-sm text-on-surface-variant">{{ \App\Models\Order::PAYMENT_LABELS[$order->payment] ?? $order->payment }}</span>
                    <span class="tabular text-price-display-md">{{ format_price($order->total) }}</span>
                </div>
            </div>
        @empty
            @if ($lastGuestOrder)
                <div class="card p-space-md">
                    <p class="mb-space-sm text-body-md font-bold">Останнє замовлення (гострове)</p>
                    <div class="mb-space-sm rounded-lg bg-surface-container-low p-space-sm text-body-sm">
                        <p class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <b>{{ \App\Models\Order::DELIVERY_LABELS[$lastGuestOrder->delivery] ?? $lastGuestOrder->delivery }}</b> · {{ $lastGuestOrder->city }} · Готуємо до відправлення
                        </p>
                        <p class="mt-1 text-on-surface-variant">Отримувач: {{ $lastGuestOrder->name }}, {{ $lastGuestOrder->phone }}</p>
                    </div>
                    <div class="flex items-center justify-between border-t border-outline-variant pt-space-sm">
                        <span class="text-body-sm text-on-surface-variant">{{ \App\Models\Order::PAYMENT_LABELS[$lastGuestOrder->payment] ?? $lastGuestOrder->payment }} · {{ $lastGuestOrder->items->count() }} позицій</span>
                        <span class="tabular text-price-display-md">{{ format_price($lastGuestOrder->total) }}</span>
                    </div>
                </div>
            @else
                <div class="card flex flex-col items-center gap-space-md p-space-2xl text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-300">receipt_long</span>
                    <p class="text-headline-md">Замовлень ще немає</p>
                    <p class="max-w-sm text-body-md text-on-surface-variant">
                        Оформіть перше замовлення — і воно з'явиться тут разом зі статусом доставки.
                    </p>
                    <a href="{{ route('catalog') }}" class="btn-primary px-space-xl py-2.5 text-body-md">Перейти до каталогу</a>
                </div>
            @endif
        @endforelse

        @if ($user && $orders->hasPages())
            @include('partials.pagination', ['paginator' => $orders])
        @endif
    </div>

    {{-- Підтримка --}}
    <div class="card mt-space-md flex flex-wrap items-center gap-space-md bg-blue-50/50 p-space-md">
        <span class="material-symbols-outlined text-3xl text-blue-600">support_agent</span>
        <div class="min-w-60 flex-1">
            <p class="text-body-md font-bold">Потрібна допомога із замовленням?</p>
            <p class="text-body-sm text-on-surface-variant">Служба турботи ТЕХНОПРОСТІР працює щодня з 08:00 до 22:00.</p>
        </div>
        <a href="tel:0800300777" class="btn-outline bg-white px-space-md py-2 text-body-sm font-semibold">0 800 300 777</a>
    </div>
</div>
@endsection
