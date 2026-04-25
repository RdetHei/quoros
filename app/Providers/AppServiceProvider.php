<?php

namespace App\Providers;

use App\Models\Chapter;
use App\Observers\ChapterObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Chapter::observe(ChapterObserver::class);
    }
}
