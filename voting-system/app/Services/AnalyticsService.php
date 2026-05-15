<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Category;
use App\Models\VoteTransaction;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get dashboard summary statistics.
     */
    public function getDashboardStats(): array
    {
        return [
            'total_votes' => Candidate::sum('vote_count'),
            'total_revenue' => VoteTransaction::successful()->sum('amount'),
            'total_candidates' => Candidate::count(),
            'total_categories' => Category::count(),
            'pending_transfers' => VoteTransaction::bankTransfers()->pending()->count(),
            'total_transactions' => VoteTransaction::count(),
            'successful_transactions' => VoteTransaction::successful()->count(),
        ];
    }

    /**
     * Get revenue formatted in Naira.
     */
    public function getFormattedRevenue(): string
    {
        $total = VoteTransaction::successful()->sum('amount');
        return '₦' . number_format($total / 100, 2);
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions(int $limit = 10)
    {
        return VoteTransaction::with('candidate.category')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get votes per day for chart (last 30 days).
     */
    public function getVotesPerDay(int $days = 30): array
    {
        $results = VoteTransaction::successful()
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(votes) as total_votes')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $results->pluck('date')->toArray(),
            'data' => $results->pluck('total_votes')->toArray(),
        ];
    }

    /**
     * Get revenue per day for chart (last 30 days).
     */
    public function getRevenuePerDay(int $days = 30): array
    {
        $results = VoteTransaction::successful()
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $results->pluck('date')->toArray(),
            'data' => $results->pluck('total_revenue')->map(fn($v) => $v / 100)->toArray(),
        ];
    }

    /**
     * Get votes distribution by category for pie chart.
     */
    public function getVotesByCategory(): array
    {
        $categories = Category::withSum('candidates', 'vote_count')
            ->orderByDesc('candidates_sum_vote_count')
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('candidates_sum_vote_count')->toArray(),
        ];
    }

    /**
     * Get payment method distribution.
     */
    public function getPaymentMethodDistribution(): array
    {
        $results = VoteTransaction::successful()
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        return [
            'labels' => $results->pluck('payment_method')->toArray(),
            'data' => $results->pluck('count')->toArray(),
        ];
    }

    /**
     * Export transactions as CSV data.
     */
    public function getTransactionsForExport(): array
    {
        return VoteTransaction::with('candidate.category')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($t) {
                return [
                    'Reference' => $t->reference,
                    'Voter Name' => $t->voter_name,
                    'Voter Email' => $t->voter_email,
                    'Candidate' => $t->candidate->name ?? 'N/A',
                    'Category' => $t->candidate->category->name ?? 'N/A',
                    'Votes' => $t->votes,
                    'Amount (₦)' => $t->amount / 100,
                    'Payment Method' => $t->payment_method,
                    'Status' => $t->payment_status,
                    'Date' => $t->created_at->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();
    }
}
