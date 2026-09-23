# ТЕХНОПРОСТІР — Tech Store

Демо інтернет-магазину електроніки та побутової техніки для України: статичний сайт на Astro з повним покупницьким флоу (каталог → товар → кошик → оформлення → успіх). Повний продуктовий спес — у [`PROJECT.md`](PROJECT.md), дизайн-система — у [`DESIGN.md`](DESIGN.md), референси екранів — у `google-stich/`.

## Стек

| Шар | Технологія |
| --- | --- |
| Генерація сторінок | [Astro 7](https://docs.astro.build) (статичний вивід, `output: static`) |
| Стилі | [Tailwind CSS 4](https://tailwindcss.com) — CSS-first тема через `@theme`, плагін `@tailwindcss/vite` |
| Логіка кошика/пошуку | Vanilla JS у `<script>`-блоках сторінок + `src/scripts/cart.js` (без UI-фреймворків) |
| Деплой | Vercel → <https://tech-store-omega-ten.vercel.app> |
| SEO | canonical/OG/Twitter/JSON-LD, `@astrojs/sitemap`, `hreflang`-готовий `lang="uk"` |

## Швидкий старт

Вимога: Node.js **≥ 22.12**.

```bash
npm install
npm run dev        # дев-сервер
npm run build      # збірка у dist/ (єдина перевірка: лінтерів/тестів немає)
npm run preview    # перегляд зібраного результату
```

Корисні команди: `npm run astro -- --help` (Astro CLI).

## Що вміє (MVP)

- **Головна**: промо-hero, щоденні деали, популярні категорії, хіти продажів, новинки, переваги, бренди.
- **Каталог / категорії**: фільтри (ціна зі слайдером, наявність, бренд із пошуком, памʼять), чіпи, сортування, мобільний drawer, порожні стани.
- **Пошук**: `/search?q=…&cat=…` — клієнтська фільтрація за назвою/брендом/категорією.
- **Картка товару**: галерея, вибір кольору/памʼяти/кількості, характеристики, опис, відгуки, питання, «Разом дешевше», схожі товари.
- **Кошик**: localStorage, кількість/сума, промокод **`TECHNO2026`** (−500 ₴), знижка магазину 10%, апсели, безкоштовна доставка.
- **Оформлення**: 4 кроки (контакти, доставка, оплата, побажання) + липкий підсумок → `/success` з номером замовлення.
- **Особистий кабінет**: останнє замовлення з checkout + демо-замовлення, бонуси ТехноБонус.
- Адаптив: мобільний хедер із пошуком + нижня навігація; все українською, ціни в ₴.

## Структура

```
src/
├── data/products.js        # єдине джерело каталогу: категорії + 34 товари
├── layouts/BaseLayout.astro# html-оболонка: SEO, хедер/футер, #tp-catalog JSON, cart.js
├── components/             # Header, Footer, MobileNav, ProductCard, CatalogView
├── scripts/cart.js         # кошик, підсумки, бейджі, тости (exports: cart, totals, formatPrice)
├── styles/global.css       # Tailwind v4 @theme (токени) + @layer components
└── pages/
    ├── index.astro         # головна
    ├── catalog/            # /catalog та /catalog/[category]
    ├── search.astro        # пошук
    ├── product/[slug].astro# картка товару (getStaticPaths з products.js)
    ├── cart.astro          # кошик
    ├── checkout.astro      # оформлення
    ├── success.astro       # підтвердження замовлення
    └── profile.astro       # особистий кабінет
google-stich/               # референс-екрани Google Stitch (screen.png + code.html)
PROJECT.md, DESIGN.md       # продуктовий спес і дизайн-система
AGENTS.md                   # інструкції для AI-агентів (архітектура, пастки)
```

## Важливі правила

- **Tailwind v4 тільки CSS-first.** Токени — у `src/styles/global.css` (`@theme`), підключення — `@tailwindcss/vite` в `astro.config.mjs`. Не додавайте `tailwind.config.mjs`, `postcss.config.*` чи `@tailwindcss/postcss`: Vite тоді падає з `ENOENT ... tailwindcss` на `@import "tailwindcss"`.
- **Каталог редагується в одному місці**: додали товар у `src/data/products.js` — він автоматично зʼявиться в каталозі, категоріях, пошуку та на своїй сторінці.
- **Без бекенду**: кошик і промокод живуть у `localStorage`, оформлене замовлення — у `sessionStorage` (`tp_last_order`). Немає API — це статичний демо-сайт.
- **Контент**: лише українська мова, ціни в ₴, українські міста/доставка/оплата (див. `PROJECT.md`).
- **Кнопка «В кошик»**: будь-який елемент із `data-add-to-cart="<slug>"` працює автоматично.

## Документація

- Astro: <https://docs.astro.build> — [роутинг](https://docs.astro.build/en/guides/routing/), [компоненти](https://docs.astro.build/en/basics/astro-components/), [стилі/Tailwind 4](https://docs.astro.build/en/guides/styling/#add-tailwind-4)
- Tailwind 4: <https://tailwindcss.com/docs>
- Продуктовий спес: [`PROJECT.md`](PROJECT.md) · Дизайн: [`DESIGN.md`](DESIGN.md)
