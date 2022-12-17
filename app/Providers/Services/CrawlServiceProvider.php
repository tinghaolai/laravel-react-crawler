<?php

namespace App\Providers\Services;

use App\Models\Crawler;
use App\Repositories\CrawlRepository;
use App\Repositories\Interfaces\CrawlRepositoryInterface;
use App\Services\Crawler\CrawlerService;
use App\Services\Crawler\Interfaces\CrawlServiceInterface;
use Illuminate\Support\ServiceProvider;

class CrawlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CrawlServiceInterface::class, function ($app) {
            return new CrawlerService($app->make(CrawlRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
