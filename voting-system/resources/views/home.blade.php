@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', 'Vote for your favorite candidates in the NACOS Awards. Support your peers and make your voice count!')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-[#f7f3ee] text-surface-900">
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-white/70 to-transparent"></div>
        <div class="absolute left-[-10%] top-24 h-[28rem] w-[28rem] rounded-full bg-primary-500/10 blur-3xl"></div>
        <div class="absolute right-[-8%] top-10 h-[24rem] w-[24rem] rounded-full bg-emerald-300/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-[18rem] w-[48rem] -translate-x-1/2 rounded-full bg-white/70 blur-3xl"></div>
        <div class="hero-grid absolute inset-0 opacity-40"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 md:py-24 lg:py-32">
        <div class="grid items-center gap-14 lg:grid-cols-[minmax(0,1.05fr)_minmax(420px,0.95fr)] lg:gap-16">
            <div class="max-w-3xl">
            <div class="hero-fade-up mb-8 inline-flex items-center gap-2 rounded-full border border-primary-600/10 bg-primary-500/10 px-4 py-2 text-sm font-medium text-primary-800 backdrop-blur-md shadow-sm" style="animation-delay: 0.05s;">
                <span class="w-2 h-2 bg-primary-400 rounded-full animate-pulse"></span>
                Voting is {{ $votingEnabled ? 'Live Now' : 'Coming Soon' }}
            </div>

            <h1 class="hero-fade-up max-w-4xl text-5xl font-black tracking-[-0.06em] text-surface-900 sm:text-6xl lg:text-[5.5rem] lg:leading-[0.96] mb-6" style="animation-delay: 0.12s;">
                {{ $siteTitle }}
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-primary-600 via-primary-500 to-emerald-400">Vote. Support. Celebrate.</span>
            </h1>

            <p class="hero-fade-up max-w-2xl text-lg leading-8 text-surface-500 md:text-xl mb-10" style="animation-delay: 0.2s;">
                Cast your votes for the most outstanding students. Every vote counts, show your support and make your voice heard!
            </p>

            <div class="hero-fade-up flex flex-col items-start gap-4 sm:flex-row sm:flex-wrap mb-12" style="animation-delay: 0.28s;">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 rounded-full bg-[#16A34A] px-8 py-4 text-lg font-semibold text-white shadow-lg shadow-primary-600/20 transition-all duration-300 hover:-translate-y-1 hover:bg-primary-700 hover:shadow-xl hover:shadow-primary-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    Start Voting
                </a>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 rounded-full border border-surface-900/10 bg-white/75 px-8 py-4 text-lg font-semibold text-surface-800 shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:bg-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M3 3v18h18" />
                        <path d="M18 17V9" />
                        <path d="M13 17V5" />
                        <path d="M8 17v-3" />
                    </svg>
                    View Leaderboard
                </a>
            </div>

        {{-- Countdown Timer --}}
        @if($eventDate)
        <div class="hero-fade-up" style="animation-delay: 0.36s;" x-data="countdownTimer('{{ $eventDate }}')" x-init="startTimer()">
            <p class="mb-5 text-sm font-semibold uppercase tracking-[0.28em] text-surface-400">Event Countdown</p>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <div class="text-left">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-surface-900/10 bg-white/80 backdrop-blur-xl shadow-soft-xl sm:h-24 sm:w-24">
                        <span class="text-3xl font-black tracking-tight text-surface-900 sm:text-4xl" x-text="days">00</span>
                    </div>
                    <p class="mt-3 text-xs font-medium uppercase tracking-[0.2em] text-surface-400">Days</p>
                </div>
                <span class="hidden text-3xl font-bold text-surface-300 sm:block">:</span>
                <div class="text-left">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-surface-900/10 bg-white/80 backdrop-blur-xl shadow-soft-xl sm:h-24 sm:w-24">
                        <span class="text-3xl font-black tracking-tight text-surface-900 sm:text-4xl" x-text="hours">00</span>
                    </div>
                    <p class="mt-3 text-xs font-medium uppercase tracking-[0.2em] text-surface-400">Hours</p>
                </div>
                <span class="hidden text-3xl font-bold text-surface-300 sm:block">:</span>
                <div class="text-left">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-surface-900/10 bg-white/80 backdrop-blur-xl shadow-soft-xl sm:h-24 sm:w-24">
                        <span class="text-3xl font-black tracking-tight text-surface-900 sm:text-4xl" x-text="minutes">00</span>
                    </div>
                    <p class="mt-3 text-xs font-medium uppercase tracking-[0.2em] text-surface-400">Minutes</p>
                </div>
                <span class="hidden text-3xl font-bold text-surface-300 sm:block">:</span>
                <div class="text-left">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-surface-900/10 bg-white/80 backdrop-blur-xl shadow-soft-xl sm:h-24 sm:w-24">
                        <span class="text-3xl font-black tracking-tight text-surface-900 sm:text-4xl" x-text="seconds">00</span>
                    </div>
                    <p class="mt-3 text-xs font-medium uppercase tracking-[0.2em] text-surface-400">Seconds</p>
                </div>
            </div>
        </div>
        @endif
            </div>

            <div class="relative lg:pl-4">
                <div class="hero-float relative mx-auto max-w-[36rem]">
                    <div class="absolute -top-10 right-0 hidden rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl sm:block">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Activity</p>
                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">2,500+ Votes Cast</p>
                    </div>

                    <div class="absolute -left-4 top-1/3 hidden rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl md:block">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Reach</p>
                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">30+ Categories</p>
                    </div>

                    <div class="absolute -bottom-8 right-8 hidden rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl sm:block">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Trust</p>
                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">Secure Voting</p>
                    </div>

                    <div class="relative overflow-hidden rounded-[2rem] border border-surface-900/10 bg-white/80 p-4 shadow-soft-2xl backdrop-blur-2xl sm:p-6">
                        <div class="absolute inset-x-10 top-0 h-24 rounded-full bg-primary-500/10 blur-3xl"></div>
                        <div class="relative overflow-hidden rounded-[1.6rem] border border-surface-900/10 bg-gradient-to-b from-white to-[#f4efe8] p-4 shadow-inner sm:p-5">
                            <div class="mb-5 flex items-center justify-between rounded-2xl border border-surface-900/10 bg-white/80 px-4 py-3 shadow-sm">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Award categories</p>
                                    <p class="mt-1 text-lg font-bold text-surface-900">Voting dashboard</p>
                                </div>
                                <div class="rounded-full bg-primary-500/10 px-3 py-1 text-sm font-semibold text-primary-700">Live</div>
                            </div>

                            <div class="space-y-3">
                                <div class="rounded-3xl border border-surface-900/10 bg-white/90 p-4 shadow-sm">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold text-surface-400">Category</p>
                                            <p class="mt-1 text-lg font-bold text-surface-900">Most Outstanding Student</p>
                                        </div>
                                        <span class="rounded-full bg-primary-500/10 px-3 py-1 text-xs font-semibold text-primary-700">Active</span>
                                    </div>
                                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-2xl border border-surface-900/10 bg-[#fcfaf7] p-4">
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-surface-400">Nominee card</p>
                                            <p class="mt-2 text-base font-bold text-surface-900">Candidate Profile</p>
                                            <div class="mt-4 flex items-center justify-between">
                                                <span class="text-sm text-surface-500">Vote button</span>
                                                <span class="rounded-full bg-primary-600 px-3 py-1 text-xs font-semibold text-white">Vote</span>
                                            </div>
                                        </div>
                                        <div class="rounded-2xl border border-surface-900/10 bg-[#fcfaf7] p-4">
                                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-surface-400">Success badge</p>
                                            <p class="mt-2 text-base font-bold text-surface-900">Payment confirmed</p>
                                            <div class="mt-4 flex items-center gap-2 text-sm font-medium text-primary-700">
                                                <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                                                Vote recorded
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl border border-surface-900/10 bg-white/90 p-4 shadow-sm">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-surface-400">Statistics</p>
                                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">98%</p>
                                        <p class="mt-1 text-sm text-surface-500">Completion</p>
                                    </div>
                                    <div class="rounded-2xl border border-surface-900/10 bg-white/90 p-4 shadow-sm">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-surface-400">Categories</p>
                                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">30+</p>
                                        <p class="mt-1 text-sm text-surface-500">Available</p>
                                    </div>
                                    <div class="rounded-2xl border border-surface-900/10 bg-white/90 p-4 shadow-sm">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-surface-400">Security</p>
                                        <p class="mt-2 text-2xl font-black tracking-tight text-surface-900">24/7</p>
                                        <p class="mt-1 text-sm text-surface-500">Monitored</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-float-delayed mt-8 grid gap-3 sm:grid-cols-3 lg:hidden">
                    <div class="rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Activity</p>
                        <p class="mt-2 text-xl font-black tracking-tight text-surface-900">2,500+ Votes Cast</p>
                    </div>
                    <div class="rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Reach</p>
                        <p class="mt-2 text-xl font-black tracking-tight text-surface-900">30+ Categories</p>
                    </div>
                    <div class="rounded-3xl border border-surface-900/10 bg-white/90 px-5 py-4 shadow-soft-xl backdrop-blur-xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-surface-400">Trust</p>
                        <p class="mt-2 text-xl font-black tracking-tight text-surface-900">Secure Voting</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Categories --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Award Categories</h2>
        <p class="text-surface-500 dark:text-surface-400 text-lg max-w-2xl mx-auto">Browse through the categories and vote for your favorites</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="group relative bg-white dark:bg-surface-800 rounded-2xl shadow-sm hover:shadow-xl border border-surface-200 dark:border-surface-700 overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="h-40 bg-gradient-to-br from-primary-500 to-primary-700 relative overflow-hidden">
                @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                @endif
            </div>
            <div class="p-5">
                <h3 class="font-bold text-lg mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $category->name }}</h3>
                <p class="text-surface-500 dark:text-surface-400 text-sm line-clamp-2 mb-3">{{ $category->description }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-3 py-1 rounded-full">{{ $category->candidates_count }} Candidates</span>
                    <svg class="w-5 h-5 text-surface-400 group-hover:text-primary-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="text-center mt-10">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400 font-semibold rounded-xl hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-all">
            View All Categories
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

{{-- Top Candidates --}}
<section class="bg-surface-100 dark:bg-surface-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 inline-flex items-center gap-2">
                <svg class="w-6 h-6 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M8 21h8" />
                    <path d="M12 17v4" />
                    <path d="M7 4h10" />
                    <path d="M17 4v5a5 5 0 0 1-10 0V4" />
                    <path d="M5 9a3 3 0 0 1-3-3V4h4" />
                    <path d="M19 9a3 3 0 0 0 3-3V4h-4" />
                </svg>
                Top Candidates
            </h2>
            <p class="text-surface-500 dark:text-surface-400 text-lg">The leading candidates across all categories</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($topCandidates as $index => $candidate)
            <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 animate-fade-in" style="animation-delay: {{ $index * 100 }}ms">
                <div class="relative">
                    <div class="h-48 bg-gradient-to-br from-surface-200 to-surface-300 dark:from-surface-700 dark:to-surface-600">
                        @if($candidate->image)
                            <img src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($candidate->name, 0, 1) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($index < 3)
                    <div class="absolute top-3 left-3 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-lg
                        {{ $index === 0 ? 'bg-amber-400 text-amber-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : 'bg-amber-600 text-amber-100') }}">
                        {{ $index + 1 }}
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <a href="{{ route('candidates.show', $candidate) }}" class="font-bold text-base hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $candidate->name }}</a>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">{{ $candidate->category->name }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold text-primary-600 dark:text-primary-400">{{ number_format($candidate->vote_count) }}</span>
                            <span class="text-xs text-surface-400">votes</span>
                        </div>
                        <a href="{{ route('candidates.show', $candidate) }}" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline">Vote →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">How to Vote</h2>
        <p class="text-surface-500 dark:text-surface-400 text-lg">Simple steps to cast your vote</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        @php
            $steps = [
                ['icon' => 'search', 'title' => 'Choose Category', 'desc' => 'Browse award categories and find your favorite'],
                ['icon' => 'user', 'title' => 'Pick Candidate', 'desc' => 'Select the candidate you want to support'],
                ['icon' => 'check-square', 'title' => 'Select Votes', 'desc' => 'Choose how many votes you want to cast'],
                ['icon' => 'credit-card', 'title' => 'Pay & Vote', 'desc' => 'Pay online or via bank transfer to confirm'],
            ];
        @endphp

        @foreach($steps as $index => $step)
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/40 rounded-2xl flex items-center justify-center shadow-sm">
                @switch($step['icon'])
                    @case('search')
                        <svg class="w-7 h-7 text-primary-700 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        @break
                    @case('user')
                        <svg class="w-7 h-7 text-primary-700 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        @break
                    @case('check-square')
                        <svg class="w-7 h-7 text-primary-700 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M9 11l2 2 4-4" />
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                        </svg>
                        @break
                    @case('credit-card')
                        <svg class="w-7 h-7 text-primary-700 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2" />
                            <path d="M2 10h20" />
                        </svg>
                        @break
                @endswitch
            </div>
            <div class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-2">Step {{ $index + 1 }}</div>
            <h3 class="font-bold text-lg mb-2">{{ $step['title'] }}</h3>
            <p class="text-surface-500 dark:text-surface-400 text-sm">{{ $step['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection

@push('scripts')
<script>
    function countdownTimer(targetDate) {
        return {
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            interval: null,
            startTimer() {
                this.updateTimer(targetDate);
                this.interval = setInterval(() => this.updateTimer(targetDate), 1000);
            },
            updateTimer(target) {
                const now = new Date().getTime();
                const end = new Date(target).getTime();
                const diff = end - now;

                if (diff <= 0) {
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    clearInterval(this.interval);
                    return;
                }

                this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }
    }
</script>
@endpush
