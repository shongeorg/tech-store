@extends('layouts.admin')

@section('title')Товари — Адмінка@endsection

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <form class="flex gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Пошук за назвою / артикулом / брендом…" class="input max-w-sm py-2 text-body-sm" />
        <button type="submit" class="btn-primary px-4 py-2 text-body-sm">Знайти</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-primary flex items-center gap-2 px-4 py-2 text-body-sm">
        <span class="material-symbols-outlined text-[18px]">add</span> Додати товар
    </a>
</div>

<div class="card overflow-x-auto">
    <table class="w-full min-w-4xl text-body-sm">
        <thead class="bg-slate-50 text-caption text-slate-500">
            <tr>
                <th class="px-3 py-2 text-left">ID</th>
                <th class="px-3 py-2 text-left">Фото</th>
                <th class="px-3 py-2 text-left">Назва</th>
                <th class="px-3 py-2 text-left">Категорія</th>
                <th class="px-3 py-2 text-right">Ціна</th>
                <th class="px-3 py-2 text-center">Наявність</th>
                <th class="px-3 py-2"><span class="sr-only">Дії</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $p)
                <tr class="border-t border-slate-100">
                    <td class="tabular px-3 py-2 font-semibold text-slate-500">{{ $p->id }}</td>
                    <td class="px-3 py-2">
                        <div class="grid h-10 w-10 place-items-center overflow-hidden rounded-md bg-slate-100">
                            @php($img = collect($p->images ?? [])->first())
                            @if ($img)
                                <img src="{{ $img }}" alt="" class="h-full w-full object-cover" />
                            @else
                                <span>{{ $p->emoji ?? '🧩' }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="max-w-md px-3 py-2">
                        <a href="{{ route('admin.products.edit', $p) }}" class="line-clamp-1 font-semibold text-on-surface hover:text-blue-600">{{ $p->title }}</a>
                        <span class="text-caption text-outline">{{ $p->brand }} · {{ $p->sku }}</span>
                        @if (! $p->is_active)
                            <span class="badge badge-action ml-2">Вимкнено</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-on-surface-variant">{{ $p->category?->name }}</td>
                    <td class="tabular px-3 py-2 text-right font-bold">{{ format_price($p->price) }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="badge {{ $p->stock === 'in' ? 'badge-instock' : 'badge-muted' }}">{{ $p->stock === 'in' ? 'в наявності' : 'передзамовлення' }}</span>
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('product', $p->slug) }}" class="grid h-8 w-8 place-items-center rounded-md hover:bg-slate-100" title="В магазині">
                                <span class="material-symbols-outlined text-[18px] text-slate-500">visibility</span>
                            </a>
                            <a href="{{ route('admin.products.edit', $p) }}" class="grid h-8 w-8 place-items-center rounded-md hover:bg-slate-100" title="Редагувати">
                                <span class="material-symbols-outlined text-[18px] text-blue-600">edit</span>
                            </a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="post" onsubmit="return confirm('Видалити товар «{{ $p->title }}»?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="grid h-8 w-8 place-items-center rounded-md hover:bg-slate-100" title="Видалити">
                                    <span class="material-symbols-outlined text-[18px] text-red-600">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-3 py-8 text-center text-on-surface-variant">Товарів не знайдено</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.pagination', ['paginator' => $products])
@endsection
