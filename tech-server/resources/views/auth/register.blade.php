@extends('layouts.store')

@section('title')Реєстрація — ТЕХНОПРОСТІР@endsection

@section('content')
<div class="mx-auto flex w-full max-w-md flex-col gap-space-lg py-space-2xl">
    <div class="text-center">
        <h1 class="text-display-sm font-bold tracking-tight">Створення облікового запису</h1>
        <p class="mt-space-xs text-body-md text-on-surface-variant">Швидше оформлюйте замовлення та бачте їх історію</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="card flex flex-col gap-space-md p-space-xl">
        @csrf

        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 px-space-md py-space-sm text-body-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col gap-space-xs">
            <label for="name" class="text-body-sm font-semibold">Ім’я та Прізвище</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   autocomplete="name" class="input py-2.5 text-body-md" placeholder="Олексій Мельник" />
        </div>

        <div class="flex flex-col gap-space-xs">
            <label for="email" class="text-body-sm font-semibold">Електронна пошта</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   autocomplete="email" class="input py-2.5 text-body-md" placeholder="you@example.ua" />
        </div>

        <div class="flex flex-col gap-space-xs">
            <label for="password" class="text-body-sm font-semibold">Пароль</label>
            <input id="password" name="password" type="password" required
                   autocomplete="new-password" class="input py-2.5 text-body-md" placeholder="Мінімум 8 символів" />
        </div>

        <div class="flex flex-col gap-space-xs">
            <label for="password_confirmation" class="text-body-sm font-semibold">Підтвердження паролю</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   autocomplete="new-password" class="input py-2.5 text-body-md" placeholder="Повторіть пароль" />
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-body-md font-semibold">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Зареєструватися
        </button>

        <p class="text-center text-body-sm text-on-surface-variant">
            Вже маєте обліковий запис?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Увійти</a>
        </p>
    </form>

    <a href="{{ route('home') }}" class="text-center text-body-sm text-on-surface-variant hover:text-blue-600">
        ← Повернутися на головну
    </a>
</div>
@endsection
