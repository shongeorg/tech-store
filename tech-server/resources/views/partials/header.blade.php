{{-- Хедер магазину (дизайн: Header.astro). $cartCount / $cartSubtotal приходять з view composer --}}
@php($path = request()->path())
@php($route = Route::currentRouteName())
@php($categories = \App\Models\Category::where('is_active', true)->orderBy('sort')->get())
@php($user = auth()->user())

{{-- Верхній утилітарний рядок (лише desktop) --}}
<div class="hidden border-b border-outline-variant bg-surface-container-lowest lg:block">
    <div class="shell flex h-9 items-center justify-between text-caption text-on-surface-variant">
        <div class="flex items-center gap-space-lg">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px] text-primary">location_on</span>Київ
            </span>
            <a href="#" class="hover:text-primary">Магазини та точки видачі (48)</a>
        </div>
        <nav class="flex items-center gap-space-lg">
            <a href="#" class="hover:text-primary">Доставка</a>
            <a href="#" class="hover:text-primary">Оплата</a>
            <a href="#" class="hover:text-primary">Гарантія та повернення</a>
            <a href="#" class="hover:text-primary">Кредит і розтермінування 0%</a>
            <a href="#" class="hover:text-primary">Контакти</a>
            <a href="#" class="hover:text-primary">Корпоративним клієнтам (B2B)</a>
        </nav>
        <div class="flex items-center gap-space-lg">
            <a href="tel:0800300777" class="flex items-center gap-1 font-semibold text-on-surface hover:text-primary">
                <span class="material-symbols-outlined text-[14px] text-primary">phone_in_talk</span>
                0 800 300 777
                <span class="font-normal text-outline">08:00 – 21:00, безкоштовно</span>
            </a>
            <span class="flex items-center gap-1">
                <span class="rounded-sm bg-primary px-1.5 py-0.5 font-semibold text-white">UA</span>
                <span class="cursor-pointer hover:text-primary">EN</span>
            </span>
        </div>
    </div>
</div>

