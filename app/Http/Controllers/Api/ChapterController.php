<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function latest()
    {
        $chapter = Chapter::with('novel')->latest()->first();

        if (!$chapter) {
            return response()->json(['message' => 'No chapters found'], 404);
        }

        return new ChapterResource($chapter);
    }
}
