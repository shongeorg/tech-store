// Спільний клієнт Laravel JSON API (/api/v1) для Astro-сторінок.
// Сторінки залишаються статикою: HTML-оболонка зі скелетонами, дані доганяє
// клієнтський fetch. Сортування, фільтри та пагінація теж у API-параметрах.

export const API_BASE: string =
  (import.meta.env.PUBLIC_API_BASE as string | undefined) ?? 'http://tech-server.test/api/v1';

export interface ApiCategory {
  slug: string;
  name: string;
  nav_name: string;
  emoji: string;
  icon: string;
  products_count: number;
}

export interface ApiProduct {
  slug: string;
  title: string;
  brand: string;
  price: number;
  old_price: number | null;
  discount: number;
  rating: number;
  reviews_count: number;
  badge: string | null;
  badge_kind: string | null;
  installment: string | null;
  sku: string | null;
  stock: string;
  stock_text: string;
  emoji: string;
  image: string | null;
  specs: [string, string][];
  category: { slug: string | null; name: string | null };
}

export interface ApiProductDetail extends ApiProduct {
  short: string | null;
  description: string | null;
  full_specs: [string, string][];
  memory: string[];
  colors: Array<{ name: string; hex: string }>;
  images: string[];
}

export interface ApiPagination {
  current_page: number;
  last_page: number;
  total: number;
}

export interface Paginated<T> {
  data: T[];
  meta: ApiPagination;
}

const ua = new Intl.NumberFormat('uk-UA');
export const money = (v: number): string => `${ua.format(v)} ₴`;

/** GET з API_BASE; onLatency — замір RTT для бейджа «API: N мс». */
export async function apiGet<T>(path: string, onLatency?: (ms: number) => void): Promise<T> {
  const started = performance.now();
  const res = await fetch(`${API_BASE}${path}`, { headers: { Accept: 'application/json' } });
  if (onLatency) onLatency(Math.round(performance.now() - started));
  if (!res.ok) throw new Error(`API ${res.status}`);
  return (await res.json()) as T;
}

/** URLSearchParams з підтримкою масивів (brands[],
  memory[]) — як чекає Laravel. */
export function qs(
  params: Record<string, string | number | string[] | undefined | null>,
): string {
  const sp = new URLSearchParams();
  for (const [key, value] of Object.entries(params)) {
    if (value === undefined || value === null || value === '') continue;
    for (const item of Array.isArray(value) ? value : [String(value)]) {
      if (item !== '') sp.append(key, item);
    }
  }
  const s = sp.toString();
  return s === '' ? '' : `?${s}`;
}

const esc = (s: string): string =>
  s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');

/** Скелетон-картки до першої відповіді API (grid/home як ProductCard). */
export function skeletonCards(count: number): string {
  return Array.from(
    { length: count },
    () => `
  <div class="card flex flex-col gap-space-sm overflow-hidden p-0">
    <div class="skeleton aspect-square w-full"></div>
    <div class="flex flex-col gap-space-sm p-space-md">
      <div class="skeleton h-4 w-1/3"></div>
      <div class="skeleton h-5 w-3/4"></div>
      <div class="skeleton h-7 w-2/5"></div>
    </div>
  </div>`,
  ).join('');
}

