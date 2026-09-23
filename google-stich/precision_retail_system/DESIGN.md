---
name: Precision Retail System
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#434655'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#a73a00'
  on-secondary: '#ffffff'
  secondary-container: '#fd651e'
  on-secondary-container: '#571a00'
  tertiary: '#006329'
  on-tertiary: '#ffffff'
  tertiary-container: '#007f36'
  on-tertiary-container: '#c7ffca'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#ffdbce'
  secondary-fixed-dim: '#ffb599'
  on-secondary-fixed: '#370e00'
  on-secondary-fixed-variant: '#7f2b00'
  tertiary-fixed: '#7ffc97'
  tertiary-fixed-dim: '#62df7d'
  on-tertiary-fixed: '#002109'
  on-tertiary-fixed-variant: '#005320'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-hero:
    fontFamily: Inter
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 48px
    letterSpacing: -0.02em
  display-hero-mobile:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '800'
    lineHeight: 34px
    letterSpacing: -0.02em
  headline-xl:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 34px
    letterSpacing: -0.015em
  headline-lg:
    fontFamily: Inter
    fontSize: 22px
    fontWeight: '700'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: -0.005em
  headline-sm:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 20px
  price-display-lg:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '800'
    lineHeight: 28px
    letterSpacing: -0.02em
  price-display-md:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '700'
    lineHeight: 22px
    letterSpacing: -0.01em
  price-display-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '700'
    lineHeight: 18px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  specs-mono:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
  label-caps:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.04em
  caption:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '400'
    lineHeight: 14px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-mobile: 0.5rem
  margin: 1.5rem
  margin-mobile: 0.75rem
  space-2xs: 0.125rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 0.75rem
  space-lg: 1rem
  space-xl: 1.5rem
  space-2xl: 2rem
---

## Brand & Style

This design system embodies a **Corporate Modern High-Density Engineering** aesthetic engineered specifically for high-velocity, high-SKU omnichannel retail. Tailored for tech-savvy consumers, procurement professionals, and general consumers seeking reliable consumer electronics, the interface prioritizes immediate technical legibility, computational efficiency, and institutional trust.

The emotional response is calculated: surgical precision, absolute inventory transparency, frictionless navigation, and commercial urgency without chaotic visual noise. Visual artifacts embrace tight optical alignments, tabular data fidelity, strict 1:1 hardware photography on neutral backings, and disciplined chromatic signifiers (such as delivery dispatch indicators and localized fiscal currency formatting).

## Colors

The palette leverages a technical, high-contrast spectrum optimized for dense data parsing:

- **Primary Electric Blue (`#2563EB`)**: Anchor brand action color. Used for primary calls-to-action (Buy / Купити), active tab indicators, and search highlights. State variants: Hover (`#1D4ED8`), Pressed (`#1E40AF`), Subtle Surface Tint (`#EFF6FF`).
- **Secondary Commercial Orange (`#EA580C` / `#F97316`)**: Commercial conversion engine. Reserved exclusively for value-add signals: promotional callouts, promotional strikes, trade-in values, "Хіт продажу" (Bestseller) tags, and urgent discount badges. Background tint: `#FFF7ED`.
- **Functional Tertiary Emerald (`#16A34A`)**: Operational assurance. Applied to "В наявності" (In Stock) statuses, official manufacturer warranty checkmarks, and dispatch-ready indicators for courier lockers and logistics points. Tint: `#F0FDF4`.
- **Functional Alert Ruby (`#DC2626`)**: Critical scarcity. Utilized for out-of-stock notices, flash-deal countdown thresholds ("Залишилось 2 шт"), and validation errors. Tint: `#FEF2F2`.
- **Neutral Stack**:
  - Base Canvas: `#F8FAFC` (Slate 50) and secondary utility bands `#F1F5F9` (Slate 100).
  - Surfaces: Pure White `#FFFFFF` for product tiles, drawer sheets, and modal viewports.
  - Borders & Dividers: High-precision architectural lines in `#E2E8F0` (Slate 200, standard internal boundaries) and `#CBD5E1` (Slate 300, structural framing and focused inputs).
  - Typography: `#0F172A` (Slate 900, headline/price), `#334155` (Slate 700, labels/attributes), and `#64748B` (Slate 500, secondary specs/metadata).

## Typography

The typography is powered exclusively by **Inter**, leveraging OpenType features `tnum` (Tabular Numbers) and `cv05` (Lower-case l with tail) across all inventory tables, faceted counters, and financial figures. This avoids layout shift during real-time catalog price updates.

- **Localized Currency Engine**: The Ukrainian Hryvnia symbol (`₴`) is consistently rendered adjacent to numeric values using standard tabular spacing (`18 999 ₴`). The currency sign is weighted identically to the integer value, avoiding superscript shrinkage that reduces legibility in dense product grids.
- **Micro-Specifications**: For hardware specification sheets (RAM, SoC, Wattage), `specs-mono` applies tabular rendering with a subtle muted slate tone (`#475569`) to distinguish technical constants from marketing descriptions.

## Layout & Spacing

The layout is built on a **12-column high-density adaptive grid** optimized for immediate information absorption without wasted canvas space:

