{{-- Каталог товарів (дизайн: CatalogView.astro). Фільтри — серверні (GET), пагінація обов'язкова --}}
@extends('layouts.store')

@section('title'){{ ($title ?? 'Каталог товарів') }} — ТЕХНОПРОСТІР@endsection

@section('content')
@php($title = $headerTitle ?? ($title ?? 'Каталог товарів'))
<div data-catalog-root>
    @php($total = $products->total())
    {{-- Breadcrumbs --}}
    <nav class="shell flex items-center gap-1 py-space-sm text-body-sm text-on-surface-variant" aria-label="Хлібні крихти">
        @foreach ($crumbs as $i => $c)
            @if ($i > 0)
                <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
            @endif
            @if (! empty($c['href']))
                <a href="{{ $c['href'] }}" class="hover:text-primary">{{ $c['label'] }}</a>
            @else
                <span class="font-medium text-on-surface">{{ $c['label'] }}</span>
            @endif
        @endforeach
    </nav>

    <div class="shell pb-space-2xl">
        {{-- Заголовок --}}
        <div class="mb-space-md flex flex-wrap items-end justify-between gap-space-md">
            <div>
                <h1 class="text-headline-xl tracking-tight text-on-surface">
                    {{ $title }}
                    <span class="ml-2 text-body-md font-normal text-outline">{{ $total }} {{ $total % 10 === 1 && $total % 100 !== 11 ? 'товар' : ($total % 10 >= 2 && $total % 10 <= 4 && ($total % 100 < 10 || $total % 100 >= 20) ? 'товари' : 'товарів') }}</span>
                </h1>
                @if (! empty($description))
                    <p class="mt-1 max-w-4xl text-body-md text-on-surface-variant">{{ $description }}</p>
                @endif
            </div>
            <p class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Склад у Києві: 94% наявність
                <span class="text-outline">• Оновлено: сьогодні о 09:30</span>
            </p>
        </div>

        {{-- Форма фільтрів (автоsubmit) --}}
        <form action="{{ request()->fullUrl() }}" method="get" id="tp-filters-form" data-filters-form>
            @if (! empty($searchQuery))
                <input type="hidden" name="q" value="{{ $searchQuery }}" />
            @endif
            @if (! empty($selectedCategorySlug ?? null))
                <input type="hidden" name="cat" value="{{ $selectedCategorySlug }}" />
            @endif
            {{-- Чіпи брендів (badge-посилання + input для цього чіпа) --}}
            @php($chipBrands = array_slice(array_keys($brandOptions), 0, 7))
            @if (count($chipBrands) > 1)
                <div class="mb-space-md flex flex-wrap gap-space-sm">
                    <a href="{{ url()->current() }}" class="chip {{ count($selectedBrands) === 0 ? 'is-active' : '' }}">Усі</a>
                    @foreach ($chipBrands as $b)
                        <label class="chip {{ in_array($b, $selectedBrands) ? 'is-active' : '' }} cursor-pointer">
                            <input type="checkbox" name="brands" value="{{ $b }}" class="hidden"
                                   @checked(in_array($b, $selectedBrands)) onchange="document.getElementById('tp-filters-form').submit()" />
                            {{ $b }}
                            <span class="tabular opacity-70">{{ $brandOptions[$b] }}</span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="flex gap-space-xl">
                {{-- ================= ФІЛЬТРИ ================= --}}
                <aside class="filters-panel" data-filters-panel aria-label="Фільтри">
                    <div class="card p-space-md">
                        <div class="mb-space-md flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-headline-sm">Фільтри</h2>
                            <button type="button" class="lg:hidden" data-close-filters aria-label="Закрити">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <div class="flex flex-col gap-space-xl">
                            {{-- Ціна --}}
                            <section>
                                <h3 class="mb-space-sm flex items-center gap-2 text-body-md font-bold">
                                    <span class="material-symbols-outlined text-[18px] text-blue-600">payments</span>Ціна, ₴
                                </h3>
                                <div class="mb-space-sm flex items-center gap-space-sm">
                                    <input type="number" name="price_min" class="input py-2 text-body-sm" placeholder="Від"
                                           value="{{ $priceMin ?: '' }}" />
                                    <input type="number" name="price_max" class="input py-2 text-body-sm" placeholder="До"
                                           value="{{ $priceMax ?: '' }}" />
                                </div>
                                <div class="mt-1 flex justify-between text-specs-mono text-slate-500">
                                    <span>Мінімум 0 ₴</span>
                                    <span>Максимум без обмежень</span>
                                </div>
                            </section>

                            {{-- Наявність --}}
                            <section>
                                <h3 class="mb-space-sm flex items-center gap-2 text-body-md font-bold">
                                    <span class="material-symbols-outlined text-[18px] text-blue-600">inventory_2</span>Наявність та доставка
                                </h3>
                                <label class="mb-2 flex cursor-pointer items-center justify-between rounded-md px-2 py-1.5 hover:bg-slate-50">
                                    <span class="flex items-center gap-2 text-body-md">
                                        <input type="radio" name="stock" value="in" class="h-3 w-3 accent-blue-600" onchange="document.getElementById('tp-filters-form').submit()" @checked($currentStock === 'in') />
                                        Тільки в наявності
                                    </span>
                                </label>
                                <label class="flex cursor-pointer items-center justify-between rounded-md px-2 py-1.5 hover:bg-slate-50">
                                    <span class="flex items-center gap-2 text-body-md">
                                        <input type="radio" name="stock" value="order" class="h-3 w-3 accent-blue-600" onchange="document.getElementById('tp-filters-form').submit()" @checked($currentStock === 'order') />
                                        Передзамовлення
                                    </span>
                                </label>
                                <label class="flex cursor-pointer items-center justify-between rounded-md px-2 py-1.5 hover:bg-slate-50">
                                    <span class="flex items-center gap-2 text-body-md">
                                        <input type="radio" name="stock" value="" class="h-3 w-3 accent-blue-600" onchange="document.getElementById('tp-filters-form').submit()" @checked($currentStock === '') />
                                        Всі позиції
                                    </span>
                                </label>
                            </section>

                            {{-- Кнопка застосувати --}}
                            <button type="submit" class="btn-primary w-full py-3 text-body-md">Застосувати фільтри</button>
                        </div>
                    </div>
                </aside>

                {{-- ================= ТОВАРИ ================= --}}
                <div class="min-w-0 flex-1">
                    {{-- Панель сортування --}}
                    <div class="card mb-space-md flex flex-wrap items-center gap-space-md px-space-md py-space-sm">
                        <button type="button" class="flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-body-sm font-semibold text-blue-700 lg:hidden" data-open-filters>
                            <span class="material-symbols-outlined text-[18px]">tune</span>
                            Фільтри
                        </button>
                        <label class="flex items-center gap-space-sm text-body-sm">
                            <span class="text-on-surface-variant">Сортування:</span>
                            <select name="sort" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-body-sm font-medium focus:border-blue-600 focus:outline-none"
                                    onchange="document.getElementById('tp-filters-form').submit()">
                                @foreach ($sorts as $key => $label)
                                    <option value="{{ $key }}" @selected($currentSort === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <p class="text-body-sm text-on-surface-variant">
                            Показано <span class="tabular font-semibold text-on-surface">{{ $products->count() }}</span>
                            із <span class="tabular">{{ $total }}</span> товарів
                        </p>
                        <div class="ml-auto hidden items-center gap-1 md:flex">
                            <button type="button" class="grid h-8 w-8 place-items-center rounded-md bg-blue-600 text-white" aria-label="Сітка">
                                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                            </button>
                        </div>
                    </div>

                    {{-- Активні фільтри --}}
                    @if (count($selectedBrands) > 0 || $priceMin > 0 || $priceMax > 0 || $currentStock !== '')
                        <div class="mb-space-md flex flex-wrap items-center gap-space-sm">
                            <span class="text-caption font-bold uppercase tracking-wide text-outline">Фільтри:</span>
                            <a href="{{ url()->current() }}" class="text-body-sm font-semibold text-blue-600 hover:underline">Очистити всі</a>
                        </div>
                    @endif

                    {{-- Сітка --}}
                    <div class="grid grid-cols-1 gap-space-md sm:grid-cols-2 xl:grid-cols-3" data-product-grid>
                        @forelse ($products as $product)
                            @include('partials.product-card', ['product' => $product, 'variant' => 'grid'])
                        @empty
                            <div class="card col-span-full flex flex-col items-center gap-space-md p-space-2xl text-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300">search_off</span>
                                <p class="text-headline-md">Товарів не знайдено</p>
                                <p class="max-w-md text-body-md text-on-surface-variant">
                                    Спробуйте змінити пошуковий запит або очистити фільтри, щоб побачити більше результатів.
                                </p>
                                <a href="{{ url()->current() }}" class="btn-primary px-6 py-2.5 text-body-md">Очистити фільтри</a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Пагінація (обов'язкова) --}}
                    @include('partials.pagination', ['paginator' => $products])

                    {{-- Переваги після каталогу --}}
                    <div class="mt-space-xl grid grid-cols-1 gap-space-md md:grid-cols-3">
                        <div class="card flex items-start gap-space-sm p-space-md">
                            <span class="material-symbols-outlined text-[22px] text-blue-600">verified</span>
                            <div>
                                <p class="text-body-md font-bold">100% Офіційна техніка</p>
                                <p class="text-body-sm text-on-surface-variant">Всі товари з гарантійним талоном виробника та сервісною підтримкою в Україні.</p>
                            </div>
                        </div>
                        <div class="card flex items-start gap-space-sm p-space-md">
                            <span class="material-symbols-outlined text-[22px] text-emerald-600">credit_card</span>
                            <div>
                                <p class="text-body-md font-bold">Оплата частинами 0%</p>
                                <p class="text-body-sm text-on-surface-variant">До 24 платежів від ПриватБанку, monobank та А-Банку без переплат.</p>
                            </div>
                        </div>
                        <div class="card flex items-start gap-space-sm p-space-md">
                            <span class="material-symbols-outlined text-[22px] text-orange-600">autorenew</span>
                            <div>
                                <p class="text-body-md font-bold">Легкий обмін 14 днів</p>
                                <p class="text-body-sm text-on-surface-variant">Міняйте відкритий товар або поверніть пристрій без зайвих бюрократій.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Мобайл-дrawer фільтрів
    const panel = document.querySelector('[data-filters-panel]');
    const openBtn = document.querySelector('[data-open-filters]');
    const closeBtns = document.querySelectorAll('[data-close-filters]');
    openBtn?.addEventListener('click', () => panel?.classList.add('is-open'));
    closeBtns.forEach((b) => b.addEventListener('click', () => panel?.classList.remove('is-open')));
</script>
@endpush
