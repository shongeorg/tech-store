@extends('layouts.store')

@section('title')ТЕХНОПРОСТІР — Інтернет-магазин електроніки та побутової техніки в Україні@endsection

@section('content')
{{-- ============ HERO ============ --}}
<section class="shell pt-space-lg">
    <div class="grid gap-space-md lg:grid-cols-[1fr_340px]">
        <div class="card overflow-hidden p-space-xl lg:p-space-2xl">
            <div class="mb-space-md flex flex-wrap gap-space-sm">
                <span class="badge bg-blue-50 text-blue-700">ОНЛАЙН-ЕКСПО 2026</span>
                <span class="badge bg-slate-100 text-slate-600">⚡ AI Neural Acceleration</span>
            </div>
            <h1 class="max-w-2xl text-display-hero-mobile tracking-tight text-on-surface lg:text-display-hero">
                Флагманські технології 2026: Ноутбуки з AI-чіпами
            </h1>
            <p class="mt-space-md max-w-2xl text-body-md text-on-surface-variant lg:text-body-lg">
                Потужні мобільні процесори. Оновлені лінійки з інтеграцією нейромережевих прискорювачів NPU
                для творчості, моделювання та швидкої роботи з великими файлами.
            </p>
            <div class="mt-space-lg grid max-w-xl grid-cols-3 gap-space-md">
                <div class="rounded-lg bg-slate-50 p-space-md">
                    <p class="text-caption text-slate-500">Знижка</p>
                    <p class="text-headline-lg text-red-600">-25%</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-space-md">
                    <p class="text-caption text-slate-500">Доставка</p>
                    <p class="text-headline-lg text-on-surface">1–2 дні</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-space-md">
                    <p class="text-caption text-slate-500">Гарантія</p>
                    <p class="text-headline-lg text-emerald-600">24 міс</p>
                </div>
            </div>
            <div class="mt-space-lg flex flex-wrap gap-space-sm">
                <a href="{{ route('catalog.category', 'laptops') }}" class="btn-primary flex items-center gap-2 px-space-xl py-3 text-body-md">
                    Обрати флагман <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
                <a href="{{ route('catalog') }}" class="btn-outline px-space-xl py-3 text-body-md">Всі акційні пропозиції</a>
            </div>
        </div>

        <div class="flex flex-col gap-space-md">
            {{-- Щоденні гарячі деали --}}
            @if ($dailyDeal)
                <div class="card overflow-hidden">
                    <div class="flex items-center justify-between bg-emerald-600 px-space-md py-2 text-white">
                        <span class="flex items-center gap-1 text-caption font-bold uppercase tracking-wide">
                            <span class="material-symbols-outlined text-[16px]">bolt</span> Щоденні гарячі деали
                        </span>
                        <span class="text-caption font-semibold">--:--:--</span>
                    </div>
                    <div class="p-space-md">
                        <p class="text-headline-sm font-bold">{{ $dailyDeal->title }}</p>
                        <p class="mt-1 text-body-sm text-on-surface-variant">{{ $dailyDeal->short }}</p>
                        <div class="mt-space-sm flex items-center justify-between">
                            <span class="tabular text-price-display-md">{{ format_price($dailyDeal->price) }}</span>
                            <button type="submit" form="daily-deal-add" class="btn-primary px-space-lg py-2 text-body-sm">Купити</button>
                            <form id="daily-deal-add" action="{{ route('cart.add') }}" method="post" class="hidden">
                                @csrf
                                <input type="hidden" name="product" value="{{ $dailyDeal->slug }}" />
                                <input type="hidden" name="qty" value="1" />
                                <input type="hidden" name="redirect_cart" value="1" />
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Trade-in --}}
            <div class="card flex-1 overflow-hidden">
                <div class="flex items-center gap-2 bg-orange-50 px-space-md py-2">
                    <span class="material-symbols-outlined text-[18px] text-orange-600">autorenew</span>
                    <span class="text-caption font-bold uppercase tracking-wide text-orange-700">Trade-in старий смартфон</span>
                </div>
                <div class="p-space-md">
                    <p class="text-body-md font-semibold leading-snug">
                        «Обміняйте свій пристрій — миттєва знижка на новий смартфон або ноутбук»
                    </p>
                    <p class="mt-space-sm text-body-sm text-on-surface-variant">Оцініть пристрій та отримайте до</p>
                    <div class="flex items-center justify-between">
                        <span class="tabular text-price-display-md text-blue-700">до 18 000 ₴</span>
                        <a href="#" class="btn-secondary px-space-md py-2 text-body-sm">Розрахувати вартість</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ КАТЕГОРІЇ ============ --}}
