## Development

When starting the dev server, use background mode:

```
astro dev --background
```

Manage the background server with `astro dev stop`, `astro dev status`, and `astro dev logs`.

Verification = `npm run build` (50 static pages must build; there is no lint/test/typecheck script).

## Project facts

- Static Astro site, deploy target `https://tech-store-omega-ten.vercel.app` (set in `astro.config.mjs`, sitemap integration).
- **Tailwind v4 is CSS-first**: all design tokens live in `src/styles/global.css` under `@theme`; there is deliberately no `tailwind.config.mjs`, no `postcss.config`, and no `@tailwindcss/postcss`. The wiring is the `@tailwindcss/vite` plugin in `astro.config.mjs`. Do not "fix" this by adding PostCSS config: Vite's postcss-import fails on `@import "tailwindcss"` (`ENOENT ... tailwindcss`) if Tailwind runs as a PostCSS plugin.
- `PROJECT.md` is the authoritative product spec. Hard constraints: UI text in Ukrainian only, prices in UAH (₴) only, Ukrainian cities/delivery/payment data, Russian localization is forbidden.
- Design sources of truth: `DESIGN.md` and `google-stich/precision_retail_system/DESIGN.md` (tokens; `global.css` `@theme` mirrors them). `google-stich/*/screen.png` + `code.html` are the Google Stitch reference screens for each page — match layouts to them.
- Products/catalog: single source `src/data/products.js` (categories + products, Ukrainian titles, ₴ prices). Page routes derive from it via `getStaticPaths` — add a product there and it appears on catalog/category/search/PDP automatically. Client bundle gets it as `#tp-catalog` JSON embedded by `src/layouts/BaseLayout.astro`.
- Cart/checkout/search are client-side only (no backend): cart + promo in `localStorage` (`tp_cart`, `tp_promo`; demo promo code `TECHNO2026` = −500 ₴), completed order in `sessionStorage` (`tp_last_order` → `/success`, shown on `/profile`). Core logic: `src/scripts/cart.js` (exports `cart`, `totals`, `formatPrice`, `showToast`). Add-to-cart button = `data-add-to-cart="<slug>"` on any element.
- Shared shell: `BaseLayout.astro` → `Header` / `Footer` / `MobileNav`. Product tiles: `ProductCard.astro` (`variant="grid"` for catalog, `"home"` for home/upsell). Catalog filters/sort: `CatalogView.astro` (client JS over `data-*` attributes).

## Documentation

Full documentation: https://docs.astro.build — consult the [routing](https://docs.astro.build/en/guides/routing/), [Astro components](https://docs.astro.build/en/basics/astro-components/) and [styling/Tailwind 4](https://docs.astro.build/en/guides/styling/#add-tailwind-4) guides before working on related tasks.
