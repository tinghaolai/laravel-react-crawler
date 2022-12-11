<?php

use App\Http\Controllers\Crawler\CrawlerController;
use Illuminate\Support\Facades\Route;

Route::prefix('crawler')->controller(CrawlerController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/', 'index');
    Route::get('{id}', 'show');
});
