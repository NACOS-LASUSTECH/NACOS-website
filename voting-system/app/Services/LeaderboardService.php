<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Category;
use Illuminate\Support\Collection;

class LeaderboardService
{
    /**
     * Get global leaderboard — all candidates ranked by votes.
     */
    public function getGlobalLeaderboard(int $limit = 50): Collection
    {
        return Candidate::with('category')
            ->active()
            ->orderByDesc('vote_count')
            ->limit($limit)
            ->get()
            ->map(function ($candidate, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'slug' => $candidate->slug,
                    'image_url' => $candidate->image_url,
                    'category' => $candidate->category->name,
                    'category_slug' => $candidate->category->slug,
                    'vote_count' => $candidate->vote_count,
                ];
            });
    }

    /**
     * Get category-specific leaderboard.
     */
    public function getCategoryLeaderboard(Category $category, int $limit = 50): Collection
    {
        return $category->candidates()
            ->active()
            ->orderByDesc('vote_count')
            ->limit($limit)
            ->get()
            ->map(function ($candidate, $index) use ($category) {
                return [
                    'rank' => $index + 1,
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'slug' => $candidate->slug,
                    'image_url' => $candidate->image_url,
                    'category' => $category->name,
                    'category_slug' => $category->slug,
                    'vote_count' => $candidate->vote_count,
                ];
            });
    }

    /**
     * Get top candidates across all categories (for homepage).
     */
    public function getTopCandidates(int $limit = 10): Collection
    {
        return Candidate::with('category')
            ->active()
            ->orderByDesc('vote_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top candidates per category.
     */
    public function getTopCandidatesPerCategory(int $perCategory = 3): Collection
    {
        $categories = Category::active()
            ->with(['candidates' => function ($query) use ($perCategory) {
                $query->active()->orderByDesc('vote_count')->limit($perCategory);
            }])
            ->get();

        return $categories;
    }
}
