<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display all active categories.
     */
    public function index()
    {
        $categories = Category::active()
            ->withCount('candidates')
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display a single category with its candidates.
     */
    public function show(Category $category)
    {
        $category->load(['candidates' => function ($query) {
            $query->active()->orderByDesc('vote_count');
        }]);

        return view('categories.show', compact('category'));
    }
}
