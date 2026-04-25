<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Services\DiscordNotifierService;

class ChapterObserver
{
    protected $discordNotifier;

    public function __construct(DiscordNotifierService $discordNotifier)
    {
        $this->discordNotifier = $discordNotifier;
    }

    /**
     * Handle the Chapter "created" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function created(Chapter $chapter)
    {
        // Pastikan relasi novel dimuat untuk mendapatkan judul dan slug
        $chapter->load('novel');
        
        $this->discordNotifier->sendNewChapterNotification($chapter);
    }
}
