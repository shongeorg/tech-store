@extends('layouts.store')

@section('title'){{ $p->title }} — ціна в Україні | ТЕХНОПРОСТІР@endsection

@section('content')
<div class="shell py-space-md">
    {{-- Breadcrumbs --}}
    <nav class="mb-space-md flex flex-wrap items-center gap-1 text-body-sm text-on-surface-variant" aria-label="Хлібні крихти">
        <a href="{{ route('home') }}" class="hover:text-primary">Головна</a>
        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
        <a href="{{ route('catalog') }}" class="hover:text-primary">Каталог</a>
        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
        <a href="{{ route('catalog.category', $category->slug) }}" class="hover:text-primary">{{ $category->name }}</a>
        <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
        <span class="font-medium text-on-surface">{{ $p->brand }}</span>
    </nav>

    {{-- Верх: галерея + блок купівлі --}}
    <div class="grid gap-space-xl lg:grid-cols-2">
        {{-- Галерея --}}
        <div>
            <div class="mb-space-sm flex flex-wrap gap-space-xs">
                @if ($p->badge)
                    <span class="badge badge-{{ $p->badge_kind ?? 'muted' }}">{{ $p->badge === 'ХІТ' ? 'ТОППРОДАЖ' : $p->badge }}</span>
                @endif
                @if ($p->installment)
                    <span class="badge bg-blue-50 text-blue-700">Розтермінування 0%</span>
                @endif
                @if ($p->old_price)
                    <span class="badge badge-action">-{{ round((1 - $p->price / $p->old_price) * 100) }}% знижка</span>
                @endif
            </div>

            <div class="card relative grid aspect-square place-items-center bg-white p-space-2xl">
                @php($discount = $p->old_price ? round((1 - $p->price / $p->old_price) * 100) : 0)
                <span class="absolute left-3 top-3 flex flex-col gap-1">
                    @if ($p->badge === 'ХІТ')
                        <span class="badge badge-hit">ХІТ ПРОДАЖУ</span>
                    @endif
                    @if ($p->installment)
                        <span class="badge badge-muted">Розтермінування 0%</span>
                    @endif
                    @if ($discount > 0)
                        <span class="badge badge-action">-{{ $discount }}% ЗНИЖКА</span>
                    @endif
                </span>
                <span class="absolute right-3 top-3 flex gap-space-xs">
                    <button type="button" class="grid h-9 w-9 place-items-center rounded-full border border-outline-variant bg-white/85 text-slate-500 hover:text-blue-600" aria-label="Порівняти">
                        <span class="material-symbols-outlined text-[18px]">compare_arrows</span>
                    </button>
                </span>

                @php($images = collect($p->images ?? [])->take(1))
                @if ($images->isNotEmpty())
                    <img src="{{ $images->first() }}" alt="{{ $p->title }}" id="tp-main-image"
                         class="max-h-full max-w-full rounded-lg object-contain" />
                @else
                    <span class="select-none text-[120px] leading-none sm:text-[160px]">{{ $p->emoji ?? '🧩' }}</span>
                @endif
            </div>

            {{-- Спец-смуга під головним фото --}}
            <div class="mt-space-sm grid grid-cols-2 gap-space-xs sm:grid-cols-4">
                @foreach (array_slice($p->specs ?? [], 0, 4) as $row)
                    @php($pair = spec_pair($row))
                    @continue($pair[0] === '')
                    <div class="rounded-lg bg-surface-container-low px-space-sm py-2 text-center">
                        <p class="truncate text-[11px] text-slate-500">{{ $pair[0] }}</p>
                        <p class="truncate text-body-sm font-semibold">{{ $pair[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Блок купівлі --}}
        <div class="flex flex-col gap-space-md">
            <div>
                <p class="mb-space-xs text-specs-mono text-slate-500">Артикул: ТП-{{ $p->sku }} · Гарантія 12 міс.</p>
                <h1 class="text-headline-xl leading-tight tracking-tight">{{ $p->title }}</h1>
                <div class="mt-space-sm flex flex-wrap items-center gap-space-md">
                    <span class="flex items-center gap-1 text-body-md">
                        <span class="text-amber-500">★★★★★</span>
                        <b>{{ number_format($p->rating, 1) }}</b>
                        <span class="text-outline">• {{ $p->reviews_count }} відгуків</span>
                    </span>
                    <a href="#reviews" class="text-body-sm text-blue-600 hover:underline">Залишити відгук</a>
                    <span class="flex items-center gap-1 text-body-sm text-emerald-600">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        {{ $p->stock === 'in' ? 'В наявності' : 'Передзамовлення' }}
                    </span>
                </div>
            </div>

            {{-- Ціна --}}
            <div class="card bg-blue-50/60 p-space-md">
                <div class="flex flex-wrap items-baseline gap-space-sm">
                    <span class="tabular text-[32px] font-extrabold leading-none text-on-surface">
                        {{ format_price($p->price) }}
                    </span>
                    @if ($p->old_price)
                        <span class="tabular text-body-md text-slate-500 line-through">{{ format_price($p->old_price) }}</span>
                        <span class="badge badge-action">-{{ $discount }}%</span>
                        <span class="text-body-sm font-semibold text-emerald-600">
                            Економія {{ format_price($p->old_price - $p->price) }}
                        </span>
                    @endif
                </div>
                <div class="mt-space-sm flex flex-col gap-1 text-body-sm text-on-surface-variant">
                    <p class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-emerald-600">redeem</span>
                        Кешбек <b class="text-emerald-600">+{{ format_price(round($p->price * 0.02)) }}</b> у «ТехноБонус» після отримання
                    </p>
                    <p class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-blue-600">credit_card</span>
                        або від <b class="text-blue-700">{{ format_price(round($p->price / 12)) }}/міс</b> · 15 платежів без переплати
                    </p>
                </div>
            </div>

            {{-- Вибір кольору --}}
            @if ($p->colors)
                <div>
                    <p class="mb-space-xs text-body-sm text-on-surface-variant">
                        Колір корпусу: <b class="text-on-surface">{{ $p->colors[0]['name'] }}</b>
                    </p>
                    <div class="flex gap-space-sm" data-color-group>
                        @foreach ($p->colors as $c)
                            <button type="button"
                                class="h-8 w-8 rounded-full border-2 shadow-sm transition {{ $loop->first ? 'border-blue-600 ring-2 ring-blue-200' : 'border-white' }}"
                                style="background-color:{{ $c['hex'] }}"
                                data-color="{{ $c['name'] }}"
                                data-color-label
                                aria-label="{{ $c['name'] }}"
                                title="{{ $c['name'] }}"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Вибір пам'яті --}}
            @if ($p->memory)
                <div>
                    <p class="mb-space-xs text-body-sm text-on-surface-variant">Вбудована пам'ять (НЗМ):</p>
                    <div class="flex flex-wrap gap-space-xs" data-mem-group>
                        @foreach ($p->memory as $m)
                            <button type="button"
                                class="rounded-md border px-space-md py-2 text-body-sm font-semibold transition {{ $loop->index === 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 bg-white hover:border-blue-400' }}"
                                data-mem="{{ $m }}">
                                {{ $m }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Кількість + кнопки --}}
            <div class="flex items-center gap-space-md">
                <div class="flex items-center rounded-lg border border-slate-300">
                    <button type="button" class="grid h-11 w-10 place-items-center text-slate-500 hover:text-blue-600" data-qty-minus aria-label="Менше">
                        <span class="material-symbols-outlined text-[18px]">remove</span>
                    </button>
                    <input type="number" id="tp-qty" value="1" min="1" max="99"
                           class="tabular w-10 border-0 bg-transparent text-center text-body-md font-semibold focus:outline-none" />
                    <button type="button" class="grid h-11 w-10 place-items-center text-slate-500 hover:text-blue-600" data-qty-plus aria-label="Більше">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </div>
                <p class="text-body-sm text-on-surface-variant">{{ $p->stock_text }}</p>
            </div>

            <form action="{{ route('cart.add') }}" method="post" class="flex flex-wrap gap-space-sm">
                @csrf
                <input type="hidden" name="product" value="{{ $p->slug }}" />
                <input type="hidden" name="qty" value="1" id="tp-qty-input" />
                <input type="hidden" name="redirect_cart" value="1" />
                <button type="submit" class="btn-primary flex flex-1 items-center justify-center gap-2 px-space-xl py-3.5 text-headline-sm">
                    <span class="material-symbols-outlined text-[20px]">shopping_cart</span> Купити
                </button>
                <button type="submit" name="qty" value="1" class="btn-secondary flex items-center justify-center px-space-lg py-3.5" aria-label="В кошик">
                    <span class="material-symbols-outlined text-[20px]">favorite</span>
                </button>
                <button type="submit" name="qty" value="1" class="btn-outline flex items-center justify-center gap-1 px-space-lg py-3.5 text-body-sm font-semibold">
                    <span class="material-symbols-outlined text-[18px]">bolt</span> В 1 клік
                </button>
            </form>
            <p class="-mt-2 flex items-center gap-2 text-body-sm text-on-surface-variant">
                <span class="rounded-md bg-blue-50 px-space-sm py-0.5 font-semibold text-blue-700">0% розстрочка</span>
                Оплата частинами до 24 платежів без переплат
            </p>

            {{-- Доставка --}}
            <div class="card divide-y divide-outline-variant">
                <div class="flex items-center justify-between px-space-md py-space-sm">
                    <span class="flex items-center gap-2 text-body-md">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">storefront</span>
                        Самовивіз із магазину Технопростір
                    </span>
                    <b class="text-body-sm text-emerald-600">Безкоштовно</b>
                </div>
                <div class="flex items-center justify-between px-space-md py-space-sm">
                    <span class="flex items-center gap-2 text-body-md">
                        <span class="material-symbols-outlined text-[18px] text-blue-600">local_shipping</span>
                        Доставка Новою Поштою
                    </span>
                    <b class="text-body-sm">від 80 ₴</b>
                </div>
                <div class="flex items-center justify-between px-space-md py-space-sm">
                    <span class="flex items-center gap-2 text-body-md">
                        <span class="material-symbols-outlined text-[18px] text-blue-600">electric_moped</span>
                        Експрес-кур'єр по Києву
                    </span>
                    <b class="text-body-sm">199 ₴</b>
                </div>
            </div>

            <div class="flex flex-wrap gap-space-md text-body-sm text-on-surface-variant">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-emerald-600">verified_user</span> 100% оригінальний товар</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-emerald-600">assignment_return</span> 14 днів на повернення</span>
            </div>
        </div>
    </div>

    {{-- Таби --}}
    <div class="mt-space-xl grid gap-space-xl lg:grid-cols-[1fr_320px]">
        <div class="min-w-0">
            <div class="flex gap-space-xs overflow-x-auto border-b border-outline-variant" data-tabs>
                <button type="button" data-tab="specs" class="whitespace-nowrap border-b-2 border-blue-600 px-space-md py-space-sm text-body-md font-semibold text-blue-600">Характеристики</button>
                <button type="button" data-tab="desc" class="whitespace-nowrap border-b-2 border-transparent px-space-md py-space-sm text-body-md font-semibold text-on-surface-variant hover:text-blue-600">Опис та огляд</button>
                <button type="button" data-tab="reviews" id="reviews" class="whitespace-nowrap border-b-2 border-transparent px-space-md py-space-sm text-body-md font-semibold text-on-surface-variant hover:text-blue-600">Відгуки {{ $p->reviews_count }}</button>
                <button type="button" data-tab="qa" class="whitespace-nowrap border-b-2 border-transparent px-space-md py-space-sm text-body-md font-semibold text-on-surface-variant hover:text-blue-600">Питання та відповіді</button>
            </div>

            {{-- Характеристики --}}
            <div data-panel="specs" class="mt-space-md flex flex-col gap-space-lg">
                @foreach ($fullSpecs as $group)
                    <div class="card overflow-hidden">
                        <h3 class="border-b border-outline-variant bg-slate-50 px-space-md py-space-sm text-caption font-bold uppercase tracking-wide text-slate-600">
                            {{ $group['group'] }}
                        </h3>
                        <table class="w-full text-body-sm">
                            <tbody>
                                @foreach ($group['rows'] as $i => $row)
                                    @php($pair = spec_pair($row))
                                    <tr class="{{ $i % 2 ? 'bg-slate-50/60' : 'bg-white' }}">
                                        <td class="w-2/5 px-space-md py-2 text-slate-500">{{ $pair[0] }}</td>
                                        <td class="px-space-md py-2 font-medium text-on-surface">{{ $pair[1] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- Опис --}}
            <div data-panel="desc" class="mt-space-md hidden flex-col gap-space-md">
                <div class="card p-space-md">
                    <p class="text-body-md leading-relaxed text-on-surface-variant">{{ $p->description }}</p>
                    <p class="mt-space-sm text-body-md leading-relaxed text-on-surface-variant">
                        Купуйте {{ $p->title }} з офіційною гарантією виробника, доставкою Новою Поштою по всій Україні
                        та оплатою частинами 0%. Самовивіз із 48 магазинів «ТЕХНОПРОСТІР» — Київ, Львів, Дніпро, Одеса, Харків
                        та інші міста.
                    </p>
                </div>
            </div>

            {{-- Відгуки --}}
            <div data-panel="reviews" class="mt-space-md hidden flex-col gap-space-md">
                @foreach ([
                    ['Олексій Мельник', 5, 'Сьогодні, 14:45', 'Швидка доставка, все запаковано ідеально. Товар відповідає опису, працює бездоганно. Дякую за консультацію!'],
                    ['Ірина Коваль', 5, '18 лютого 2026', 'Купувала в магазині на Хрещатику — менеджер допоміг з вибором комплектації. Ціна виявилась найкращою в місті.'],
                    ['Андрій Бондар', 4, '12 лютого 2026', 'Все супер, єдине — кур’єр їхав довше обіцяного. За якість і сервіс ставлю чотири зірки, рекомендую.'],
                ] as [$name, $stars, $date, $text])
                    <div class="card p-space-md">
                        <div class="mb-space-sm flex items-center justify-between">
                            <div class="flex items-center gap-space-sm">
                                <span class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 font-bold text-blue-700">{{ mb_substr($name, 0, 1) }}</span>
                                <div>
                                    <p class="text-body-md font-semibold">{{ $name }}</p>
                                    <p class="text-caption text-outline">{{ $date }}</p>
                                </div>
                            </div>
                            <span class="text-amber-500">{{ str_repeat('★', $stars).str_repeat('☆', 5 - $stars) }}</span>
                        </div>
                        <p class="text-body-md text-on-surface-variant">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Питання --}}
            <div data-panel="qa" class="mt-space-md hidden flex-col gap-space-sm">
                @foreach ([
                    ['Чи оригінальний товар і яка гарантія?', 'Так, товар офіційний, з гарантійним талоном виробника на 12–24 місяці залежно від категорії.'],
                    ['Як швидко доставлять замовлення?', 'Відправляємо в день замовлення до 18:00. По Україні — 1–2 дні, по Києву кур’єр може привезти сьогодні ж.'],
                    ['Чи можна оплатити частинами?', 'Так, доступне розтермінування 0% до 24 платежів (ПриватБанк, monobank, А-Банк) та оплата при отриманні.'],
                ] as [$qText, $aText])
                    <details class="card group p-space-md">
                        <summary class="flex cursor-pointer items-center justify-between text-body-md font-semibold">
                            {{ $qText }}
                            <span class="material-symbols-outlined text-[18px] text-slate-400 group-open:rotate-180">expand_more</span>
                        </summary>
                        <p class="mt-space-sm text-body-md text-on-surface-variant">{{ $aText }}</p>
                    </details>
                @endforeach
            </div>
        </div>

        {{-- Бічна колонка --}}
        <aside class="flex flex-col gap-space-md">
            <div class="card p-space-md">
                <p class="flex items-center gap-2 text-body-md font-bold">
                    <span class="material-symbols-outlined text-[20px] text-blue-600">shield</span>
                    Програма «ТехноЗахист Екстра»
                </p>
                <p class="mt-space-xs text-body-sm text-on-surface-variant">
                    Захист від сколів, вологи та падіння: безкоштовне відновлення екрана чи пристрою зі знижкою до 50%.
                </p>
            </div>

            <div class="card p-space-md text-body-sm text-on-surface-variant">
                <p class="mb-space-xs flex items-center gap-2 font-semibold text-on-surface">
                    <span class="material-symbols-outlined text-[18px] text-blue-600">public</span> Оплата та доставка
                </p>
                Visa · Mastercard · Apple Pay · Google Pay · Приват24 · mono<br />
                Нова Пошта · Укрпошта · Meest · кур'єр по Києву
            </div>
        </aside>
    </div>

    {{-- Схожі товари --}}
    @if ($related->isNotEmpty())
        <section class="mt-space-2xl">
            <h2 class="mb-space-md flex items-center gap-3 text-headline-lg tracking-tight">
                <span class="h-6 w-1.5 rounded-full bg-blue-600"></span>Схожі товари
            </h2>
            <div class="grid grid-cols-1 gap-space-md sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($related as $product)
                    @include('partials.product-card', ['product' => $product, 'variant' => 'home'])
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
    // Таби
    const tabBtns = document.querySelectorAll('[data-tab]');
    const panels = document.querySelectorAll('[data-panel]');
    tabBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-tab');
            tabBtns.forEach((b) => {
                const active = b === btn;
                b.classList.toggle('border-blue-600', active);
                b.classList.toggle('text-blue-600', active);
                b.classList.toggle('border-transparent', !active);
                b.classList.toggle('text-on-surface-variant', !active);
            });
            panels.forEach((pnl) => {
                const active = pnl.getAttribute('data-panel') === id;
                pnl.classList.toggle('hidden', !active);
                if (active) pnl.classList.add('flex');
            });
        });
    });

    // Колір / пам'ять / кількість
    document.querySelectorAll('[data-color]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-color]').forEach((b) => b.classList.remove('border-blue-600', 'ring-2', 'ring-blue-200'));
            btn.classList.add('border-blue-600', 'ring-2', 'ring-blue-200');
            const label = btn.closest('[data-color-group]')?.previousElementSibling?.querySelector('[data-color-label] b');
            if (label) label.textContent = btn.getAttribute('data-color') ?? '';
        });
    });
    document.querySelectorAll('[data-mem]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-mem]').forEach((b) => {
                b.classList.remove('border-blue-600', 'bg-blue-600', 'text-white');
                b.classList.add('border-slate-300', 'bg-white');
            });
            btn.classList.add('border-blue-600', 'bg-blue-600', 'text-white');
            btn.classList.remove('border-slate-300', 'bg-white');
        });
    });
    const qtyEl = document.getElementById('tp-qty');
    const qtyInput = document.getElementById('tp-qty-input');
    const clampQty = (v) => Math.max(1, Math.min(99, Number(v) || 1));
    document.querySelector('[data-qty-minus]')?.addEventListener('click', () => {
        qtyEl.value = clampQty(Number(qtyEl.value) - 1);
        qtyInput.value = qtyEl.value;
    });
    document.querySelector('[data-qty-plus]')?.addEventListener('click', () => {
        qtyEl.value = clampQty(Number(qtyEl.value) + 1);
        qtyInput.value = qtyEl.value;
    });
    qtyEl?.addEventListener('input', () => { qtyInput.value = clampQty(qtyEl.value); });
</script>
@endpush
@endsection
