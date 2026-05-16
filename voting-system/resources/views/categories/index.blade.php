@extends('layouts.app')
@section('title', 'Award Categories')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Award Categories</h1>
        <p class="text-surface-500 dark:text-surface-400 text-lg">Browse all categories and vote for your favorites</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="group bg-white dark:bg-surface-800 rounded-2xl shadow-sm hover:shadow-xl border border-surface-200 dark:border-surface-700 overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="h-44 bg-gradient-to-br from-primary-500 to-primary-700 relative overflow-hidden">
                @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                @endif
                <div class="absolute bottom-3 right-3 bg-white/20 backdrop-blur-sm text-white text-xs font-medium px-3 py-1 rounded-full border border-white/30">
                    {{ $category->candidates_count }} Candidates
                </div>
            </div>
            <div class="p-5">
                <h3 class="font-bold text-lg mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $category->name }}</h3>
                <p class="text-surface-500 dark:text-surface-400 text-sm line-clamp-2">{{ $category->description }}</p>
                <div class="mt-4 flex items-center gap-2 text-sm font-medium text-primary-600 dark:text-primary-400">
                    <span>View Candidates</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