/** Картка товару з API — та сама розмітка, що ProductCard.astro. */
export function cardHtml(p: ApiProduct, variant: 'grid' | 'home' = 'grid'): string {
  const title = esc(p.title);
  const brandBadge = p.badge
    ? `<span class="badge badge-${p.badge_kind ?? 'muted'}">${esc(p.badge)}</span>`
    : '';
  const discountBadge =
    p.discount > 0 ? `<span class="badge badge-action">-${p.discount}%</span>` : '';
  const installmentBadge =
    variant === 'grid' && p.installment
      ? `<span class="badge badge-muted lowercase">${esc(p.installment)}</span>`
      : '';
  const media = p.image
    ? `<img src="${esc(p.image)}" alt="${title}" loading="lazy" decoding="async" class="h-full w-full object-cover" />`
    : `<span class="select-none text-7xl">${p.emoji}</span>`;
  const specRows =
    variant === 'grid'
      ? p.specs
          .slice(0, 3)
          .map(
            ([k, v]) =>
              `<div class="flex justify-between gap-2"><dt class="text-slate-500">${esc(k)}:</dt><dd class="truncate font-medium text-on-surface">${esc(v)}</dd></div>`,
          )
          .join('')
      : '';
  const isNew = p.badge === 'НОВИНКА';

  return `
  <article class="group relative flex flex-col overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest transition hover:border-slate-300 hover:shadow-[0_4px_12px_-2px_rgba(15,23,42,0.08)]" data-product-card data-slug="${p.slug}">
    <div class="absolute left-2 top-2 z-10 flex flex-col items-start gap-1">${brandBadge}${discountBadge}${installmentBadge}</div>
    <button type="button" aria-label="В обране" class="absolute right-2 top-2 z-10 grid h-8 w-8 place-items-center rounded-full border border-outline-variant bg-white/85 text-slate-500 transition hover:text-pink-500">
      <span class="material-symbols-outlined text-[18px]">favorite</span>
    </button>
    <a href="/product/${p.slug}" class="block aspect-square bg-[#f8fafc] p-3" tabindex="-1" aria-hidden="true">
      <div class="grid h-full select-none place-items-center text-7xl transition-transform duration-200 group-hover:scale-105">${media}</div>
    </a>
    <div class="flex flex-1 flex-col gap-space-sm p-space-md">
      ${
        variant === 'grid'
          ? `<div class="flex items-center justify-between text-specs-mono text-slate-400">
        <span>Артикул: ${p.sku ?? '—'}</span>
        <span class="flex items-center gap-1 rounded-sm bg-slate-100 px-1.5 py-0.5 font-semibold text-slate-500">0-0-15</span>
      </div>`
          : ''
      }
      <div class="flex items-center justify-between gap-2">
        <span class="flex items-center gap-1 text-body-sm">
          <span class="text-amber-500">★</span>
          <span class="font-semibold">${p.rating.toFixed(1)}</span>
          <span class="text-outline">(${p.reviews_count} відгуків)</span>
        </span>
        ${isNew ? '<span class="badge badge-new">NEW 2026</span>' : ''}
      </div>
      <a href="/product/${p.slug}" class="line-clamp-2 min-h-10 text-body-md font-semibold leading-5 text-on-surface hover:text-blue-600">${title}</a>
      ${variant === 'grid' && specRows ? `<dl class="hidden flex-col gap-1 text-body-sm text-on-surface-variant md:flex">${specRows}</dl>` : ''}
      <p class="flex items-start gap-1 text-[12px] leading-4 text-tertiary">
        <span class="material-symbols-outlined mt-px text-[14px]">local_shipping</span>${esc(p.stock_text)}
      </p>
      <div class="mt-auto flex flex-wrap items-baseline gap-x-2 gap-y-1 pt-1">
        <span class="tabular text-price-display-md text-on-surface">${money(p.price)}</span>
        ${
          p.old_price
            ? `<span class="tabular text-specs-mono text-slate-400 line-through">${money(p.old_price)}</span>`
            : ''
        }
      </div>
      <div class="flex items-center gap-space-sm">
        <button
          type="button"
          data-add-to-cart="${p.slug}"
          class="flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-blue-600 py-2.5 font-semibold text-white transition hover:bg-blue-700 active:bg-blue-800 ${variant === 'grid' ? 'text-headline-sm' : 'text-body-md'}"
        >
          <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
          ${variant === 'grid' ? 'В кошик' : 'Купити'}
        </button>
        ${
          variant === 'grid'
            ? `<a href="/product/${p.slug}" class="btn-secondary whitespace-nowrap px-3 py-2.5 text-body-sm font-semibold">В 1 клік</a>`
            : ''
        }
      </div>
    </div>
  </article>`;
}

/** Вмагничує .skeleton та .chip-active стилі один раз у <head>. */
export function ensureApiStyles(): void {
  if (document.getElementById('tp-api-css') !== null) return;
  const style = document.createElement('style');
  style.id = 'tp-api-css';
  style.textContent = `
    .skeleton{display:inline-block;border-radius:6px;background:linear-gradient(90deg,#eef1f6 25%,#f6f8fb 50%,#eef1f6 75%);background-size:200% 100%;animation:tp-skeleton 1.2s ease-in-out infinite}
    @keyframes tp-skeleton{from{background-position:200% 0}to{background-position:-200% 0}}
    .chip-active{border-color:#2563eb;background:#eff6ff;color:#1d4ed8}`;
  document.head.appendChild(style);
}
