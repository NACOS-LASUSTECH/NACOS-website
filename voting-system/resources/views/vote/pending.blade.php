@extends('layouts.app')
@section('title', 'Payment Pending')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-8">
        <div class="w-20 h-20 mx-auto mb-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>

        <h1 class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-2 inline-flex items-center gap-2">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 6v6l4 2" />
            </svg>
            Payment Pending
        </h1>
        <p class="text-surface-500 dark:text-surface-400 mb-6">Your receipt has been uploaded. Our team will verify it shortly.</p>

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
                <span class="text-sm text-surface-500">Votes</span>
                <span class="text-sm font-bold">{{ $transaction->votes }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-surface-500">Status</span>
                <span class="text-sm font-medium text-amber-600">Awaiting Verification</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-primary">
            Back to Home
        </a>
    </div>
</div>
@endsection
