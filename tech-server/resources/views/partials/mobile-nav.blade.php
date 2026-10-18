{{-- Мобільна нижня навігація (дизайн: MobileNav.astro) --}}
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-outline-variant bg-surface-container-lowest lg:hidden" aria-label="Мобільна навігація">
    <div class="grid grid-cols-5">
        @php($navItems = [
            ['route' => 'home', 'icon' => 'home', 'label' => 'Головна'],
            ['route' => 'catalog', 'icon' => 'grid_view', 'label' => 'Каталог'],
            ['route' => 'profile', 'icon' => 'favorite', 'label' => 'Обране'],
            ['route' => 'cart', 'icon' => 'shopping_bag', 'label' => '', 'cart' => true],
            ['route' => 'profile', 'icon' => 'person', 'label' => 'Профіль'],
        ])
        @foreach ($navItems as $it)
            <a href="{{ route($it['route']) }}"
               class="relative flex flex-col items-center gap-0.5 py-2 text-[10px] {{ Route::currentRouteName() === $it['route'] ? 'text-blue-600' : 'text-on-surface-variant' }}">
                <span class="relative">
                    <span class="material-symbols-outlined text-[22px]">{{ $it['icon'] }}</span>
                    @if (! empty($it['cart']))
                        <span class="absolute -right-2 -top-1 grid h-4 min-w-4 place-items-center rounded-full bg-orange-500 px-1 text-[9px] font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </span>
                @if (! empty($it['cart']))
                    <span class="text-[10px] font-bold">{{ format_price($cartSubtotal) }}</span>
                @else
                    <span>{{ $it['label'] }}</span>
                @endif
            </a>
        @endforeach
    </div>
</nav>
