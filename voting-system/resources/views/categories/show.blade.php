@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Breadcrumb --}}
    <nav class="mb-8 flex items-center gap-2 text-sm text-surface-500">
        <a href="{{ route('categories.index') }}" class="hover:text-primary-600 transition">Categories</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-surface-900 dark:text-surface-100 font-medium">{{ $category->name }}</span>
    </nav>

    {{-- Category Header --}}
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-3xl p-8 md:p-12 text-white mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute -top-20 -right-20 w-60 h-60 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative">
            <h1 class="text-3xl md:text-4xl font-bold mb-3">{{ $category->name }}</h1>
            <p class="text-primary-100 text-lg max-w-2xl">{{ $category->description }}</p>
            <div class="flex items-center gap-6 mt-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-semibold">{{ $category->candidates->count() }} Candidates</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="font-semibold">{{ number_format($category->total_votes) }} Total Votes</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidates Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($category->candidates as $index => $candidate)
        <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="relative h-56">
                @if($candidate->image)
                    <img src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-surface-200 to-surface-300 dark:from-surface-700 dark:to-surface-600 flex items-center justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($candidate->name, 0, 1) }}
                        </div>
                    </div>
                @endif

                {{-- Rank Badge --}}
                <div class="absolute top-3 left-3 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-lg
                    {{ $index === 0 ? 'bg-primary-200 text-primary-800' : ($index === 1 ? 'bg-primary-100 text-primary-700' : ($index === 2 ? 'bg-primary-300 text-primary-900' : 'bg-surface-800/70 text-white backdrop-blur-sm')) }}">
                    #{{ $index + 1 }}
                </div>
            </div>

            <div class="p-5">
                <h3 class="font-bold text-lg mb-1">{{ $candidate->name }}</h3>
                <p class="text-surface-500 dark:text-surface-400 text-sm line-clamp-2 mb-4">{{ $candidate->bio }}</p>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold text-lg text-primary-600 dark:text-primary-400">{{ number_format($candidate->vote_count) }}</span>
                            <span class="text-xs text-surface-400 ml-1">votes</span>
                        </div>
                    </div>

                    <a href="{{ route('candidates.show', $candidate) }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-all hover:-translate-y-0.5">
                        Vote
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
