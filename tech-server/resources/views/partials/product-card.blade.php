{{-- Картка товару (дизайн: ProductCard.astro). variant: grid | home --}}
@php
    $variant = $variant ?? 'grid';
    $p = $product;
    $discount = $p->old_price ? max(0, round((1 - $p->price / $p->old_price) * 100)) : 0;
    $firstImage = collect($p->images ?? [])->first();
@endphp
<article
    class="group relative flex flex-col overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest transition hover:border-slate-300 hover:shadow-[0_4px_12px_-2px_rgba(15,23,42,0.08)]"
>
    {{-- Бейджі --}}
    <div class="absolute left-2 top-2 z-10 flex flex-col items-start gap-1">
        @if ($p->badge)
            <span class="badge badge-{{ $p->badge_kind ?? 'muted' }}">{{ $p->badge }}</span>
        @endif
        @if ($discount > 0)
            <span class="badge badge-action">-{{ $discount }}%</span>
        @endif
        @if ($variant === 'grid' && $p->installment)
            <span class="badge badge-muted lowercase">{{ $p->installment }}</span>
        @endif
    </div>

    {{-- Обране --}}
    <button type="button" aria-label="В обране" class="absolute right-2 top-2 z-10 grid h-8 w-8 place-items-center rounded-full border border-outline-variant bg-white/85 text-slate-500 transition hover:text-pink-500">
        <span class="material-symbols-outlined text-[18px]">favorite</span>
    </button>

    {{-- Медіа --}}
    <a href="{{ route('product', $p->slug) }}" class="block aspect-square bg-[#f8fafc] p-3" tabindex="-1" aria-hidden="true">
        @if ($firstImage)
            <img src="{{ $firstImage }}" alt="{{ $p->title }}" loading="lazy"
                 class="h-full w-full select-none rounded-md object-cover transition-transform duration-200 group-hover:scale-105" />
        @else
            <div class="grid h-full select-none place-items-center text-7xl transition-transform duration-200 group-hover:scale-105">
                {{ $p->emoji ?? '🧩' }}
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col gap-space-sm p-space-md">
        @if ($variant === 'grid')
            <div class="flex items-center justify-between text-specs-mono text-slate-400">
                <span>Артикул: {{ $p->sku }}</span>
                <span class="flex items-center gap-1 rounded-sm bg-slate-100 px-1.5 py-0.5 font-semibold text-slate-500">0-0-15</span>
            </div>
        @endif

        {{-- Рейтинг --}}
        <div class="flex items-center justify-between gap-2">
            <span class="flex items-center gap-1 text-body-sm">
                <span class="text-amber-500">★</span>
                <span class="font-semibold">{{ number_format($p->rating, 1) }}</span>
                <span class="text-outline">({{ $p->reviews_count }} відгуків)</span>
            </span>
            @if ($p->badge === 'НОВИНКА')
                <span class="badge badge-new">NEW 2026</span>
            @endif
        </div>

        <a href="{{ route('product', $p->slug) }}" class="line-clamp-2 min-h-10 text-body-md font-semibold leading-5 text-on-surface hover:text-blue-600">
            {{ $p->title }}
        </a>

        @if ($variant === 'grid' && $p->specs)
            <dl class="hidden flex-col gap-1 text-body-sm text-on-surface-variant md:flex">
                @php($specs = array_slice($p->specs, 0, 3))
                @foreach ($specs as $row)
                    @php($pair = spec_pair($row))
                    @continue($pair[0] === '')
                    <div class="flex justify-between gap-2">
                        <dt class="text-slate-500">{{ $pair[0] }}:</dt>
                        <dd class="truncate font-medium text-on-surface">{{ $pair[1] }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <p class="flex items-start gap-1 text-[12px] leading-4 text-tertiary">
            <span class="material-symbols-outlined mt-px text-[14px]">local_shipping</span>
            {{ $p->stock_text }}
        </p>

        {{-- Ціна --}}
        <div class="mt-auto flex flex-wrap items-baseline gap-x-2 gap-y-1 pt-1">
            <span class="tabular text-price-display-md text-on-surface">
                {{ format_price($p->price) }}
            </span>
            @if ($p->old_price)
                <span class="tabular text-specs-mono text-slate-400 line-through">{{ format_price($p->old_price) }}</span>
                <span class="badge badge-action">-{{ $discount }}%</span>
            @endif
        </div>

        {{-- Дії: форма додає товар у сесійний кошик --}}
        <div class="flex items-center gap-space-sm">
            <button type="submit"
                    form="cart-add-{{ $p->id }}"
                    class="flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-blue-600 py-2.5 font-semibold text-white transition hover:bg-blue-700 active:bg-blue-800 {{ $variant === 'grid' ? 'text-headline-sm' : 'text-body-md' }}">
                <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                {{ $variant === 'grid' ? 'В кошик' : 'Купити' }}
            </button>
            @if ($variant === 'grid')
                <a href="{{ route('product', $p->slug) }}" class="btn-secondary whitespace-nowrap px-3 py-2.5 text-body-sm font-semibold">
                    В 1 клік
                </a>
            @endif
        </div>
        <form id="cart-add-{{ $p->id }}" action="{{ route('cart.add') }}" method="post" class="hidden">
            @csrf
            <input type="hidden" name="product" value="{{ $p->slug }}" />
            <input type="hidden" name="qty" value="1" />
        </form>
    </div>
</article>
