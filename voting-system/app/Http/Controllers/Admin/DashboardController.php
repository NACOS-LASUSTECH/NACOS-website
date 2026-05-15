<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __invoke(AnalyticsService $analyticsService)
    {
        $stats = $analyticsService->getDashboardStats();
        $recentTransactions = $analyticsService->getRecentTransactions(10);
        $votesPerDay = $analyticsService->getVotesPerDay(30);
        $revenuePerDay = $analyticsService->getRevenuePerDay(30);
        $votesByCategory = $analyticsService->getVotesByCategory();

        return view('admin.dashboard', compact(
            'stats',
            'recentTransactions',
            'votesPerDay',
            'revenuePerDay',
            'votesByCategory'
        ));
    }
}
