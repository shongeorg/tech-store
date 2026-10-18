@extends('layouts.admin')

@section('title'){{ $product->exists ? 'Редагування товару' : 'Новий товар' }} — Адмінка@endsection

@section('content')
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="post" class="grid gap-4 lg:grid-cols-3" enctype="multipart/form-data">
    @csrf
    @if ($product->exists) @method('PUT') @endif

    {{-- Основні поля --}}
    <div class="card space-y-3 p-4 lg:col-span-2">
        <h2 class="text-body-md font-bold">Основне</h2>

        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Назва *
            <input type="text" name="title" required value="{{ old('title', $product->title) }}" class="input py-2 text-body-sm" />
        </label>

        <div class="grid gap-3 sm:grid-cols-3">
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Slug (URL) *
                <input type="text" name="slug" required value="{{ old('slug', $product->slug) }}" class="input py-2 text-body-sm" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Категорія *
                <select name="category_id" class="input py-2 text-body-sm">
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Бренд *
                <input type="text" name="brand" required value="{{ old('brand', $product->brand) }}" class="input py-2 text-body-sm" />
            </label>
        </div>

        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Короткий опис
            <input type="text" name="short" value="{{ old('short', $product->short) }}" class="input py-2 text-body-sm" />
        </label>

        <div class="grid gap-3 sm:grid-cols-4">
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Ціна, ₴ *
                <input type="number" name="price" required min="1" value="{{ old('price', $product->price) }}" class="input py-2 text-body-sm tabular" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Стара ціна, ₴
                <input type="number" name="old_price" min="0" value="{{ old('old_price', $product->old_price) }}" class="input py-2 text-body-sm tabular" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Рейтинг (0–5)
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $product->rating) }}" class="input py-2 text-body-sm tabular" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Відгуків
                <input type="number" name="reviews_count" min="0" value="{{ old('reviews_count', $product->reviews_count) }}" class="input py-2 text-body-sm tabular" />
            </label>
        </div>

        <div class="grid gap-3 sm:grid-cols-4">
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Артикул
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="input py-2 text-body-sm" />
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Наявність
                <select name="stock" class="input py-2 text-body-sm">
                    <option value="in" @selected(old('stock', $product->stock) === 'in')>В наявності</option>
                    <option value="order" @selected(old('stock', $product->stock) === 'order')>Передзамовлення</option>
                </select>
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Бейдж
                <select name="badge" class="input py-2 text-body-sm">
                    <option value="">— немає —</option>
                    @foreach (['ХІТ' => 'hit', 'НОВИНКА' => 'new', 'АКЦІЯ' => 'action'] as $label => $kind)
                        <option value="{{ $label }}" @selected(old('badge', $product->badge) === $label)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Емодзі (запасний)
                <input type="text" name="emoji" value="{{ old('emoji', $product->emoji) }}" class="input py-2 text-body-sm" />
            </label>
        </div>

        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Рядок наявності (під назвою)
            <input type="text" name="stock_text" value="{{ old('stock_text', $product->stock_text) }}" class="input py-2 text-body-sm" />
        </label>

        <label class="flex flex-col gap-1 text-body-sm font-semibold">
            Опис
            <textarea name="description" rows="4" class="input py-2 text-body-sm">{{ old('description', $product->description) }}</textarea>
        </label>

        <div class="flex flex-wrap gap-4 pt-2">
            <label class="flex items-center gap-2 text-body-sm font-semibold">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="h-4 w-4 accent-blue-600" />
                Активний (показувати в магазині)
            </label>
            <label class="flex items-center gap-2 text-body-sm font-semibold">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="h-4 w-4 accent-blue-600" />
                Рекомендований (хіти)
            </label>
        </div>
    </div>

    {{-- Зображення з Unsplash --}}
    <div class="flex flex-col gap-4">
        <div class="card p-4">
            <h2 class="mb-2 text-body-md font-bold">Зображення</h2>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                URL зображень (по одному в рядку)
                <textarea name="images" rows="4" class="input py-2 text-body-sm"
                          placeholder="https://images.unsplash.com/photo-…">{{ old('images', collect($product->images ?? [])->implode("\n")) }}</textarea>
            </label>
            <p class="mt-1 text-caption text-outline">Перше зображення — головне фото товару.</p>

            @php($unsplashKey = config('services.unsplash.key'))
            @if ($unsplashKey)
                <div class="mt-3 border-t border-slate-100 pt-3">
                    <p class="mb-1.5 flex items-center gap-1 text-caption font-bold uppercase tracking-wide text-slate-500">
                        <span class="material-symbols-outlined text-[14px]">image_search</span> Пошук фото в Unsplash
                    </p>
                    <div class="flex gap-2">
                        <input type="text" id="tp-unsplash-q" placeholder="напр. iphone titanium" class="input py-1.5 text-body-sm" />
                        <button type="button" id="tp-unsplash-search" class="btn-secondary px-3 py-1.5 text-body-sm font-semibold">Знайти</button>
                    </div>
                    <div id="tp-unsplash-results" class="mt-2 grid grid-cols-3 gap-2"></div>
                </div>
            @endif
        </div>

        <div class="card p-4">
            <h2 class="mb-2 text-body-md font-bold">Технічні дані (JSON)</h2>
            <label class="mb-2 flex flex-col gap-1 text-body-sm font-semibold">
                Характеристики (масив рядків [ключ, значення])
                <textarea name="specs" rows="3" class="input py-2 font-mono text-[11px]" placeholder='[["Діагональ екрана", "6.3 дюйма"]]'>@if (isset($product)){{ json_encode($product->specs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}@else[["Ключ", "Значення"]]@endif</textarea>
            </label>
            <label class="mb-2 flex flex-col gap-1 text-body-sm font-semibold">
                Пам'ять (масив рядків)
                <textarea name="memory" rows="2" class="input py-2 font-mono text-[11px]" placeholder='["128GB", "256GB"]'>@if (isset($product)){{ json_encode($product->memory, JSON_UNESCAPED_UNICODE) }}@elseif (old('memory')){{ old('memory') }}@endif</textarea>
            </label>
            <label class="mb-2 flex flex-col gap-1 text-body-sm font-semibold">
                Кольори ([name, hex])
                <textarea name="colors" rows="3" class="input py-2 font-mono text-[11px]" placeholder='["Титановий", "Чорний"]'>@if (isset($product)){{ json_encode($product->colors, JSON_UNESCAPED_UNICODE) }}@elseif (old('colors')){{ old('colors') }}@endif</textarea>
            </label>
            <label class="flex flex-col gap-1 text-body-sm font-semibold">
                Повні характеристики (групи)
                <textarea name="full_specs" rows="4" class="input py-2 font-mono text-[11px]" placeholder='[["Процесор", "Snapdragon 8 Elite"]]'>@if (isset($product)){{ json_encode($product->full_specs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}@else[["Ключ", "Значення"]]@endif</textarea>
            </label>
        </div>

        <button type="submit" class="btn-primary w-full py-3 text-headline-sm">
            {{ $product->exists ? 'Зберегти зміни' : 'Створити товар' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
    const btn = document.getElementById('tp-unsplash-search');
    const input = document.getElementById('tp-unsplash-q');
    const hostResults = document.getElementById('tp-unsplash-results');
    const imagesArea = document.querySelector('textarea[name=images]');
    btn?.addEventListener('click', async () => {
        const query = input.value.trim();
        if (!query) return;
        btn.disabled = true;
        hostResults.innerHTML = '<p class="col-span-3 text-caption text-outline">Шукаємо…</p>';
        try {
            const res = await fetch("{{ route('admin.products.image-search') }}?q=" + encodeURIComponent(query));
            const data = await res.json();
            hostResults.innerHTML = '';
            for (const r of (data.results || [])) {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'overflow-hidden rounded-md border border-slate-200 hover:border-blue-500';
                b.innerHTML = `<img class="h-16 w-full cursor-pointer object-cover" loading="lazy" />`;
                b.querySelector('img').src = r.thumb ?? r.regular ?? '';
                b.alt = 'unsplash';
                // Клац — додати URL у textarea
                b.dataset.url = r.regular ?? '';
                b.addEventListener('click', () => {
                    const url = b.dataset.url;
                    const existing = imagesArea.value.trim();
                    imagesArea.value = existing ? existing + '\n' + url : url;
                });
                hostResults.appendChild(b);
            }
            if ((data.results || []).length === 0) {
                hostResults.innerHTML = '<p class="col-span-3 text-caption text-outline">Нічого не знайдено</p>';
            }
        } catch (e) {
            hostResults.innerHTML = '<p class="col-span-3 text-caption text-red-600">Помилка запиту до Unsplash</p>';
        } finally {
            btn.disabled = false;
        }
    });
</script>
@endpush
@endsection
