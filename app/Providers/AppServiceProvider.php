<?php

namespace App\Providers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\InAppNotification;
use App\Models\Novel;
use App\Models\Review;
use App\Observers\ChapterNotificationObserver;
use App\Policies\ChapterPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NovelPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Gate::policy(Novel::class, NovelPolicy::class);
        Gate::policy(Chapter::class, ChapterPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);

        Chapter::observe(ChapterNotificationObserver::class);

        View::composer(['layouts.app', 'partials.notification-bell'], function ($view) {
            if (! Auth::check()) {
                return;
            }

            $userId = Auth::id();

            $view->with([
                'unreadNotificationsCount' => InAppNotification::query()
                    ->where('user_id', $userId)
                    ->whereNull('read_at')
                    ->count(),
                'recentNotifications' => InAppNotification::query()
                    ->where('user_id', $userId)
                    ->latest()
                    ->limit(8)
                    ->get(),
            ]);
        });

        RateLimiter::for('chapter-read', function (Request $request) {
            $user = $request->user();

            if ($user && $user->role === 'admin') {
                return Limit::perMinute(600)->by('chapter-admin-'.$user->id);
            }

            $novel = $request->route('novel');
            if ($user && $novel instanceof Novel && (int) $novel->author_id === (int) $user->id) {
                return Limit::perMinute(600)->by('chapter-author-'.$user->id);
            }

            $perMinute = max(5, min(200, (int) env('CHAPTER_READ_PER_MINUTE', 45)));

            return Limit::perMinute($perMinute)->by($request->ip());
        });
    }
}
