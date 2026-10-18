@extends('layouts.admin')

@section('title')Промокоди — Адмінка@endsection

@section('content')
<div class="grid items-start gap-4 lg:grid-cols-[1fr_320px]">
    <div class="card overflow-x-auto">
        <table class="w-full text-body-sm">
            <thead class="bg-slate-50 text-caption text-slate-500">
                <tr>
                    <th class="px-3 py-2 text-left">Код</th>
                    <th class="px-3 py-2 text-right">Знижка</th>
                    <th class="px-3 py-2 text-center">Статус</th>
                    <th class="px-3 py-2"><span class="sr-only">Дії</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promos as $promo)
                    <tr class="border-t border-slate-100">
                        <td class="px-3 py-2 font-mono font-bold text-blue-700">{{ $promo->code }}</td>
                        <td class="tabular px-3 py-2 text-right font-bold">−{{ format_price($promo->amount) }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="badge {{ $promo->is_active ? 'bg-emerald-50 text-emerald-700' : 'badge-muted' }}">
                                {{ $promo->is_active ? 'активний' : 'вимкнено' }}
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('admin.promos.toggle', $promo) }}" method="post">
                                    @csrf
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md hover:bg-slate-100" title="{{ $promo->is_active ? 'Вимкнути' : 'Увімкнути' }}">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500">{{ $promo->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.promos.destroy', $promo) }}" method="post"
                                      onsubmit="return confirm('Видалити промокод {{ $promo->code }}?')">
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
                    <tr><td colspan="4" class="px-3 py-8 text-center text-on-surface-variant">Промокодів немає</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.promos.store') }}" method="post" class="card h-fit space-y-3 p-4">
        @csrf
        <h2 class="text-body-md font-bold">Новий промокод</h2>
        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Код *
            <input type="text" name="code" required class="input py-2 font-mono text-body-sm uppercase" placeholder="TECHNO2026" />
        </label>
        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Знижка, ₴ *
            <input type="number" name="amount" required min="1" class="input py-2 text-body-sm tabular" placeholder="500" />
        </label>
        <button type="submit" class="btn-primary w-full py-2.5 text-body-sm">Створити промокод</button>
        <p class="text-caption text-outline">Код застосовується в кошику та дає фіксовану знижку в гривні.</p>
    </form>
</div>

@include('partials.pagination', ['paginator' => $promos])
@endsection
