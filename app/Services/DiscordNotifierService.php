<?php

namespace App\Services;

use App\Models\Chapter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Reserved for post-production Discord integration. Not wired into the app yet.
 */
class DiscordNotifierService
{
    /**
     * Send notification to Discord bot when a new chapter is created.
     *
     * @param Chapter $chapter
     * @return void
     */
    public function sendNewChapterNotification(Chapter $chapter): void
    {
        $botUrl = env('DISCORD_BOT_URL');
        $secretToken = env('DISCORD_SECRET_TOKEN');

        if (!$botUrl || !$secretToken) {
            Log::warning('Discord notification skipped: DISCORD_BOT_URL or DISCORD_SECRET_TOKEN not set.');
            return;
        }

        $payload = [
            'title' => $chapter->novel->title . ' - ' . $chapter->title,
            'summary' => $chapter->summary ?: 'Chapter baru telah rilis!',
            'url' => url('/novels/' . $chapter->novel->slug . '/read/' . $chapter->slug),
        ];

        try {
            $response = Http::withToken($secretToken)
                ->post($botUrl, $payload);

            if ($response->successful()) {
                Log::info('Discord notification sent successfully for chapter: ' . $chapter->id);
            } else {
                Log::error('Failed to send Discord notification. Status: ' . $response->status() . ' Body: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error sending Discord notification: ' . $e->getMessage());
        }
    }
}
