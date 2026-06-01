<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    public function index(Request $request)
    {
        $featuredNovels = Novel::where('is_featured', true)->with('author')->get();
        
        $search = $request->get('search');
        $availableNovels = Novel::where('is_featured', false)
            ->when($search, function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->with('author')
            ->latest()
            ->paginate(10);

        return view('admin.carousel.index', compact('featuredNovels', 'availableNovels', 'search'));
    }

    public function toggle(Novel $novel)
    {
        $novel->is_featured = !$novel->is_featured;
        $novel->save();

        $status = $novel->is_featured ? 'added to' : 'removed from';
        return back()->with('success', "Novel \"{$novel->title}\" successfully {$status} carousel banner.");
    }
}
