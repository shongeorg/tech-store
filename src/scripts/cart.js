// Клієнтське ядро магазину: кошик у localStorage, бейджі, тости, таймери.
// Підключається з BaseLayout на кожній сторінці.

const CART_KEY = 'tp_cart';
const PROMO_KEY = 'tp_promo';
const ORDER_KEY = 'tp_last_order';
export const PROMO_CODE = 'TECHNO2026';
export const PROMO_DISCOUNT = 500;
export const STORE_DISCOUNT_RATE = 0.1;

export function formatPrice(n) {
  return `${new Intl.NumberFormat('uk-UA').format(Math.round(n))} ₴`;
}

function safeParse(value, fallback) {
  try {
    return value ? JSON.parse(value) : fallback;
  } catch {
    return fallback;
  }
}

export function readCart() {
  return safeParse(localStorage.getItem(CART_KEY), []);
}

function writeCart(items) {
  localStorage.setItem(CART_KEY, JSON.stringify(items));
  updateBadges();
  document.dispatchEvent(new CustomEvent('tp:cart-change'));
}

export const cart = {
  items: () => readCart(),
  count: () => readCart().reduce((s, i) => s + i.qty, 0),
  sum: () => readCart().reduce((s, i) => s + i.price * i.qty, 0),
  add(item, qty = 1) {
    const items = readCart();
    const found = items.find((i) => i.slug === item.slug);
    if (found) found.qty += qty;
    else items.push({ ...item, qty });
    writeCart(items);
  },
  setQty(slug, qty) {
    let items = readCart();
    if (qty <= 0) items = items.filter((i) => i.slug !== slug);
    else {
      const it = items.find((i) => i.slug === slug);
      if (it) it.qty = qty;
    }
    writeCart(items);
  },
  remove(slug) {
    writeCart(readCart().filter((i) => i.slug !== slug));
  },
  clear() {
    writeCart([]);
  },
};

export function getPromo() {
  return localStorage.getItem(PROMO_KEY) === PROMO_CODE;
}

export function setPromo(on) {
  if (on) localStorage.setItem(PROMO_KEY, PROMO_CODE);
  else localStorage.removeItem(PROMO_KEY);
  document.dispatchEvent(new CustomEvent('tp:cart-change'));
}

// Підсумки кошика: товари - знижка магазину10% - промокод + доставка0.
export function totals() {
  const subtotal = cart.sum();
  const storeDiscount = Math.round(subtotal * STORE_DISCOUNT_RATE);
  const promo = getPromo() && subtotal > 0 ? PROMO_DISCOUNT : 0;
  const delivery = 0;
  return {
    count: cart.count(),
    subtotal,
    storeDiscount,
    promo,
    delivery,
    total: Math.max(0, subtotal - storeDiscount - promo),
  };
}

export function getCatalog() {
  const el = document.getElementById('tp-catalog');
  return el ? safeParse(el.textContent, []) : [];
}

export function updateBadges() {
  const count = cart.count();
  const sum = cart.sum();
  document.querySelectorAll('[data-cart-count]').forEach((el) => {
    el.textContent = String(count);
    el.classList.toggle('hidden', count === 0);
  });
  document.querySelectorAll('[data-cart-sum]').forEach((el) => {
    el.textContent = formatPrice(sum);
  });
}

export function showToast(message, linkHref, linkText) {
  let host = document.getElementById('tp-toast-host');
  if (!host) {
    host = document.createElement('div');
    host.id = 'tp-toast-host';
    host.className = 'fixed bottom-20 left-1/2 z-50 flex -translate-x-1/2 flex-col items-center gap-2 lg:bottom-6';
    document.body.appendChild(host);
  }
  const toast = document.createElement('div');
  toast.className =
    'flex items-center gap-3 rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-body-md shadow-[0_10px_25px_-5px_rgba(15,23,42,0.12)] animate-in';
  toast.innerHTML = `<span class="material-symbols-outlined text-tertiary">check_circle</span><span>${message}</span>`;
  if (linkHref) {
    const a = document.createElement('a');
    a.href = linkHref;
    a.className = 'font-semibold text-primary underline underline-offset-2';
    a.textContent = linkText ?? 'Перейти';
    toast.appendChild(a);
  }
  host.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity .3s';
    setTimeout(() => toast.remove(), 320);
  }, 2600);
}

function productFromCard(btn) {
  const slug = btn.dataset.addToCart;
  const product = getCatalog().find((p) => p.slug === slug);
  if (!product) return null;
  return {
    slug: product.slug,
    title: product.title,
    short: product.short ?? '',
    price: product.price,
    oldPrice: product.oldPrice ?? null,
    emoji: product.emoji,
    category: product.category,
  };
}

function initGlobal() {
  updateBadges();

  // Додавання в кошик з будь-якої кнопки [data-add-to-cart="<slug>"]
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-add-to-cart]');
    if (!btn) return;
    const item = productFromCard(btn);
    if (!item) return;
    cart.add(item, Number(btn.dataset.qty ?? 1));
    showToast('Товар додано до кошика', '/cart', 'До кошика');
  });

  // «Фіксація ціни» / зворотний відлік до кінця дня
  document.querySelectorAll('[data-countdown-to-end-of-day]').forEach((el) => {
    const tick = () => {
      const now = new Date();
      const end = new Date(now);
      end.setHours(23, 59, 59, 999);
      let diff = Math.max(0, Math.floor((end - now) / 1000));
      const h = String(Math.floor(diff / 3600)).padStart(2, '0');
      const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
      const s = String(diff % 60).padStart(2, '0');
      el.textContent = el.dataset.countdownLabel
        ? `${el.dataset.countdownLabel} ${h}:${m}:${s}`
        : `${h}:${m}:${s}`;
    };
    tick();
    setInterval(tick, 1000);
  });
}

initGlobal();
