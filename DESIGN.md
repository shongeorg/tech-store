---
name: Precision Retail
colors:
  surface: '#faf8ff'
  surface-dim: '#d2d9f4'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f3ff'
  surface-container: '#eaedff'
  surface-container-high: '#e2e7ff'
  surface-container-highest: '#dae2fd'
  on-surface: '#131b2e'
  on-surface-variant: '#434655'
  inverse-surface: '#283044'
  inverse-on-surface: '#eef0ff'
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
  background: '#faf8ff'
  on-background: '#131b2e'
  surface-variant: '#dae2fd'
typography:
  display-hero:
    fontFamily: Inter
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  display-hero-mobile:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Inter
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.015em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: -0.005em
  price-xl:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.02em
  price-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '700'
    lineHeight: 24px
    letterSpacing: -0.015em
  price-sm:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '700'
    lineHeight: 20px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
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
  caption:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
  label-badge:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-tablet: 1.5rem
  margin-desktop: 2rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 0.75rem
  space-lg: 1.25rem
  space-xl: 2rem
---

## Brand & Style

This design system establishes a high-performance, structured, and modern retail environment tailored for major electronics, technology, and home appliances e-commerce. The interface communicates technical reliability, operational efficiency, and absolute pricing transparency.

The aesthetic is grounded in **Corporate / Modern High-Density Engineering**, prioritizing scan-speed and cognitive clarity over decorative noise. The visual atmosphere feels precise, dependable, and swift—reminiscent of a modern tech warehouse or flagship showroom.

Visual principles:
- **Clarity over ornament:** Decoration is strictly functional. Visual weight belongs exclusively to products, actionable states, key specifications, and pricing data.
- **Structured density:** High information architecture must not feel claustrophobic. Deep hierarchy, strict tabular alignments, and disciplined micro-spacing ensure vast amounts of catalog information remain instantly digestible.
- **Affirmative actions:** Primary interactive targets use vivid chromatic clarity to guide the shopper from browsing through frictionless checkout.

## Colors

The system uses a calibrated cool-neutral structure accented by an energetic, authoritative blue for system focus and purposeful functional accents for commercial messaging.

### Application Roles
- **Primary Blue (`#2563EB`)**: Anchor color for transactional calls-to-action (Add to Cart, Buy Now), primary filter chips, active navigation states, and key interactive highlights.
  - Hover: `#1D4ED8`
  - Active: `#1E40AF`
  - Subtle Surface: `#EFF6FF` (selection highlights, active category pills, promo containers)
- **Secondary Orange (`#EA580C` / `#F97316`)**: Reserved strictly for commercial hooks, promo callouts, flash sales, discount percentages, and "Hit / Best Seller" badges.
  - Tint background: `#FFF7ED`
- **Functional Tertiary Emerald (`#16A34A`)**: Delivery timelines, "In Stock" indicators, and verified guarantee markers.
  - Tint background: `#F0FDF4`
- **Functional Alert Ruby (`#DC2626`)**: Limited quantities ("Only 2 left"), deadline countdowns, cart clearance warnings, and system errors.
  - Tint background: `#FEF2F2`
- **Neutrals & Surfaces**:
  - Main App Surface: Pure white (`#FFFFFF`) for product display cleanliness.
  - Canvas / Secondary Surface: `#F8FAFC` and `#F1F5F9` for page backgrounds, spec table alternations, and header lower-tiers.
  - Borders & Dividers: `#E2E8F0` for default component boundaries; `#CBD5E1` for hover states and structural dividers.
  - Text: High-contrast `#0F172A` (Headlines, Titles, Prices), neutral `#334155` (Body, Specs, Descriptions), and `#64748B` (Secondary metadata, SKU, reviews count).

## Typography

The type system relies on **Inter** configured with tabular numeric figures (`tnum`, `cv05`, `cv11`) enabled globally across price tags, inventory counters, rating counts, and technical specification values.

### Formatting Rules
- **Prices**: Always set in tabular numerals to prevent layout jiggle on comparative views. Currency symbols drop 1-2 weights or scale down slightly (e.g., 85% of font-size) relative to the integer amount.
- **Strikethrough Old Prices**: Must use `body-sm` or `caption` with `#94A3B8`, strictly lighter in weight (400 or 500) than the active promotional price.
- **Product Titles**: Use `headline-sm` in listing grids; clamp strictly to 2 lines (`line-clamp-2`) with balanced vertical clearance.
- **Specification Tables**: Labels use `body-sm` at `#64748B` (Regular), paired with `body-sm` at `#0F172A` (Medium 500) for parameter values.

## Layout & Spacing

The layout is built upon an adaptive 12-column grid engine optimized for broad catalog density, maximum width efficiency, and structured sidebars.

### Viewport Configurations
- **Desktop (1280px - 1440px+)**:
  - Max container width: 1440px centered.
  - Outer margins: `2rem` (32px).
  - Main Catalog split: 280px fixed-width faceted filter sidebar + 9-column dynamic product card grid (yielding 3 or 4 cards per row).
  - Column gutters: `1.5rem` (24px).
- **Tablet (768px - 1279px)**:
  - Outer margins: `1.5rem` (24px).
  - Sidebar collapses into a sticky horizontal filter row and sliding sheet drawer.
  - Catalog shifts to 3-column or 2-column grid.
  - Column gutters: `1rem` (16px).
- **Mobile (320px - 767px)**:
  - Outer margins: `1rem` (16px).
  - Catalog utilizes a 2-column compact grid or continuous horizontal scrolling carousels.
  - Column gutters: `0.5rem` to `0.75rem` (8px to 12px) to maximize touch target area and screen real estate.

