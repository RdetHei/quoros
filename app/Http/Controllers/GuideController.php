<?php

namespace App\Http\Controllers;

use App\Models\GuideArticle;
use App\Models\GuideCategory;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $categories = GuideCategory::with(['articles' => function ($query) {
            $query->where('is_published', true)->orderBy('order');
        }])->orderBy('order')->get();

        return view('guides.index', compact('categories'));
    }

    public function category(GuideCategory $category)
    {
        $articles = $category->articles()->where('is_published', true)->orderBy('order')->get();
        return view('guides.category', compact('category', 'articles'));
    }

    public function show(GuideCategory $category, GuideArticle $article)
    {
        if (!$article->is_published) {
            abort(404);
        }

        // Ensure the article belongs to the category
        if ($article->guide_category_id !== $category->id) {
            return redirect()->route('guides.show', [$article->category, $article]);
        }

        return view('guides.show', compact('category', 'article'));
    }
}
