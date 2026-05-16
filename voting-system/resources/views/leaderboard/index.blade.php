@extends('layouts.app')
@section('title', 'Leaderboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="leaderboard()">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 inline-flex items-center gap-2">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path d="M8 21h8" />
                <path d="M12 17v4" />
                <path d="M7 4h10" />
                <path d="M17 4v5a5 5 0 0 1-10 0V4" />
                <path d="M5 9a3 3 0 0 1-3-3V4h4" />
                <path d="M19 9a3 3 0 0 0 3-3V4h-4" />
            </svg>
            Live Leaderboard
        </h1>
        <p class="text-surface-500 dark:text-surface-400 text-lg">Rankings update every 5 seconds</p>
        <div class="flex items-center justify-center gap-2 mt-3">
            <span class="w-2 h-2 bg-primary-400 rounded-full animate-pulse"></span>
            <span class="text-sm text-primary-600 dark:text-primary-400 font-medium">Live</span>
        </div>
    </div>

    {{-- Category Tabs --}}
    <div class="flex flex-wrap items-center justify-center gap-2 mb-8">
        <button @click="switchCategory('')" :class="selectedCategory === '' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-700'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:-translate-y-0.5">
            All Categories
        </button>
        @foreach($categories as $cat)
        <button @click="switchCategory('{{ $cat->slug }}')" :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-primary-600 text-white shadow-lg' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-700'" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:-translate-y-0.5">
            {{ $cat->name }}
        </button>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="max-w-md mx-auto mb-8">
        <div class="relative">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="searchQuery" placeholder="Search candidates..." class="w-full pl-12 pr-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
        </div>
    </div>

    {{-- Leaderboard Table --}}
    <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900 text-surface-500 dark:text-surface-400 text-sm">
                        <th class="px-6 py-4 text-left font-semibold">Rank</th>
                        <th class="px-6 py-4 text-left font-semibold">Candidate</th>
                        <th class="px-6 py-4 text-left font-semibold hidden md:table-cell">Category</th>
                        <th class="px-6 py-4 text-right font-semibold">Votes</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in filteredLeaderboard" :key="item.id">
                        <tr class="border-t border-surface-100 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors"
                            :class="{
                                'bg-primary-50/60 dark:bg-primary-900/15': item.rank === 1,
                                'bg-primary-50/30 dark:bg-primary-900/10': item.rank === 2 || item.rank === 3
                            }">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold"
                                     :class="{
                                        'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300': item.rank === 1,
                                        'bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-300': item.rank === 2,
                                        'bg-primary-100/70 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300': item.rank === 3,
                                        'bg-surface-100 text-surface-500 dark:bg-surface-700 dark:text-surface-400': item.rank > 3
                                     }">
                                    <span x-text="item.rank"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" x-text="item.name.charAt(0)"></div>
                                    <div>
                                        <a :href="'/candidates/' + item.slug" class="font-semibold hover:text-primary-600 dark:hover:text-primary-400 transition" x-text="item.name"></a>
                                        <p class="text-xs text-surface-400 md:hidden" x-text="item.category"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="text-sm text-surface-500 dark:text-surface-400" x-text="item.category"></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-lg text-primary-600 dark:text-primary-400" x-text="Number(item.vote_count).toLocaleString()"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <template x-if="filteredLeaderboard.length === 0">
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-surface-300 dark:text-surface-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-surface-500 dark:text-surface-400">No candidates found</p>
            </div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
function leaderboard() {
    return {
        leaderboardData: @json($leaderboard),
        selectedCategory: '{{ $category ? $category->slug : '' }}',
        searchQuery: '',
        polling: null,

        init() {
            this.startPolling();
        },

        get filteredLeaderboard() {
            if (!this.searchQuery) return this.leaderboardData;
            const q = this.searchQuery.toLowerCase();
            return this.leaderboardData.filter(item =>
                item.name.toLowerCase().includes(q) ||
                item.category.toLowerCase().includes(q)
            );
        },

        switchCategory(slug) {
            this.selectedCategory = slug;
            this.fetchLeaderboard();
        },

        async fetchLeaderboard() {
            try {
                const url = this.selectedCategory
                    ? `/api/leaderboard/${this.selectedCategory}`
                    : '/api/leaderboard';
                const response = await fetch(url);
                if (response.ok) {
                    this.leaderboardData = await response.json();
                }
            } catch (error) {
                console.error('Failed to fetch leaderboard:', error);
            }
        },

        startPolling() {
            this.polling = setInterval(() => this.fetchLeaderboard(), 5000);
        },

        destroy() {
            clearInterval(this.polling);
        }
    }
}
</script>
@endpush