<section class="shell mt-space-2xl">
    <div class="mb-space-md flex items-center justify-between">
        <h2 class="text-headline-lg tracking-tight">Популярні категорії</h2>
        <a href="{{ route('catalog') }}" class="flex items-center gap-1 text-body-sm font-semibold text-blue-600 hover:underline">
            Всі категорії ({{ $homeCategories->count() }}) <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
    <p class="mb-space-md text-body-md text-on-surface-variant">Швидкий доступ до розділів спеціалізованої техніки</p>
    <div class="grid grid-cols-2 gap-space-sm sm:grid-cols-4 lg:grid-cols-8">
        @foreach ($homeCategories as $c)
            <a href="{{ route('catalog.category', $c->slug) }}" class="card card-hover flex flex-col items-center gap-space-sm p-space-md text-center">
                @php($catProducts = $c->products()->where('is_active', true)->count())
                <span class="grid h-12 w-12 place-items-center rounded-lg bg-blue-50 text-xl">
                    {{ $c->emoji ?? '🧩' }}
                </span>
                <span class="text-body-sm font-semibold leading-tight">{{ $c->name }}</span>
                <span class="tabular text-caption text-outline">{{ $catProducts }} товарів</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============ ХІТИ ПРОДАЖІВ ============ --}}
<section class="shell mt-space-2xl" id="hits">
    <div class="mb-space-md flex items-center justify-between">
        <h2 class="flex items-center gap-3 text-headline-lg tracking-tight">
            <span class="h-6 w-1.5 rounded-full bg-blue-600"></span>Хіти продажів
        </h2>
        <p class="hidden items-center gap-2 text-body-sm text-on-surface-variant md:flex">
            <span class="material-symbols-outlined text-[16px] text-emerald-600">verified</span>
            Середній рейтинг: <b class="text-on-surface">98.4%</b> доступно
        </p>
    </div>
    <div class="grid grid-cols-1 gap-space-md sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($hits as $product)
            @include('partials.product-card', ['product' => $product, 'variant' => 'home'])
        @endforeach
    </div>
</section>

{{-- ============ ПРОМО-БАНЕР ============ --}}
<section class="shell mt-space-2xl">
    <div class="grid items-center gap-space-xl rounded-xl bg-slate-900 p-space-xl lg:grid-cols-2 lg:p-space-2xl">
        <div>
            <span class="badge mb-space-md inline-flex bg-orange-500 text-white">
                <span class="material-symbols-outlined mr-1 text-[14px]">local_fire_department</span> Гаряча пропозиція
            </span>
            <h2 class="max-w-lg text-headline-lg text-white">
                Тиждінь кіберзнижок на комп'ютерні комплектуючі
            </h2>
            <p class="mt-space-sm max-w-lg text-body-md text-slate-300">
                Оновлюйте арсенал для геймінгу та роботи: відеокарти GeForce RTX, процесори AMD Ryzen,
                швидкі накопичувачі NVMe SSD зі знижками до 30% на більшість позицій та безкоштовним встановленням у нашому сервісі.
            </p>
            <div class="mt-space-md flex flex-wrap gap-space-sm">
                <span class="rounded-md bg-white/10 px-space-sm py-1 text-body-sm text-slate-200">Відеокарти <b class="text-emerald-400">до 10% бонусних</b></span>
                <span class="rounded-md bg-white/10 px-space-sm py-1 text-body-sm text-slate-200">Кредит <b class="text-emerald-400">0% на 12 платежів</b></span>
                <span class="rounded-md bg-white/10 px-space-sm py-1 text-body-sm text-slate-200">Розтермінування <b class="text-emerald-400">36 місяців</b></span>
            </div>
        </div>
        <div class="flex flex-col items-start gap-space-md lg:items-end">
            <div class="w-full rounded-lg bg-white/5 p-space-md">
                <p class="mb-space-sm text-caption font-bold uppercase tracking-wide text-slate-400">До закінчення акції залишилось</p>
                <div class="grid grid-cols-4 gap-space-sm text-center">
                    @foreach ([['18', 'Годин'], ['42', 'Хвилин'], ['03', 'Секунд'], ['16', 'Мс']] as [$v, $l])
                        <div class="rounded-md bg-slate-800 py-space-sm">
                            <p class="tabular text-headline-md text-white">{{ $v }}</p>
                            <p class="text-[10px] text-slate-400">{{ $l }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('catalog.category', 'components') }}" class="btn-commercial flex items-center gap-2 px-space-xl py-3 text-body-md">
                Переглянути всі комплектуючі <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

