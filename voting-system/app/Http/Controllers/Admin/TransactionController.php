<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\VoteTransaction;
use App\Services\AnalyticsService;
use App\Services\VotingService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = VoteTransaction::with('candidate.category')->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }
        if ($request->filled('category')) {
            $query->whereHas('candidate.category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('voter_name', 'like', "%{$search}%")
                    ->orWhere('voter_email', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.transactions.index', compact('transactions', 'categories'));
    }

    public function show(VoteTransaction $transaction)
    {
        $transaction->load('candidate.category');
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Approve a bank transfer payment.
     */
    public function approve(VoteTransaction $transaction, VotingService $votingService)
    {
        if ($transaction->payment_method !== 'bank_transfer') {
            return back()->with('error', 'Only bank transfer payments can be manually approved.');
        }

        if ($transaction->isProcessed()) {
            return back()->with('error', 'This transaction has already been processed.');
        }

        $votingService->processSuccessfulPayment($transaction);

        ActivityLog::log('transaction_approved', "Approved transaction: {$transaction->reference}");

        return back()->with('success', 'Transaction approved. Votes have been added.');
    }

    /**
     * Reject a bank transfer payment.
     */
    public function reject(VoteTransaction $transaction, VotingService $votingService)
    {
        if ($transaction->isProcessed()) {
            return back()->with('error', 'This transaction has already been processed.');
        }

        $votingService->rejectTransaction($transaction);

        ActivityLog::log('transaction_rejected', "Rejected transaction: {$transaction->reference}");

        return back()->with('success', 'Transaction rejected.');
    }

    /**
     * Export transactions as CSV.
     */
    public function export(AnalyticsService $analyticsService)
    {
        $data = $analyticsService->getTransactionsForExport();

        $filename = 'transactions_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
            }

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
