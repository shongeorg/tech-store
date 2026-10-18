{{-- Лейаут адмінки --}}
<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <title>@yield('title', 'Адмінка — ТЕХНОПРОСТІР')</title>
    @vite(['resources/css/store.css'])
</head>
<body class="bg-slate-100">
    <div class="flex min-h-screen">
        {{-- Сайдбар --}}
        <aside class="hidden w-60 shrink-0 flex-col bg-slate-900 p-4 text-slate-300 lg:flex">
            <a href="{{ route('home') }}" class="mb-6 flex items-center gap-2">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-blue-600 text-lg font-black text-white">T</span>
                <span class="text-[15px] font-extrabold text-white">ТЕХНО<span class="text-blue-400">ПРОСТІР</span></span>
            </a>
            <nav class="flex flex-col gap-1 text-body-sm">
                @php($routeName = Route::currentRouteName())
                @foreach ([
                    ['admin.dashboard', 'dashboard', 'Дашборд'],
                    ['admin.products.index', 'inventory_2', 'Товари'],
                    ['admin.categories.index', 'category', 'Категорії'],
                    ['admin.orders.index', 'receipt_long', 'Замовлення'],
                    ['admin.promos.index', 'sell', 'Промокоди'],
                ] as [$name, $icon, $label])
                    <a href="{{ route($name) }}"
                       class="flex items-center gap-2 rounded-md px-3 py-2 {{ str_starts_with($routeName, $name) ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>{{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="mt-auto flex flex-col gap-1 text-caption">
                <a href="{{ route('home') }}" class="rounded-md px-3 py-2 hover:bg-slate-800 hover:text-white">← На магазин</a>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="w-full rounded-md px-3 py-2 text-left hover:bg-slate-800 hover:text-white">Вийти</button>
                </form>
            </div>
        </aside>

        {{-- Контент --}}
        <div class="min-w-0 flex-1">
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 lg:px-8">
                <h1 class="text-headline-md">@yield('title', 'Адмінка')</h1>
                <div class="flex items-center gap-3 text-body-sm">
                    <span class="text-on-surface-variant">{{ auth()->user()?->name }}</span>
                    <span class="badge bg-blue-50 text-blue-700">admin</span>
                </div>
            </header>
            <main class="p-4 lg:p-8">
                @if (session('toast'))
                    <div class="mb-4 flex items-center justify-between rounded-lg bg-emerald-50 px-4 py-2.5 text-body-sm font-semibold text-emerald-800">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('toast') }}
                        </span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 px-4 py-2.5 text-body-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
