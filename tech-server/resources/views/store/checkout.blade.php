@extends('layouts.store')

@section('title')Оформлення замовлення — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="shell py-space-md">
    {{-- Верхній рядок --}}
    <div class="mb-space-sm flex flex-wrap items-center justify-between gap-space-sm">
        <nav class="flex items-center gap-1 text-body-sm text-on-surface-variant">
            <a href="{{ route('home') }}" class="hover:text-primary">Головна</a>
            <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
            <a href="{{ route('cart') }}" class="hover:text-primary">Кошик</a>
            <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
            <span class="font-medium text-on-surface">Оформлення замовлення</span>
        </nav>
        <p class="flex items-center gap-2 text-body-sm text-emerald-600">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            Дані захищено 256-бітним SSL-протоколом
        </p>
    </div>

    <p class="mb-space-xs text-caption font-bold uppercase tracking-wide text-blue-600">Безпечний розрахунок</p>
    <h1 class="mb-space-lg text-display-hero-mobile tracking-tight lg:text-display-hero">Оформлення замовлення</h1>

    <form action="{{ route('checkout.store') }}" method="post" class="grid items-start gap-space-xl lg:grid-cols-[1fr_380px]">
        @csrf

        {{-- ============ ЛІВА КОЛОНКА ============ --}}
        <div class="flex min-w-0 flex-col gap-space-xl">
            {{-- 1. Контактні дані --}}
            <section class="card border-l-4 border-blue-600 p-space-md">
                <h2 class="mb-space-md flex items-center gap-space-sm text-headline-md">
                    <span class="grid h-7 w-7 place-items-center rounded-md bg-blue-600 text-body-sm font-bold text-white">1</span>
                    Контактні дані покупця
                </h2>

                <div class="grid gap-space-md sm:grid-cols-2">
                    <label class="flex flex-col gap-1 text-body-sm font-semibold">
                        Ім'я та Прізвище *
                        <input type="text" name="name" required value="{{ old('name') }}" class="input py-2.5 text-body-md" autocomplete="name" />
                        @error('name')<span class="text-caption text-red-600">{{ $message }}</span>@enderror
                    </label>
                    <label class="flex flex-col gap-1 text-body-sm font-semibold">
                        Номер телефону *
                        <input type="tel" name="phone" required value="{{ old('phone') }}" class="input py-2.5 text-body-md" autocomplete="tel" placeholder="+380 __ ___ __ __" />
                        @error('phone')<span class="text-caption text-red-600">{{ $message }}</span>@enderror
                    </label>
                    <label class="flex flex-col gap-1 text-body-sm font-semibold sm:col-span-2">
                        Електронна пошта
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" class="input py-2.5 text-body-md" autocomplete="email" />
                        @error('email')<span class="text-caption text-red-600">{{ $message }}</span>@enderror
                    </label>
                </div>
            </section>

            {{-- 2. Доставка --}}
            <section class="card border-l-4 border-blue-600 p-space-md">
                <h2 class="mb-space-md flex items-center gap-space-sm text-headline-md">
                    <span class="grid h-7 w-7 place-items-center rounded-md bg-blue-600 text-body-sm font-bold text-white">2</span>
                    Спосіб доставки
                    <span class="ml-auto rounded-full bg-emerald-100 px-space-sm py-1 text-caption font-bold text-emerald-700">Безкоштовно до 2 000 ₴</span>
                </h2>

                <div class="grid gap-space-sm sm:grid-cols-2">
                    @foreach ([
                        ['novaposhta', 'Нова пошта (відділення / поштомат)', 'Оплата завтра до 14:00 · Оплата перед отриманням', '0 ₴', true],
                        ['pickup', 'Самовивіз із магазину Технопростір', 'Київ, вул. Хрещатик, 22 · Готовий до видачі', '0 ₴', false],
                        ['courier', 'Кур’єр Нової Пошти до дверей', 'Доставка до квартири/офісу з підйомом', 'від 120 ₴', false],
                        ['ukrposhta', 'Укрпошта Експрес', 'По всій Україні у відділення', 'від 45 ₴', false],
                    ] as [$id, $t, $note, $price, $checked])
                        <label class="flex cursor-pointer flex-col gap-1 rounded-lg border p-space-sm transition {{ old('delivery', 'novaposhta') === $id ? 'border-blue-600 bg-blue-50/60' : 'border-outline-variant bg-white hover:border-blue-300' }}">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="delivery" value="{{ $id }}" @checked(old('delivery', 'novaposhta') === $id) class="accent-blue-600" />
                                <b class="text-body-md">{{ $t }}</b>
                                <b class="ml-auto whitespace-nowrap text-body-sm text-on-surface">{{ $price }}</b>
                            </span>
                            <span class="pl-6 text-body-sm text-on-surface-variant">{{ $note }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-space-md rounded-lg bg-surface-container-low p-space-md">
                    <p class="mb-space-sm flex items-center justify-between text-body-md font-semibold">
                        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px] text-blue-600">location_on</span> Адреса отримання</span>
                    </p>
                    <div class="grid gap-space-sm sm:grid-cols-2">
                        <label class="flex flex-col gap-1 text-body-sm">
                            Населений пункт
                            <select name="city" class="input py-2.5 text-body-md">
                                @foreach ($cities as $c)
                                    <option value="{{ $c }}" @selected(old('city', 'Київ') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="flex flex-col gap-1 text-body-sm">
                            Відділення або поштомат
                            <input type="text" name="branch" value="{{ old('branch') }}" class="input py-2.5 text-body-md" placeholder="Відділення №45 (до 30 кг): вул.…" />
                            @error('branch')<span class="text-caption text-red-600">{{ $message }}</span>@enderror
                        </label>
                    </div>
                </div>
            </section>

            {{-- 3. Оплата --}}
            <section class="card border-l-4 border-blue-600 p-space-md">
                <h2 class="mb-space-md flex items-center gap-space-sm text-headline-md">
                    <span class="grid h-7 w-7 place-items-center rounded-md bg-blue-600 text-body-sm font-bold text-white">3</span>
                    Спосіб оплати
                    <span class="ml-auto flex items-center gap-1 text-caption font-normal text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px]">lock</span> Безпечно
                    </span>
                </h2>

                <div class="flex flex-col gap-space-sm">
                    @foreach ([
                        ['card', 'Оплата онлайн карткою або гаманцем', 'Apple Pay, Google Pay, Visa, Mastercard · Швидка оплата в один клік через біометрію', '0%', true],
                        ['installments', 'Оплата частинами 0%', 'monobank, ПриватБанк, А-Банк · до 24 платежів', '0% розстрочка', false],
                        ['cod', 'Оплата при отриманні (післяплата)', 'Тільки карткою або переказом у відділенні', '2% + 20 ₴', false],
                        ['invoice', 'Безготівковий розрахунок (для юросіб)', 'Рахунок-фактура та повний пакет бухгалтерських документів', 'з ПДВ 20%', false],
                    ] as [$id, $t, $note, $badge, $checked])
                        <label class="flex cursor-pointer flex-col gap-1 rounded-lg border p-space-sm transition {{ old('payment', 'card') === $id ? 'border-blue-600 bg-blue-50/60' : 'border-outline-variant bg-white hover:border-blue-300' }}">
                            <span class="flex items-center gap-2">
                                <input type="radio" name="payment" value="{{ $id }}" @checked(old('payment', 'card') === $id) class="accent-blue-600" />
                                <b class="text-body-md">{{ $t }}</b>
                                <b class="ml-auto whitespace-nowrap rounded-md bg-blue-50 px-space-sm py-0.5 text-caption font-bold text-blue-700">{{ $badge }}</b>
                            </span>
                            <span class="pl-6 text-body-sm text-on-surface-variant">{{ $note }}</span>
                        </label>
                    @endforeach
                </div>
            </section>

            {{-- 4. Побажання --}}
            <section class="card border-l-4 border-blue-600 p-space-md">
                <h2 class="mb-space-md flex items-center gap-space-sm text-headline-md">
                    <span class="grid h-7 w-7 place-items-center rounded-md bg-blue-600 text-body-sm font-bold text-white">4</span>
                    Додаткові побажання
                </h2>

                <label class="mt-space-md flex flex-col gap-1 text-body-sm">
                    Коментар до замовлення (необов'язково)
                    <textarea name="comment" rows="3" class="input py-2.5 text-body-md" placeholder="Наприклад: зателефонувати перед відправкою, або специфічний код домофона…">{{ old('comment') }}</textarea>
                </label>

                <div class="mt-space-md grid gap-space-sm sm:grid-cols-3">
                    @foreach ([
                        ['verified', '100% Оригінальна', 'Офіційна техніка'],
                        ['autorenew', 'Обмін / повернення', '14 днів за законом'],
                        ['support_agent', 'Підтримка 24/7', '0 800 300 000'],
                    ] as [$icon, $t, $s])
                        <div class="flex items-center gap-2 rounded-lg bg-surface-container-low px-space-sm py-2">
                            <span class="material-symbols-outlined text-[20px] text-emerald-600">{{ $icon }}</span>
                            <div class="leading-tight">
                                <p class="text-body-sm font-semibold">{{ $t }}</p>
                                <p class="text-caption text-outline">{{ $s }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- ============ ПРАВА КОЛОНКА ============ --}}
        <aside class="flex flex-col gap-space-md lg:sticky lg:top-44">
            <div class="card p-space-md">
                <div class="mb-space-md flex items-center justify-between">
                    <h2 class="text-headline-md">Ваше замовлення</h2>
                    <span class="badge bg-blue-50 text-blue-700">{{ $totals['count'] }}&nbsp;{{ $totals['count'] === 1 ? 'товар' : 'товарів' }}</span>
                </div>

                <div class="mb-space-md flex flex-col gap-space-sm border-b border-outline-variant pb-space-md">
                    @forelse ($items as $item)
                        <div class="flex items-center gap-space-sm">
                            <span class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-md bg-[#f8fafc]">
                                @php($img = collect($item['product']->images ?? [])->first())
                                @if ($img)
                                    <img src="{{ $img }}" alt="" class="h-full w-full object-cover" />
                                @else
                                    <span class="text-xl">{{ $item['product']->emoji }}</span>
                                @endif
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="line-clamp-1 text-body-sm font-semibold">{{ $item['product']->title }}</span>
                                <span class="text-caption text-outline">{{ $item['qty'] }} шт.</span>
                            </span>
                            <span class="tabular whitespace-nowrap text-body-sm font-bold">{{ format_price($item['product']->price * $item['qty']) }}</span>
                        </div>
                    @empty
                        <p class="text-body-sm text-on-surface-variant">Кошик порожній.</p>
                    @endforelse
                </div>

                <dl class="flex flex-col gap-space-sm border-t border-outline-variant pt-space-md text-body-md">
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant">Вартість товарів</dt>
                        <dd class="tabular font-semibold">{{ format_price($totals['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between {{ $totals['promoDiscount'] === 0 ? 'hidden' : '' }}">
                        <dt class="text-on-surface-variant">Знижка за промокодом {{ $totals['promoCode']?->code }}</dt>
                        <dd class="tabular font-semibold text-emerald-600">-{{ format_price($totals['promoDiscount']) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant">Доставка Новою Поштою</dt>
                        <dd class="font-semibold text-emerald-600">0 ₴</dd>
                    </div>
                </dl>

                <div class="mt-space-md flex items-baseline justify-between border-t border-outline-variant pt-space-md">
                    <span class="text-body-md font-bold">Разом:</span>
                    <span class="tabular text-price-display-lg text-blue-700">{{ format_price($totals['total']) }}</span>
                </div>
                <p class="mt-1 text-right text-caption text-outline">з урахуванням ПДВ 20%</p>

                <button type="submit" class="btn-primary mt-space-md flex w-full items-center justify-center gap-2 py-3.5 text-headline-sm">
                    <span class="material-symbols-outlined text-[20px]">lock</span>
                    Підтвердити замовлення
                </button>
                <p class="mt-space-xs text-center text-caption text-outline">
                    Натискаючи кнопку, ви погоджуєтесь з умовами публічної оферти та обробкою персональних даних
                </p>

                <div class="mt-space-md rounded-lg bg-emerald-50 p-space-sm text-body-sm text-emerald-800">
                    <p class="flex items-center gap-2 font-semibold">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        Сертифікований платіжний шлюз
                    </p>
                    <p class="mt-1 text-emerald-700">
                        Безпечна оплата з протоколом PCI DSS Level 1, 3D Secure 2.0 та офіційна гарантія виробника 12 місяців.
                    </p>
                </div>
            </div>
        </aside>
    </form>
</div>
@endsection
