<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\ReadingSession;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingSessionController extends Controller
{
    public function heartbeat(Request $request, Novel $novel, RecommendationService $recommendations): JsonResponse
    {
        $validated = $request->validate([
            'session_uuid' => ['required', 'uuid'],
            'ended' => ['sometimes', 'boolean'],
        ]);

        $user = $request->user();
        $now = now();

        DB::transaction(function () use ($validated, $novel, $user, $now) {
            // Serialize writes for the per-user, per-novel row.
            $user->newQuery()->whereKey($user->id)->lockForUpdate()->first();

            $chapterSessions = ReadingSession::query()
                ->where('user_id', $user->id)
                ->where('novel_id', $novel->id)
                ->lockForUpdate()
                ->orderBy('id')
                ->get();

            $session = $chapterSessions->first();

            if (! $session) {
                if (! empty($validated['ended'])) {
                    return;
                }

                ReadingSession::create([
                    'session_uuid' => $validated['session_uuid'],
                    'user_id' => $user->id,
                    'novel_id' => $novel->id,
                    'started_at' => $now,
                    'last_active_at' => $now,
                ]);

                return;
            }

            // Collapse any legacy duplicate rows for this user/novel into one.
            if ($chapterSessions->count() > 1) {
                $session->active_seconds = $chapterSessions->sum('active_seconds');
                $session->started_at = $chapterSessions->min('started_at');
                $session->last_active_at = $chapterSessions->max('last_active_at');
                $session->ended_at = $chapterSessions->max('ended_at');
                $session->save();
                $chapterSessions->skip(1)->each->delete();
            }

            $elapsed = max(0, $session->last_active_at->diffInSeconds($now, false));
            if ($elapsed <= 90) {
                $session->active_seconds += min($elapsed, 30);
            }

            $session->last_active_at = $now;
            $session->ended_at = ! empty($validated['ended']) ? $now : null;
            $session->save();
        });

        if (! empty($validated['ended'])) {
            $recommendations->forgetForUser($user->id);
        }

        return response()->json(['ok' => true]);
    }
}
