@extends('layouts.admin')

@section('title')Категорії — Адмінка@endsection

@section('content')
<div class="grid gap-4 lg:grid-cols-[1fr_320px]">
    <div class="card overflow-x-auto">
        <table class="w-full text-body-sm">
            <thead class="bg-slate-50 text-caption text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Категорія</th>
                    <th class="px-3 py-2 text-left">Slug</th>
                    <th class="px-3 py-2 text-right">Товарів</th>
                    <th class="px-3 py-2 text-right">Сорт.</th>
                    <th class="px-3 py-2"><span class="sr-only">Дії</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $c)
                    <tr class="border-t border-slate-100">
                        <td class="tabular px-3 py-2 text-slate-500">{{ $c->id }}</td>
                        <td class="px-3 py-2 font-semibold">{{ $c->emoji }} {{ $c->name }}</td>
                        <td class="px-3 py-2 font-mono text-[11px] text-slate-500">{{ $c->slug }}</td>
                        <td class="tabular px-3 py-2 text-right">{{ $c->products_count }}</td>
                        <td class="tabular px-3 py-2 text-right text-slate-500">{{ $c->sort }}</td>
                        <td class="px-3 py-2">
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="post"
                                  onsubmit="return confirm('Видалити категорію «{{ $c->name }}»?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="grid h-8 w-8 place-items-center rounded-md hover:bg-slate-100" title="Видалити">
                                    <span class="material-symbols-outlined text-[18px] text-red-600">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="post" class="card h-fit space-y-3 p-4">
        @csrf
        <h2 class="text-body-md font-bold">Нова категорія</h2>
        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Назва *
            <input type="text" name="name" required class="input py-2 text-body-sm" placeholder="Смартфони" />
        </label>
        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Slug (латиницею) *
            <input type="text" name="slug" required class="input py-2 text-body-sm" placeholder="smartphones" />
        </label>
        <div class="grid grid-cols-2 gap-3">
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Емодзі
                <input type="text" name="emoji" class="input py-2 text-body-sm" placeholder="📱" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Порядок
                <input type="number" name="sort" value="99" min="0" class="input py-2 text-body-sm tabular" />
            </label>
        </div>
        <button type="submit" class="btn-primary w-full py-2.5 text-body-sm">Створити категорію</button>
    </form>
</div>
@endsection
