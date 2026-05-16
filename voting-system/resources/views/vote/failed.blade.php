@extends('layouts.app')
@section('title', 'Payment Failed')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-8">
        <div class="w-20 h-20 mx-auto mb-6 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>

        <h1 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-2 inline-flex items-center gap-2">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M15 9l-6 6" />
                <path d="M9 9l6 6" />
            </svg>
            Payment Failed
        </h1>
        <p class="text-surface-500 dark:text-surface-400 mb-6">Your payment could not be verified. Please try again.</p>

        <div class="flex flex-col gap-3">
            <a href="{{ route('categories.index') }}" class="btn-primary">
                Try Again
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 border-2 border-surface-300 dark:border-surface-600 font-semibold rounded-xl hover:bg-surface-50 dark:hover:bg-surface-700 transition-all">
                Go Home
            </a>
        </div>
    </div>
</div>
@endsection
