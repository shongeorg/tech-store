<?php

use Illuminate\Support\Carbon;

if (! function_exists('format_price')) {
    /**
     * Format a price in UAH, for example 54999 -> "54 999 ₴".
     */
    function format_price(int|float|string|null $value): string
    {
        return number_format((float) $value, 0, ',', ' ').' ₴';
    }
}

if (! function_exists('format_date_uk')) {
    /**
     * Format a date in Ukrainian, for example "18 жовтня 2026".
     */
    function format_date_uk(Carbon|DateTimeInterface|null $date): string
    {
        if ($date === null) {
            return '—';
        }

        $carbon = Carbon::parse($date)->locale('uk');

        return $carbon->translatedFormat('d F Y');
    }
}

if (! function_exists('spec_pair')) {
    /**
     * Normalize a spec row to a [label, value] pair.
     *
     * Product specs may come either as list rows [label, value] or
     * as single-entry maps [label => value].
     *
     * @return array{0: string, 1: string}
     */
    function spec_pair(mixed $row): array
    {
        if (! is_array($row) || $row === []) {
            return ['', ''];
        }

        if (array_is_list($row)) {
            return [(string) $row[0], (string) ($row[1] ?? '')];
        }

        $label = (string) array_key_first($row);

        return [$label, (string) $row[$label]];
    }
}

if (! function_exists('string_list')) {
    /**
     * Normalize JSON values to a plain list of non-empty strings.
     *
     * Column values may come as a list (["a", "b"]), an assoc map, or — in older
     * seed data — a single string, which Laravel collections would otherwise
     * explode into per-character items.
     *
     * @return list<string>
     */
    function string_list(mixed $value): array
    {
        if (is_string($value)) {
            return $value !== '' ? [$value] : [];
        }

        if (! is_array($value)) {
            return [];
        }

        $items = array_is_list($value) ? $value : array_values($value);

        return array_values(array_filter($items, fn ($item): bool => is_string($item) && $item !== ''));
    }
}

if (! function_exists('image_list')) {
    /**
     * List of image URLs for a product (https://...), [] when none.
     *
     * @return list<string>
     */
    function image_list(mixed $value): array
    {
        return array_values(array_filter(
            string_list($value),
            fn (string $item): bool => str_starts_with($item, 'http'),
        ));
    }
}
