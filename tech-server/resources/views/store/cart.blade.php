@extends('layouts.store')

@section('title')Кошик — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="shell py-space-md">
    {{-- Резерв ціни --}}
    <div class="card mb-space-sm flex flex-wrap items-center justify-between gap-space-sm px-space-md py-space-sm">
        <p class="flex items-center gap-2 text-body-sm font-semibold text-emerald-600">
            <span class="material-symbols-outlined text-[18px]">inventory</span>
            Товари зарезервовано на час оформлення
        </p>
        <button type="button" class="btn-outline px-space-sm py-1.5 text-body-sm">Фіксація ціни</button>
    </div>

    {{-- Безкоштовна доставка --}}
    <div class="card mb-space-md p-space-md">
        <div class="mb-space-xs flex items-center justify-between text-body-sm">
            <span class="flex items-center gap-2 font-semibold">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">local_shipping</span>
                Безкоштовна доставка
            </span>
            <span class="font-bold text-emerald-600">100% виконано</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full w-full rounded-full bg-emerald-500"></div>
        </div>
        <p class="mt-space-xs text-body-sm text-on-surface-variant">
            Нова Пошта і самовивіз із магазинів безкоштовні для цього кошика
        </p>
    </div>

    <div class="grid gap-space-xl lg:grid-cols-[1fr_360px]">
        <div class="min-w-0">
            {{-- Заголовок --}}
            <div class="mb-space-md flex flex-wrap items-center gap-space-md">
                <h1 class="text-headline-lg tracking-tight">Кошик</h1>
                <span class="badge bg-blue-50 text-blue-700">{{ $totals['count'] }}&nbsp;{{ $totals['count'] === 1 ? 'товар' : 'товарів' }}</span>
                <label class="ml-auto flex cursor-pointer items-center gap-2 text-body-sm text-on-surface-variant">
                    <input type="checkbox" checked class="h-3 w-3 accent-blue-600" />
                    Вибрати все ({{ $totals['count'] }})
                </label>
            </div>

            @forelse ($items as $item)
                @php($prod = $item['product'])
                <div class="card mb-space-sm flex flex-wrap gap-space-md p-space-md">
                    <input type="checkbox" checked class="mt-1 h-3 w-3 shrink-0 accent-blue-600" aria-label="Вибрати" />
                    <a href="{{ route('product', $prod->slug) }}" class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-lg bg-[#f8fafc]">
                        @php($img = collect($prod->images ?? [])->first())
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $prod->title }}" class="h-full w-full object-cover" />
                        @else
                            <span class="text-4xl">{{ $prod->emoji }}</span>
                        @endif
                    </a>
                    <div class="min-w-0 flex-1">
                        <p class="tabular text-caption text-outline">Арт. {{ mb_strtoupper(mb_substr($prod->slug, 0, 8)) }}</p>
                        <a href="{{ route('product', $prod->slug) }}" class="line-clamp-2 text-body-md font-semibold hover:text-blue-600">{{ $prod->title }}</a>
                        <p class="mt-1 text-body-sm text-on-surface-variant">{{ $prod->short }}</p>
                        <p class="mt-1 flex items-center gap-1 text-body-sm text-emerald-600">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> В наявності
                        </p>
                    </div>
                    <div class="flex flex-col items-end justify-between gap-space-sm">
                        <form action="{{ route('cart.remove') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $prod->id }}" />
                            <button type="submit" class="text-slate-400 hover:text-red-500" aria-label="Видалити">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </form>
                        <div class="flex items-center gap-space-sm">
                            {{-- Зменшити --}}
                            <form action="{{ route('cart.update') }}" method="post" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $prod->id }}" />
                                <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}" />
                                <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-300 text-slate-500 hover:border-blue-400">−</button>
                            </form>
                            <span class="tabular w-6 text-center font-semibold">{{ $item['qty'] }}</span>
                            <form action="{{ route('cart.update') }}" method="post" class="inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $prod->id }}" />
                                <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}" />
                                <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-300 text-slate-500 hover:border-blue-400">+</button>
                            </form>
                        </div>
                        <div class="flex items-baseline gap-space-xs">
                            @if ($prod->old_price)
                                <span class="tabular text-specs-mono text-slate-400 line-through">{{ format_price($prod->old_price * $item['qty']) }}</span>
                            @endif
                            <span class="tabular text-price-display-md">{{ format_price($prod->price * $item['qty']) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card flex flex-col items-center gap-space-md p-space-2xl text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-300">shopping_cart</span>
                    <p class="text-headline-md">Кошик порожній</p>
                    <p class="max-w-sm text-body-md text-on-surface-variant">
                        Додайте товари з каталогу — і ми зарезервуємо ціни на 30 хвилин.
                    </p>
                    <a href="{{ route('catalog') }}" class="btn-primary px-space-xl py-2.5 text-body-md">Перейти до каталогу</a>
                </div>
            @endforelse

            {{-- Промокод --}}
            @if ($totals['count'] > 0)
                <div class="card mt-space-md p-space-md">
                    <p class="mb-space-sm flex items-center gap-2 text-body-md font-bold">
                        <span class="material-symbols-outlined text-[18px] text-blue-600">sell</span>
                        Промокод та знижки
                    </p>
                    <form action="{{ route('cart.promo') }}" method="post" class="flex flex-wrap gap-space-sm">
                        @csrf
                        <input type="text" name="code" class="input max-w-64 flex-1 py-2.5 text-body-md uppercase" placeholder="TECHNO2026" />
                        <button type="submit" class="btn-outline px-space-lg py-2.5 text-body-md font-semibold">Застосувати</button>
                    </form>
                    @if ($totals['promoCode'])
                        <form action="{{ route('cart.promo.clear') }}" method="post" class="mt-space-sm flex items-center gap-1 text-body-sm font-semibold text-emerald-600">
                            @csrf
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            Знижка за промокодом {{ $totals['promoCode']->code }} застосована — {{ format_price($totals['promoDiscount']) }}
                            <button type="submit" class="ml-2 text-caption text-slate-400 underline hover:text-red-500">скасувати</button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Разом дешевше --}}
            <div class="mt-space-xl">
                <div class="mb-space-sm flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-headline-md">
                        <span class="material-symbols-outlined text-[20px] text-orange-600">shopping_basket</span>
                        Разом дешевше
                    </h2>
                    <a href="{{ route('catalog') }}" class="text-body-sm font-semibold text-blue-600 hover:underline">Спеції до замовлення →</a>
                </div>
                <div class="grid grid-cols-1 gap-space-sm sm:grid-cols-3">
                    @foreach ($upsells as $product)
                        @include('partials.product-card', ['product' => $product, 'variant' => 'home'])
                    @endforeach
                </div>
            </div>

            {{-- Доставка --}}
            <div class="card mt-space-md p-space-md">
                <p class="mb-space-sm text-body-md font-bold">Способи доставки для цього замовлення</p>
                <div class="flex flex-col gap-space-xs">
                    <label class="flex cursor-pointer items-center justify-between rounded-lg bg-blue-50/60 px-space-md py-space-sm text-body-sm">
                        <span class="flex items-center gap-2">
                            <input type="radio" name="delivery-preview" checked class="accent-blue-600" />
                            <span>
                                <b>Самовивіз із 42 магазинів «Технопростір»</b><br />
                                <span class="text-on-surface-variant">Київ, Львів, Дніпро, Одеса та ін.</span>
                            </span>
                        </span>
                        <b class="text-emerald-600">0 ₴ · сьогодні з 10:00</b>
                    </label>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg bg-blue-50/60 px-space-md py-space-sm text-body-sm">
                        <span class="flex items-center gap-2">
                            <input type="radio" name="delivery-preview" class="accent-blue-600" />
                            <span>
                                <b>У відділення або поштомат Нової Пошти</b><br />
                                <span class="text-on-surface-variant">Відправимо сьогодні до 18:00</span>
                            </span>
                        </span>
                        <b class="text-emerald-600">0 ₴ · завтра з 09:00</b>
                    </label>
                </div>
            </div>
        </div>

        {{-- Підсумок --}}
        <aside class="lg:sticky lg:top-44 lg:h-fit">
            <div class="card p-space-md">
                <h2 class="mb-space-md text-headline-md">Разом до сплати</h2>
                <dl class="flex flex-col gap-space-sm text-body-md">
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant">Вартість товарів ({{ $totals['count'] }} шт)</dt>
                        <dd class="tabular font-semibold">{{ format_price($totals['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between {{ $totals['promoDiscount'] === 0 ? 'hidden' : '' }}">
                        <dt class="text-on-surface-variant">Промокод {{ $totals['promoCode']?->code }}</dt>
                        <dd class="tabular font-semibold text-emerald-600">-{{ format_price($totals['promoDiscount']) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant">Доставка (Нова Пошта)</dt>
                        <dd class="font-semibold text-emerald-600">Безкоштовно</dd>
                    </div>
                </dl>

                <div class="mt-space-md border-t border-outline-variant pt-space-md">
                    <div class="flex items-baseline justify-between">
                        <span class="text-body-md font-semibold">Фінальна сума</span>
                        <span class="tabular text-price-display-lg text-blue-700">{{ format_price($totals['total']) }}</span>
                    </div>
                    <p class="mt-1 text-right text-caption text-outline">Всі ціни вже включають ПДВ 20%</p>
                </div>

                <a href="{{ route('checkout') }}"
                   class="btn-primary mt-space-md flex items-center justify-center gap-2 py-3.5 text-headline-sm {{ $totals['count'] === 0 ? 'pointer-events-none opacity-50' : '' }}">
                    Оформити замовлення <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>

                <div class="mt-space-sm grid grid-cols-2 gap-space-sm">
                    <button type="button" onclick="window.location.href='{{ route('checkout') }}'" class="btn-outline py-2.5 text-body-sm font-semibold">⚡ Купити в 1 клік</button>
                    <button type="button" class="flex items-center justify-center gap-1 rounded-lg bg-slate-900 py-2.5 text-body-sm font-semibold text-white">
                        Apple Pay
                    </button>
                </div>

                <div class="mt-space-md grid grid-cols-3 gap-space-xs border-t border-outline-variant pt-space-md text-center text-[11px] text-on-surface-variant">
                    <div>
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">verified</span>
                        <p>Офіційна техніка</p>
                        <p class="text-outline">UA/UA сертифіковано</p>
                    </div>
                    <div>
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">autorenew</span>
                        <p>14 днів обмін</p>
                        <p class="text-outline">Без зайвих питань</p>
                    </div>
                    <div>
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">lock</span>
                        <p>256-біт SSL</p>
                        <p class="text-outline">Безпечний платіж</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
