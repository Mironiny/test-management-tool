<?php

namespace App\Providers;

use app\Services\TestLibraryService;
use Illuminate\Support\ServiceProvider;

class TestLibraryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TestLibraryService::class, function($app){
            return new TestLibraryService();
        });
    }
}
