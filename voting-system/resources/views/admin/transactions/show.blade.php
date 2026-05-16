@extends('layouts.admin')
@section('title', 'Transaction Details')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold">Transaction Details</h3>
            <span class="inline-block px-3 py-1.5 rounded-full text-sm font-semibold {{ $transaction->payment_status === 'success' ? 'bg-primary-100 text-primary-800' : ($transaction->payment_status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($transaction->payment_status) }}
            </span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-surface-400 mb-1">Reference</p><p class="font-mono font-medium">{{ $transaction->reference }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Date</p><p class="font-medium">{{ $transaction->created_at->format('M d, Y H:i') }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Voter</p><p class="font-medium">{{ $transaction->voter_name }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Email</p><p class="font-medium">{{ $transaction->voter_email }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Candidate</p><p class="font-medium">{{ $transaction->candidate->name }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Category</p><p class="font-medium">{{ $transaction->candidate->category->name }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Votes</p><p class="text-lg font-bold text-primary-600">{{ $transaction->votes }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Amount</p><p class="text-lg font-bold">{{ $transaction->formatted_amount }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Method</p><p class="font-medium">{{ $transaction->payment_method_label }}</p></div>
            <div><p class="text-xs text-surface-400 mb-1">Verification</p><p class="font-medium">{{ ucfirst($transaction->verification_status) }}</p></div>
        </div>
        @if($transaction->receipt_image)
        <div class="mt-4 pt-4 border-t border-surface-200 dark:border-surface-700">
            <p class="text-xs text-surface-400 mb-2">Payment Receipt</p>
            <img src="{{ $transaction->receipt_url }}" alt="Receipt" class="max-w-sm rounded-xl border shadow-sm">
        </div>
        @endif
    </div>

    @if($transaction->payment_method === 'bank_transfer' && !$transaction->isProcessed())
    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm p-6">
        <h4 class="font-bold mb-4">Actions</h4>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.transactions.approve', $transaction) }}" onsubmit="return confirm('Approve?')">
                @csrf
                <button class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="m5 13 4 4L19 7" />
                    </svg>
                    Approve
                </button>
            </form>
            <form method="POST" action="{{ route('admin.transactions.reject', $transaction) }}" onsubmit="return confirm('Reject?')">
                @csrf
                <button class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M18 6L6 18" />
                        <path d="M6 6l12 12" />
                    </svg>
                    Reject
                </button>
            </form>
        </div>
    </div>
    @endif
    <div class="mt-4"><a href="{{ route('admin.transactions.index') }}" class="text-sm text-primary-600 hover:underline">← Back</a></div>
</div>
@endsection
