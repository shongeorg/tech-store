{{-- Головний лейаут магазину (дизайн: BaseLayout.astro) --}}
<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <meta name="theme-color" content="#2563EB" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <title>@yield('title', 'ТЕХНОПРОСТІР — Інтернет-магазин електроніки та побутової техніки в Україні')</title>
    <meta name="description" content="@yield('description', 'Купуйте електроніку та побутову техніку в Україні з доставкою. Великий вибір смартфонів, ноутбуків, побутової техніки за вигідними цінами. Гарантія якості.')" />
    @vite(['resources/css/store.css'])
</head>
<body class="pb-16 lg:pb-0">
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    @include('partials.mobile-nav')

    @if (session('toast'))
        <div id="tp-toast" class="fixed bottom-20 left-1/2 z-[70] -translate-x-1/2 rounded-lg bg-slate-900 px-4 py-2.5 text-body-sm font-semibold text-white shadow-lg lg:bottom-6">
            {{ session('toast') }}
        </div>
        <script>
            setTimeout(() => document.getElementById('tp-toast')?.remove(), 3500);
        </script>
    @endif
    @stack('scripts')
</body>
</html>