{{-- ============ НОВИНКИ ============ --}}
<section class="shell mt-space-2xl">
    <div class="mb-space-md flex items-center justify-between">
        <h2 class="flex items-center gap-3 text-headline-lg tracking-tight">
            <span class="h-6 w-1.5 rounded-full bg-emerald-500"></span>Новинки магазину
        </h2>
        <a href="{{ route('catalog') }}" class="flex items-center gap-1 text-body-sm font-semibold text-blue-600 hover:underline">
            Всі новинки <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>
    <p class="mb-space-md text-body-md text-on-surface-variant">Свіжі надходження серед технологічних новинок (що є в наявності)</p>
    <div class="grid grid-cols-1 gap-space-md sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($novelties as $product)
            @include('partials.product-card', ['product' => $product, 'variant' => 'home'])
        @endforeach
    </div>
</section>

{{-- ============ ПЕРЕВАГИ ============ --}}
<section class="mt-space-2xl bg-surface-container-low py-space-2xl">
    <div class="shell">
        <div class="mb-space-lg text-center">
            <p class="text-caption font-bold uppercase tracking-[0.1em] text-blue-600">Чому обирають нас</p>
            <h2 class="mt-space-xs text-headline-lg tracking-tight">Омніканальний ритейл нового стандарту</h2>
            <p class="mt-space-xs text-body-md text-on-surface-variant">
                Ми будуємо безперервну цифрову екосистему: 48 магазинів, доставка по всій Україні, сервіс у 100% випадків швидкого звернення.
            </p>
        </div>
        <div class="grid gap-space-md sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => 'verified', 'title' => 'Офіційна гарантія', 'text' => 'Багаторічна офіційна гарантія від виробника і прозора політика повернення відповідно до законодавства України.', 'chip' => '100% Legal Import', 'chipClass' => 'text-emerald-700 bg-emerald-50'],
                ['icon' => 'local_shipping', 'title' => 'Швидка доставка', 'text' => 'Відправлення в день замовлення до 18:00. 450 пунктів видачі Нової Пошти та власні магазини у 24 містах.', 'chip' => 'Доставка з 30 хв по Києву', 'chipClass' => 'text-blue-700 bg-blue-50'],
                ['icon' => 'credit_card', 'title' => 'Розтермінування 0%', 'text' => 'Оплата частинами без переплат до 24 місяців від ПриватБанку, monobank, А-Банку та Універсальної Карти.', 'chip' => '0% на 12 платежів', 'chipClass' => 'text-blue-700 bg-blue-50'],
                ['icon' => 'support_agent', 'title' => 'Експертний сервіс 24/7', 'text' => 'Служба турботи допомагає з вибором, підтверджує замовлення і вирішує питання після покупки.', 'chip' => 'Гаряча лінія 0 800 300 777', 'chipClass' => 'text-orange-700 bg-orange-50'],
            ] as $a)
                <div class="card p-space-md">
                    <span class="mb-space-sm grid h-10 w-10 place-items-center rounded-lg bg-blue-50">
                        <span class="material-symbols-outlined text-[22px] text-blue-600">{{ $a['icon'] }}</span>
                    </span>
                    <h3 class="text-headline-sm font-bold">{{ $a['title'] }}</h3>
                    <p class="mt-space-xs text-body-sm text-on-surface-variant">{{ $a['text'] }}</p>
                    <span class="mt-space-sm inline-flex rounded-md px-space-sm py-1 text-caption font-semibold {{ $a['chipClass'] }}">{{ $a['chip'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ БРЕНДИ ============ --}}
<section class="shell mt-space-2xl mb-space-2xl">
    <div class="mb-space-md flex items-center justify-between">
        <div>
            <h2 class="text-headline-lg tracking-tight">Офіційні партнери та бренди</h2>
            <p class="text-body-sm text-on-surface-variant">Прямі дистриб'юторські контракти та гарантія авторитету</p>
        </div>
        <span class="badge bg-blue-50 text-blue-700">100% AUTHORIZED</span>
    </div>
    <div class="card grid grid-cols-2 gap-px overflow-hidden bg-outline-variant sm:grid-cols-5 lg:grid-cols-10">
        @foreach ($brandsRow as $b)
            <span class="grid h-16 place-items-center bg-white text-body-md font-bold text-slate-500 transition hover:text-blue-600">{{ $b }}</span>
        @endforeach
    </div>
</section>
@endsection
