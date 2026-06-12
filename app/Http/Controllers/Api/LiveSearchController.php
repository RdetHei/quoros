<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $novels = Novel::with(['author', 'genres'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('alternative_title', 'like', "%{$query}%")
                  ->orWhereHas('author', function ($q2) use ($query) {
                      $q2->where('name', 'like', "%{$query}%");
                  });
            })
            ->select(['id', 'title', 'alternative_title', 'slug', 'cover_image', 'rating_avg', 'author_id', 'status', 'type'])
            ->limit(6)
            ->get()
            ->map(function ($novel) {
                return [
                    'id'                => $novel->id,
                    'title'             => $novel->title,
                    'alternative_title' => $novel->alternative_title,
                    'slug'              => $novel->slug,
                    'cover_image'       => $novel->cover_image_url ?: ($novel->cover_image
                        ? asset('storage/' . $novel->cover_image)
                        : null),
                    'rating_avg'        => number_format($novel->rating_avg, 1),
                    'author'            => $novel->author->name ?? '-',
                    'status'            => $novel->status,
                    'type'              => str_replace('_', ' ', $novel->type),
                    'genres'            => $novel->genres->take(2)->pluck('name'),
                    'url'               => route('novels.show', $novel->slug),
                ];
            });

        return response()->json($novels);
    }

    public function details(Novel $novel)
    {
        $novel->load(['author', 'genres']);
        
        return response()->json([
            'id' => $novel->id,
            'title' => $novel->title,
            'description' => Str::limit(strip_tags($novel->description ?? ''), 200),
            'author_name' => $novel->author->name ?? 'Unknown',
            'cover_image_url' => $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null),
            'rating_avg' => number_format($novel->rating_avg, 1),
            'view_count' => number_format($novel->view_count),
            'bookmarks_count' => number_format($novel->bookmarks_count ?? 0),
            'genres' => $novel->genres->map(fn($g) => ['id' => $g->id, 'name' => $g->name]),
        ]);
    }
}