### Spacing Cadence
Component internals respect strict 4px/8px incremental boundaries. Interactive cards apply `space-md` internal padding. Product thumbnails sit within dedicated aspect-ratio containers (1:1 standard) with minimal micro-gaps separating media from titles.

## Elevation & Depth

Visual separation is primarily achieved through **crisp low-contrast borders combined with faint ambient micro-shadows**. This avoids murky grey build-up and maintains visual agility across thousands of adjacent products.

### Elevation Hierarchy
1. **Level 0 (Flat Ground)**: Base background `#F8FAFC`. Zero elevation, framed by single pixel borders (`#E2E8F0`).
2. **Level 1 (Resting Cards & Panels)**: `#FFFFFF` card surfaces. Single border `1px solid #E2E8F0` coupled with a subtle ambient shadow: `box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.03)`.
3. **Level 2 (Interactive Hover & Card Focus)**: Product cards translate upward 2px on desktop cursor hover, transitioning border to `#CBD5E1` and deepening shadow: `box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.03)`.
4. **Level 3 (Sticky Headers, Floating Bars & Popovers)**: Top tier navigation bars, floating cart trays, and autocomplete search dropdowns. Border `#E2E8F0` with `box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.1)`.
5. **Level 4 (Modals, Overlays & Quick-View Dialogs)**: Backdrop overlay `rgba(15, 23, 42, 0.5)` with `backdrop-filter: blur(4px)`. Dialog uses `box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25)`.

## Shapes

The design system employs a **Rounded** shape model (`0.5rem` / 8px baseline) that softens technical hardware imagery while maintaining sharp horizontal and vertical rhythm.

### Geometric Application
- **Default (8px / `0.5rem`)**: Standard interactive controls—buttons, text inputs, catalog card containers, dropdown selectors, and banner promo panels.
- **Large (12px - 16px / `0.75rem` - `1rem`)**: Modal dialog containers, large featured category tiles, and notification toast banners.
- **Badges & Micro Tags**: 4px radius for tight spec pills (e.g., "OLED", "120Hz"), or full-pill (`9999px`) for promotional badges ("-25%", "Best Deal", "Top Rated") to distinguish commercial perks from functional hardware specs.

## Components

### Header (Two-Tier Structure)
- **Top Utility Tier (`#0F172A` or `#F1F5F9`)**: Dense micro-bar providing location/store selector, customer service links, B2B portal, order status lookup, and language/currency toggles.
- **Main Action Tier (`#FFFFFF`, sticky, 72px desktop height)**:
  - Brand logomark.
  - Prominent "Catalog" drawer toggle button (Primary Blue `#2563EB` fill, white bold text, burger icon).
  - Omnipresent Search Bar: Expands across available center width, bordered by `#CBD5E1`, internal clear icon, integrated category selector chip, and direct return action.
  - User Action Clusters: High-density iconography paired with clear text labels (Compare, Wishlist with counter badge, Cart with dynamic price total and floating badge counter).

### Product Cards
- **Structure**: Set inside a `#FFFFFF` container with `#E2E8F0` border and `8px` radius.
- **Top Layer**: Badges pinned to upper corners (Promo Pill on top-left in Orange/Red; Wishlist/Compare action buttons top-right, transitioning to visible on hover).
- **Thumbnail Zone**: Padded 1:1 image frame with neutral-white background, strictly containing high-resolution cut-out equipment imagery.
- **Metadata**: Category path or brand tag in `caption` `#64748B`.
- **Title**: Inter Medium (`14px`), max 2 lines with standard height reservation.
- **Rating Matrix**: 5-star cluster (Filled Amber `#F59E0B`, Unfilled `#E2E8F0`), followed by numerical rating and review count in parentheses.
- **Pricing Stack**:
  - Main Price: `price-lg` in `#0F172A`.
  - Comparison / Old Price: Strikethrough in `#94A3B8`.
  - Installment / Monthly Subtitle: Tiny label ("or $45/mo").
- **CTA**: Integrated "Add to Cart" button or responsive icon-button transforming into an inline stepper (`- 1 +`) upon selection.

### Buttons & Interactive Controls
- **Primary CTA**: Background `#2563EB`, text `#FFFFFF`, font-weight 600. Active states drop to `#1E40AF`.
- **Secondary CTA**: Background `#EFF6FF`, text `#2563EB`, border `1px solid #BFDBFE`.
- **Commercial Action**: Background `#EA580C`, text `#FFFFFF` for limited flash sales.
- **Outline / Filter Button**: Background `#FFFFFF`, border `1px solid #E2E8F0`, text `#334155`.

### Faceted Filter Sidebar
- **Section Headers**: Bold `headline-sm` with expandable chevron arrows.
- **Controls**:
  - Custom Checkboxes: 18px square, `#CBD5E1` border, transitioning to `#2563EB` fill with white checkmark when selected. Accompanying brand counts aligned flush right (`#94A3B8`).
  - Dual-Thumb Range Sliders: Blue track fill with tactile white thumb handles (`#FFFFFF` with `#CBD5E1` border and subtle elevation).
  - Quick Selection Chips: Compact pills that turn `#EFF6FF` with blue borders when active.

### Specification Tables
- Clean zebra-striping utilizing alternating rows (`#FFFFFF` and `#F8FAFC`).
- 1px border lines in `#E2E8F0`.
- Left-column attribute names (`#64748B`, 40% width); right-column technical parameters (`#0F172A` font-weight 500, tabular figures).
