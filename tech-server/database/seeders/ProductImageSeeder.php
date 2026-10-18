<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    private const CACHE_PATH = 'seeders/unsplash_images.json';

    private const CATEGORY_KEYWORDS = [
        'smartphones' => 'smartphone',
        'laptops' => 'laptop computer',
        'tv' => 'television',
        'appliances' => 'home appliance',
        'components' => 'computer hardware',
        'gaming' => 'video game console',
        'power' => 'portable power station',
        'wearables' => 'smartwatch',
        'home' => 'robot vacuum',
    ];

    /**
     * Fetch product images from the Unsplash search API.
     *
     * The API key has a low demo rate limit, so results are cached to
     * storage/app/seeders/unsplash_images.json and reused on re-seed.
     *
     * @return array<string, string> product slug => image URL
     */
    public function run(): array
    {
        $apiKey = (string) config('services.unsplash.key');

        if ($apiKey === '') {
            $this->command?->warn('UNSPLASH_API_KEY is not set, skipping product images.');

            return [];
        }

        $catalog = json_decode(
            (string) file_get_contents(__DIR__.'/data/catalog.json'),
            true
        );

        $cache = [];
        if (Storage::exists(self::CACHE_PATH)) {
            $cache = json_decode((string) Storage::get(self::CACHE_PATH), true) ?: [];
        }

        $images = [];

        foreach ($catalog['products'] as $row) {
            $slug = $row['slug'];

            if (isset($cache[$slug])) {
                $images[$slug] = $cache[$slug];

                continue;
            }

            $query = $this->buildQuery($row);

            try {
                $response = Http::timeout(15)
                    ->get('https://api.unsplash.com/search/photos', [
                        'query' => $query,
                        'per_page' => 3,
                        'orientation' => 'landscape',
                        'client_id' => $apiKey,
                    ]);

                if ($response->failed()) {
                    $this->command?->warn("Unsplash HTTP {$response->status()} for \"{$query}\"");

                    continue;
                }

                $url = $response->json('results.0.urls.regular');

                if (\is_string($url) && $url !== '') {
                    $url .= '&auto=format&fit=crop&w=800&q=70';
                    $cache[$slug] = $url;
                    $images[$slug] = $url;
                }
            } catch (\Throwable $e) {
                $this->command?->warn("Unsplash request failed for \"{$query}\": {$e->getMessage()}");

                continue;
            }
        }

        if ($cache !== []) {
            Storage::put(self::CACHE_PATH, json_encode($cache, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        return $images;
    }

    /**
     * Build an English Unsplash search query, for example:
     * "Смартфон Apple iPhone 16 Pro 256GB Natural Titanium" -> "apple iphone 16 pro smartphone".
     *
     * @param  array<string, mixed>  $row
     */
    private function buildQuery(array $row): string
    {
        $latin = preg_replace('/[^A-Za-z0-9 ]/u', ' ', $row['title']) ?? '';
        $latin = preg_replace('/\s+/', ' ', trim($latin)) ?? '';
        // Drop storage sizes like "256GB" so queries stay short
        $latin = preg_replace('/\b\d+(GB|TB)\b/i', '', $latin) ?? '';

        $keyword = self::CATEGORY_KEYWORDS[$row['category']] ?? '';

        return mb_strtolower(trim($row['brand'].' '.$latin.' '.$keyword));
    }
}
