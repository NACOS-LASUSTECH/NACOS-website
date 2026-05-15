<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Services\LeaderboardService;

class HomeController extends Controller
{
    public function __invoke(LeaderboardService $leaderboardService)
    {
        $categories = Category::active()
            ->withCount('candidates')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $topCandidates = $leaderboardService->getTopCandidates(8);

        $eventDate = Setting::getEventDate();
        $siteTitle = Setting::getSiteTitle();
        $votingEnabled = Setting::isVotingEnabled();

        return view('home', compact(
            'categories',
            'topCandidates',
            'eventDate',
            'siteTitle',
            'votingEnabled'
        ));
    }
}
