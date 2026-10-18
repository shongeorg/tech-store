@extends('layouts.store')

@section('title')Вхід — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="mx-auto flex w-full max-w-md flex-col gap-space-lg py-space-2xl">
    <div class="text-center">
        <h1 class="text-display-sm font-bold tracking-tight">Вхід до облікового запису</h1>
        <p class="mt-space-xs text-body-md text-on-surface-variant">Керуйте замовленнями та списком бажань</p>
    </div>

    @if (session('status'))
        <div class="rounded-md border border-emerald-200 bg-emerald-50 px-space-md py-space-sm text-body-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="card flex flex-col gap-space-md p-space-xl">
        @csrf

        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 px-space-md py-space-sm text-body-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col gap-space-xs">
            <label for="email" class="text-body-sm font-semibold">Електронна пошта</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username" class="input py-2.5 text-body-md" placeholder="you@example.ua" />
        </div>

        <div class="flex flex-col gap-space-xs">
            <label for="password" class="text-body-sm font-semibold">Пароль</label>
            <input id="password" name="password" type="password" required
                   autocomplete="current-password" class="input py-2.5 text-body-md" placeholder="••••••••" />
            @if ($canResetPassword ?? false)
                <a href="{{ route('password.request') }}" class="text-body-sm text-blue-600 hover:underline">Забули пароль?</a>
            @endif
        </div>

        <label class="flex items-center gap-space-xs text-body-sm">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600" />
            Запам’ятати мене
        </label>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-body-md font-semibold">
            <span class="material-symbols-outlined text-[18px]">login</span>
            Увійти
        </button>

        <p class="text-center text-body-sm text-on-surface-variant">
            Немає облікового запису?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Створити</a>
        </p>
    </form>

    <a href="{{ route('home') }}" class="text-center text-body-sm text-on-surface-variant hover:text-blue-600">
        ← Повернутися на головну
    </a>
</div>
@endsection
