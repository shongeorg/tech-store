<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Публічний JSON API ТЕХНОПРОСТІР (споживається Astro-фронтендом)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('health', function () {
        return response()->json([
            'ok' => true,
            'service' => 'technoprostir-api',
            'version' => 'v1',
            'time' => now()->toIso8601String(),
        ]);
    })->name('health');

    Route::get('stats', [CatalogController::class, 'stats'])->name('stats');
    Route::get('categories', [CatalogController::class, 'categories'])->name('categories');
    Route::get('products', [CatalogController::class, 'products'])->name('products');
    Route::get('products/{slug}', [CatalogController::class, 'show'])->name('products.show');
});
