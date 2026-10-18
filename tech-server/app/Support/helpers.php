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
