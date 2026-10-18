<?php

/*
|--------------------------------------------------------------------------
| CORS — дозволяємо Astro-фронтенсу ходити в наш JSON API
|--------------------------------------------------------------------------
| По замовчуванню (без цього файлу) жоден origin не дозволений.
| Для продакшн-домену Astro додаємо його строкою нижче.
*/

return [
    'paths' => ['api/*', 'up'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        '*',
        // 'https://tech-store-omega-ten.vercel.app',
    ],

    'allowed_origins_patterns' => [
        '#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => false,
];
