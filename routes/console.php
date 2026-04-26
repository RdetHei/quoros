<?php

use App\Models\Chapter;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Chapter::where('status', 'scheduled')
        ->where('published_at', '<=', now())
        ->update(['status' => 'published']);
})->everyMinute();
