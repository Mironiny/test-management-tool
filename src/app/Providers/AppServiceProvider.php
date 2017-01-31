<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\Project;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $projects = Project::all();
        View::share('projects', $projects);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
