@extends('layouts.app')
@section('title', 'Payment Successful')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-8">
        <div class="w-20 h-20 mx-auto mb-6 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>

        <h1 class="text-2xl font-bold text-primary-700 dark:text-primary-300 mb-2 inline-flex items-center gap-2">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="m9 12 2 2 4-4" />
            </svg>
            Vote Successful
        </h1>
        <p class="text-surface-500 dark:text-surface-400 mb-6">Your votes have been recorded successfully.</p>

        <div class="bg-surface-50 dark:bg-surface-700 rounded-xl p-4 mb-6 text-left space-y-2">
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Reference</span>
                <span class="text-sm font-mono font-medium">{{ $transaction->reference }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Candidate</span>
                <span class="text-sm font-medium">{{ $transaction->candidate->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Category</span>
                <span class="text-sm font-medium">{{ $transaction->candidate->category->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Votes</span>
                <span class="text-sm font-bold text-primary-600">{{ $transaction->votes }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Amount</span>
                <span class="text-sm font-bold">{{ $transaction->formatted_amount }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('candidates.show', $transaction->candidate) }}" class="btn-primary">
                Vote Again
            </a>
            <a href="{{ route('leaderboard') }}" class="px-6 py-3 border-2 border-surface-300 dark:border-surface-600 font-semibold rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-all">
                View Leaderboard
            </a>
        </div>
    </div>
</div>
@endsection
