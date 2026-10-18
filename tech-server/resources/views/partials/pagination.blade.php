{{-- Пагінація в стилі дизайн-системи Precision Retail (через linkCollection) --}}
@if ($paginator->hasPages())
    @php($current = $paginator->currentPage())
    @php($last = $paginator->lastPage())
    <nav class="mt-space-xl flex flex-col items-center gap-space-md" aria-label="Пагінація">
        <p class="text-body-sm text-on-surface-variant">
            Сторінка <span class="tabular font-semibold">{{ $current }}</span>
            із <span class="tabular">{{ $last }}</span>
            ({{ $paginator->count() }} товарів на сторінці)
        </p>
        <div class="flex items-center gap-space-xs">
            @if ($paginator->onFirstPage())
                <span class="grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-400" aria-disabled="true">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-600 hover:border-blue-400">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            @foreach ($paginator->linkCollection() as $link)
                @if (empty($link['url']))
                    <span class="text-slate-400">…</span>
                @elseif (str_contains((string) $link['label'], 'Previous'))
                    @continue
                @elseif (str_contains((string) $link['label'], 'Next'))
                    @continue
                @elseif ($link['active'])
                    <span class="grid h-9 w-9 place-items-center rounded-md bg-blue-600 font-semibold text-white">{{ $link['label'] }}</span>
                @else
                    <a href="{{ $link['url'] }}" class="grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-600 hover:border-blue-400">{{ is_array($link['label']) ? '' : $link['label'] }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-600 hover:border-blue-400">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span class="grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-400" aria-disabled="true">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
