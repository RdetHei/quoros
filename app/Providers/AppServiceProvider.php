<?php

namespace App\Providers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\Review;
use App\Policies\ChapterPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NovelPolicy;
use App\Policies\ReviewPolicy;
use App\Observers\ChapterObserver;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Novel::class, NovelPolicy::class);
        Gate::policy(Chapter::class, ChapterPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);

        Chapter::observe(ChapterObserver::class);
    }
}