{{-- Основний рядок хедера --}}
<header class="sticky top-0 z-40 border-b border-outline-variant bg-surface-container-lowest/95 backdrop-blur">
    <div class="shell flex h-14 items-center gap-space-lg lg:h-16">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2" aria-label="ТЕХНОПРОСТІР — на головну">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-blue-600 text-lg font-black text-white lg:h-10 lg:w-10">T</span>
            <span class="hidden flex-col leading-none sm:flex">
                <span class="text-[17px] font-extrabold tracking-tight text-on-surface lg:text-xl">
                    ТЕХНО<span class="text-blue-600">ПРОСТІР</span>
                </span>
                <span class="text-[9px] font-semibold tracking-[0.08em] text-outline">ОМНІКАНАЛЬНИЙ РИТЕЙЛ</span>
            </span>
        </a>

        <a href="{{ route('catalog') }}" class="btn-primary hidden items-center gap-2 px-4 py-2.5 text-body-md lg:flex">
            <span class="material-symbols-outlined text-[18px]">menu</span>
            Каталог товарів
        </a>

        {{-- Пошук (desktop) --}}
        <form action="{{ route('search') }}" method="get" class="hidden min-w-0 flex-1 items-center gap-2 md:flex">
            <select name="cat" aria-label="Категорія" class="h-11 shrink-0 rounded-l-lg border border-slate-300 bg-slate-50 px-3 text-body-sm text-slate-600 focus:outline-none">
                <option value="">Всі категорії</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                @endforeach
            </select>
            <div class="relative min-w-0 flex-1">
                <input type="search" name="q" placeholder="Пошук серед 150 000+ товарів…" class="input h-11 rounded-l-none border-l-0 py-2.5 pr-10 text-body-md" />
            </div>
            <button type="submit" class="grid h-11 w-11 shrink-0 place-items-center rounded-r-lg bg-blue-600 text-white hover:bg-blue-700" aria-label="Знайти">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </button>
        </form>

        {{-- Дії (desktop) --}}
        <div class="ml-auto hidden items-center gap-space-lg lg:flex">
            <a href="{{ route('catalog') }}" class="relative flex flex-col items-center text-[11px] text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[22px]">compare_arrows</span>
                Порівняння
            </a>
            <a href="{{ route('profile') }}" class="relative flex flex-col items-center text-[11px] text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[22px]">favorite</span>
                Обране
            </a>
            <a href="{{ route('cart') }}" class="relative flex flex-col items-center text-[11px] text-on-surface-variant hover:text-primary">
                <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                Кошик
                <span class="absolute -top-1 left-4 grid h-4 min-w-4 place-items-center rounded-full bg-orange-500 px-1 text-[9px] font-bold text-white">{{ $cartCount }}</span>
                <span class="text-[11px] font-bold text-on-surface">{{ format_price($cartSubtotal) }}</span>
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-2">
                <span class="grid h-9 w-9 place-items-center overflow-hidden rounded-full bg-slate-200">
                    <span class="material-symbols-outlined text-slate-500">person</span>
                </span>
                <span class="hidden flex-col leading-tight xl:flex">
                    <span class="text-[10px] text-outline">Особистий кабінет</span>
                    <span class="text-[13px] font-semibold text-on-surface">{{ $user?->name ?? 'Гість' }}</span>
                </span>
            </a>
            @if ($user?->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-semibold text-blue-700 hover:underline">Адмін</a>
            @endif
        </div>

        {{-- Дії (mobile) --}}
        <div class="ml-auto flex items-center gap-4 lg:hidden">
            <a href="{{ route('profile') }}" aria-label="Обране">
                <span class="material-symbols-outlined text-[24px] text-on-surface-variant">favorite</span>
            </a>
            <a href="{{ route('cart') }}" class="relative" aria-label="Кошик">
                <span class="material-symbols-outlined text-[24px] text-on-surface-variant">shopping_bag</span>
                <span class="absolute -right-1.5 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-orange-500 px-1 text-[9px] font-bold text-white">{{ $cartCount }}</span>
            </a>
            <a href="{{ route('profile') }}" aria-label="Профіль">
                <span class="grid h-8 w-8 place-items-center overflow-hidden rounded-full bg-slate-200">
                    <span class="material-symbols-outlined text-[20px] text-slate-500">person</span>
                </span>
            </a>
        </div>
    </div>

    {{-- Пошук (mobile) --}}
    <div class="shell pb-2.5 md:hidden">
        <form action="{{ route('search') }}" method="get" class="flex items-center gap-2">
            <div class="relative min-w-0 flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">search</span>
                <input type="search" name="q" placeholder="Пошук серед 150 000+ товарів…" class="input h-10 py-2 pl-10 pr-3 text-body-md" />
            </div>
            <button type="submit" class="grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-slate-300 text-on-surface-variant" aria-label="Фільтри">
                <span class="material-symbols-outlined text-[20px]">tune</span>
            </button>
        </form>
    </div>

    {{-- Смуга категорій (desktop) --}}
    <nav class="hidden border-t border-outline-variant bg-surface-container-lowest lg:block">
        <div class="shell flex h-10 items-center gap-space-xl overflow-x-auto text-body-sm">
            <a href="{{ route('catalog') }}?sort=discount" class="flex shrink-0 items-center gap-1 font-semibold text-red-600 hover:underline">
                <span class="material-symbols-outlined text-[16px]">local_fire_department</span>
                Гарячі Знижки %
            </a>
            @foreach ($categories as $c)
                <a href="{{ route('catalog.category', $c->slug) }}"
                   class="shrink-0 {{ $path === 'catalog' && str_starts_with(request()->url(), url('catalog/'.$c->slug)) ? 'font-semibold text-blue-600' : 'text-on-surface-variant' }} hover:text-primary">
                    {{ $c->nav_name ?? $c->name }}
                </a>
            @endforeach
            <a href="{{ route('home') }}#hits" class="shrink-0 text-on-surface-variant hover:text-primary">Хіти продажів</a>
            <a href="#" class="shrink-0 text-on-surface-variant hover:text-primary">Trade-in обмін</a>
        </div>
    </nav>
</header>
