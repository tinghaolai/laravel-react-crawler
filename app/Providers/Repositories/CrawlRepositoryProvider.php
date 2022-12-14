<?php

namespace App\Providers\Repositories;

use App\Models\Crawler;
use App\Observers\CrawObserver;
use App\Profiles\CrawlSingleUrlProfile;
use App\Repositories\CrawlRepository;
use App\Repositories\Interfaces\CrawlRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CrawlRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CrawlRepositoryInterface::class, function ($app) {
            return new CrawlRepository(
                new Crawler(),
                new CrawlSingleUrlProfile(),
                new CrawObserver()
            );
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
