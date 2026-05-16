<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(
        protected LeaderboardService $leaderboardService,
    ) {}

    /**
     * Display the leaderboard page.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->orderBy('sort_order')->get();
        $selectedCategory = $request->query('category');

        if ($selectedCategory) {
            $category = Category::where('slug', $selectedCategory)->firstOrFail();
            $leaderboard = $this->leaderboardService->getCategoryLeaderboard($category);
        } else {
            $leaderboard = $this->leaderboardService->getGlobalLeaderboard();
            $category = null;
        }

        return view('leaderboard.index', compact('leaderboard', 'categories', 'category'));
    }

    /**
     * API: Get leaderboard data for AJAX polling.
     */
    public function apiGlobal()
    {
        $leaderboard = $this->leaderboardService->getGlobalLeaderboard();
        return response()->json($leaderboard);
    }

    /**
     * API: Get category leaderboard data for AJAX polling.
     */
    public function apiCategory(Category $category)
    {
        $leaderboard = $this->leaderboardService->getCategoryLeaderboard($category);
        return response()->json($leaderboard);
    }
}