- **Desktop (1280px - 1440px max containment)**: 12 columns, 16px gutter (`gutter`), 24px side margin (`margin`). Product grids render in uniform 4-up or 5-up configurations with locked card dimensions.
- **Sidebar Allocation**: Desktop catalog views lock the faceted filter rail to a rigid 280px fixed width, allowing remaining real estate to reflow the SKU grid seamlessly.
- **Tablet (768px - 1279px)**: 8 columns, 12px gutter. Faceted navigation collapses into a persistent sticky filter bar. Grid renders 3-up.
- **Mobile (360px - 767px)**: 4 columns, 8px gutter (`gutter-mobile`), 12px canvas padding (`margin-mobile`). Grid defaults to 2-up compact product tiles or 1-up horizontal list cards for specification comparisons.
- **Dual-Tier Institutional Header**: The global header maintains a fixed desktop structure: Tier 1 (36px utility bar for city selection [e.g., Київ], language toggle [UA/EN], B2B portal link, and order tracking); Tier 2 (68px primary bar featuring the catalog mega-menu trigger, dynamic search autocomplete with SKU matching, logistics portal, and actionable cart summary).

## Elevation & Depth

This design system avoids decorative blurred drop-shadows, using instead **structural boundaries, low-contrast structural outlines, and crisp ambient occlusion**:

- **Flat Baseline (Level 0)**: Canvas is `#F8FAFC`. Embedded product cards rest directly on `#FFFFFF` surfaces defined by a 1px uniform outline of `#E2E8F0`.
- **Card Hover / Interactive (Level 1)**: Cards lift subtly using a sharp, technical shadow paired with an intensified border: `box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.08)`, border switches to `#CBD5E1`.
- **Overlays, Popovers & Search Menus (Level 2)**: Typeahead results, mini-cart preview, and city selectors use `box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 1px 3px 0 rgba(15, 23, 42, 0.05)` with a 1px `#CBD5E1` border.
- **Sticky Actions & Modals (Level 3)**: Fixed mobile action bars (sticky checkout buttons) use a top border of `#E2E8F0` with `box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06)`.

## Shapes

The design system standardizes on a **crisp 8px (`0.5rem`) geometric roundedness** across foundational elements, creating a balanced, modern, and sturdy consumer-electronics look:

- **Base Components**: Product cards, input fields, dropdown trigger buttons, segmented controls, and informational banners employ `roundedness: 2` (8px).
- **Nested Micro-Elements**: Chips, tags, rating badges, and promo pills use half-rounding (4px) to retain technical sharpness at small optical sizes.
- **Pills**: Exclusively reserved for floating operational chips (e.g., carousel dot indicators or dynamic active filter tokens that can be dismissed).

## Components

### Buttons
- **Primary ("Купити")**: Background `#2563EB`, text `#FFFFFF`, 8px radius, font `headline-sm`. Dense internal padding: 10px 16px (desktop), 8px 12px (mobile cards). Includes an inline cart icon; active state transitions to `#1E40AF`.
- **Secondary / Express Checkout**: Background `#EFF6FF`, text `#2563EB`, border 1px solid `#BFDBFE`. Used for "Купити в 1 клік" (1-Click Purchase).
- **Commercial Accent**: Background `#EA580C`, text `#FFFFFF`. Applied strictly to flash reservations or time-boxed checkout triggers.

### Product Card (Compact Retail Engine)
- **Canvas**: Pure white `#FFFFFF`, 1px border `#E2E8F0`, 8px radius. Hover triggers `#CBD5E1` border and a fast 150ms Level 1 lift.
- **Media Frame**: Strict 1:1 aspect ratio cut-out photography on a neutral-light backdrop (`#F8FAFC`), padded by 12px with a quick-view overlay trigger.
- **Badge Rail**: Top-left stacked cluster with 4px radii: "Хіт" (`#FFF7ED` / `#EA580C`), "-25%" (`#FEF2F2` / `#DC2626`), "0-0-12" installments (`#F1F5F9` / `#334155`).
- **Rating Cluster**: Star glyph in `#F59E0B` alongside numeric rating (e.g., `4.9`) and tabular review count in parentheses (`(124)`).
- **Price Block**: Old strike-through price in `specs-mono` (`#94A3B8`), current active price in `price-display-md` (`#0F172A`).

### Faceted Filter Rail
- Structured, compact accordion groups (Brand, Price Range, Screen Diagonal, Chipset).
- Checkboxes use an 8px square box with a 2px inner check icon in `#2563EB`. Count badges sit flush right in `specs-mono` (`#64748B`).
- Dual-thumb price slider with input boxes bound to minimum and maximum UAH thresholds.

### Logistics & Fulfillment Unit
- Dedicated delivery breakdown component inside product details and cart line items:
  - **Nova Poshta (Нова Пошта)**: Visual branch/locker indicator with estimated delivery date ("Завтра з 09:00") and verified cost badge ("від 80 ₴").
  - **Store Pickup (Самовивіз)**: Terse green dot (`#16A34A`), "Забрати сьогодні", hyperlinked to real-time store stock availability across regional centers.

### Input Fields & Search
- Global Search Bar: 44px height, `#F1F5F9` surface with `#CBD5E1` border, 8px radius. Expands into an instant-search floating flyout categorizing matching queries, brand hubs, and top-selling SKUs.
- Form Elements: 40px standard height, text in `#0F172A`, placeholder in `#94A3B8`. Focus state applies a crisp 2px ring in `#2563EB` with an internal white offset.