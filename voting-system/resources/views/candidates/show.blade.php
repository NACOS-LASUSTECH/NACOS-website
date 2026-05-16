@extends('layouts.app')

@section('title', 'Vote for ' . $candidate->name . ' — ' . $candidate->category->name)
@section('meta_description', strip_tags($candidate->bio) ?? 'Vote for ' . $candidate->name . ' in the ' . $candidate->category->name . ' category at the NACOS Awards.')

@section('canonical_url', $candidate->campaign_url)
@section('og_type', 'profile')
@section('og_title', 'Vote for ' . $candidate->name . ' — ' . $candidate->category->name)
@section('og_description', strip_tags($candidate->bio) ?? 'Show your support for ' . $candidate->name . ' at the NACOS Awards.')
@section('og_image', $candidate->absolute_image_url)
@section('og_url', $candidate->campaign_url)

@section('twitter_card', 'summary_large_image')

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
  "@type": "ProfilePage",
  "mainEntity": {
    "@type": "Person",
    "name": "{{ $candidate->name }}",
    "description": "{{ strip_tags($candidate->bio) }}",
    "image": "{{ $candidate->absolute_image_url }}"
  }
}
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" x-data="campaignPage()">
    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-surface-500 overflow-x-auto whitespace-nowrap pb-2">
        <a href="{{ route('categories.index') }}" class="hover:text-primary-600 transition">Categories</a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('categories.show', $candidate->category) }}" class="hover:text-primary-600 transition">{{ $candidate->category->name }}</a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-surface-900 dark:text-surface-100 font-medium">{{ $candidate->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Candidate Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="h-64 md:h-96 bg-gradient-to-br from-surface-200 to-surface-300 dark:from-surface-700 dark:to-surface-600 relative group">
                    @if($candidate->image)
                        <img src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="w-32 h-32 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-5xl font-bold">
                                {{ substr($candidate->name, 0, 1) }}
                            </div>
                        </div>
                    @endif

                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <div class="px-4 py-2 bg-white/30 dark:bg-black/30 backdrop-blur-md rounded-xl text-white font-bold border border-white/20 shadow-lg inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path d="M8 21h8" />
                                <path d="M12 17v4" />
                                <path d="M7 4h10" />
                                <path d="M17 4v5a5 5 0 0 1-10 0V4" />
                                <path d="M5 9a3 3 0 0 1-3-3V4h4" />
                                <path d="M19 9a3 3 0 0 0 3-3V4h-4" />
                            </svg>
                            Rank #{{ $rank }} <span class="text-sm font-normal opacity-80">of {{ $totalInCategory }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black mb-2">{{ $candidate->name }}</h1>
                            <a href="{{ route('categories.show', $candidate->category) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-medium hover:bg-primary-100 dark:hover:bg-primary-900/50 transition">
                                {{ $candidate->category->name }}
                            </a>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-center md:text-right">
                                <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-800">{{ number_format($candidate->vote_count) }}</div>
                                <div class="text-xs font-semibold text-surface-400 uppercase tracking-wider">Total Votes</div>
                            </div>
                        </div>
                    </div>

                    @if($candidate->bio)
                    <div class="prose dark:prose-invert max-w-none mb-8">
                        <h3 class="text-lg font-bold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Manifesto / Bio
                        </h3>
                        <p class="text-surface-600 dark:text-surface-300 leading-relaxed text-lg">{{ $candidate->bio }}</p>
                    </div>
                    @endif

                    {{-- Social Sharing --}}
                    <div class="bg-surface-50 dark:bg-surface-900 rounded-2xl p-6 border border-surface-200 dark:border-surface-700">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <h3 class="font-bold text-lg inline-flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M5 12l7-7 7 7" />
                                        <path d="M12 19V5" />
                                    </svg>
                                    Help {{ explode(' ', $candidate->name)[0] }} Win
                                </h3>
                                <p class="text-sm text-surface-500">Share this campaign to get more votes.</p>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-surface-500 font-medium">
                                <div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> {{ number_format($candidate->page_views) }} views</div>
                                <div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg> {{ number_format($candidate->share_count) }} shares</div>
                            </div>
                        </div>
                        @include('candidates.partials.share-buttons')
                    </div>
                </div>
            </div>

            {{-- Recent Supporters --}}
            @if($recentSupporters->count() > 0)
            <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-6">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Recent Supporters
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($recentSupporters as $supporter)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-50 dark:bg-surface-700/50">
                        <div class="w-10 h-10 bg-gradient-to-br from-surface-200 to-surface-300 dark:from-surface-600 dark:to-surface-500 rounded-full flex items-center justify-center font-bold text-surface-600 dark:text-surface-300">
                            {{ substr($supporter->voter_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $supporter->voter_name }}</p>
                            <p class="text-xs text-surface-500">Voted {{ $supporter->votes }} times • {{ $supporter->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column: Voting & Actions --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Countdown Timer --}}
            @if($eventDate)
            <div class="bg-gradient-to-br from-surface-900 to-black text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary-500 rounded-full blur-3xl opacity-30"></div>
                <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-primary-500 rounded-full blur-3xl opacity-30"></div>
                <div class="relative z-10 text-center">
                    <p class="text-sm font-medium text-surface-400 uppercase tracking-widest mb-3">Voting Closes In</p>
                    <div class="flex justify-center gap-3" x-data="countdown('{{ $eventDate }}')">
                        <div class="flex flex-col items-center">
                            <span class="text-3xl font-black tabular-nums" x-text="days">00</span>
                            <span class="text-[10px] text-surface-400 uppercase font-bold tracking-wider">Days</span>
                        </div>
                        <span class="text-2xl font-black text-surface-600 mt-1">:</span>
                        <div class="flex flex-col items-center">
                            <span class="text-3xl font-black tabular-nums" x-text="hours">00</span>
                            <span class="text-[10px] text-surface-400 uppercase font-bold tracking-wider">Hrs</span>
                        </div>
                        <span class="text-2xl font-black text-surface-600 mt-1">:</span>
                        <div class="flex flex-col items-center">
                            <span class="text-3xl font-black tabular-nums" x-text="minutes">00</span>
                            <span class="text-[10px] text-surface-400 uppercase font-bold tracking-wider">Min</span>
                        </div>
                        <span class="text-2xl font-black text-surface-600 mt-1">:</span>
                        <div class="flex flex-col items-center">
                            <span class="text-3xl font-black tabular-nums text-primary-400" x-text="seconds">00</span>
                            <span class="text-[10px] text-surface-400 uppercase font-bold tracking-wider">Sec</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Vote Form --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-xl border border-surface-200 dark:border-surface-700 p-6 sticky top-24" id="vote-section">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">Cast Your Vote</h3>
                    <p class="text-sm text-surface-500 mt-1">Push {{ explode(' ', $candidate->name)[0] }} to the top!</p>
                </div>

                @if(!$votingEnabled)
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-surface-100 dark:bg-surface-700 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <p class="font-bold text-lg">Voting is Closed</p>
                    <p class="text-sm text-surface-500 mt-1">Check back later or view the leaderboard.</p>
                </div>
                @else
                <form method="POST" action="{{ route('vote.store') }}">
                    @csrf
                    <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">

                    <div class="space-y-5">
                        {{-- Quick Packages --}}
                        <div>
                            <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-3">Quick Packages</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($votePackages as $pkg)
                                <button type="button" @click="votes = {{ $pkg['votes'] }}" class="px-3 py-2 border-2 rounded-xl text-left transition-all" :class="votes === {{ $pkg['votes'] }} ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 shadow-md' : 'border-surface-200 dark:border-surface-700 hover:border-primary-300 dark:hover:border-primary-700'">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold">{{ $pkg['votes'] }}</span>
                                        <span>{{ $pkg['icon'] }}</span>
                                    </div>
                                    <div class="text-[10px] text-surface-500 uppercase font-semibold">{{ $pkg['label'] }}</div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-surface-100 dark:border-surface-700">
                            <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-3">Custom Amount</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decrementVotes()" class="w-14 h-14 rounded-2xl bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 flex items-center justify-center font-bold text-2xl transition shadow-sm">−</button>
                                <div class="flex-1 relative">
                                    <input type="number" name="votes" x-model="votes" min="1" max="10000" required class="w-full text-center px-4 py-4 rounded-2xl border-2 border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 focus:border-primary-500 focus:ring-0 font-black text-2xl transition shadow-inner">
                                    <div class="absolute bottom-1 w-full text-center text-[10px] font-bold text-surface-400 uppercase tracking-widest">Votes</div>
                                </div>
                                <button type="button" @click="incrementVotes()" class="w-14 h-14 rounded-2xl bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 flex items-center justify-center font-bold text-2xl transition shadow-sm">+</button>
                            </div>
                            @error('votes')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-2xl p-4 text-white shadow-lg transform transition-transform hover:scale-[1.02]">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-white/80">Total Required</span>
                                <span class="text-3xl font-black" x-text="'₦' + totalAmount.toLocaleString()"></span>
                            </div>
                            <p class="text-xs text-white/60 mt-1">₦{{ number_format($votePrice, 0) }} per vote</p>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <input type="text" name="voter_name" required value="{{ old('voter_name') }}" class="w-full px-4 py-3.5 rounded-xl border-2 border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-800 focus:border-primary-500 focus:ring-0 transition font-medium" placeholder="Your Name">
                                @error('voter_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <input type="email" name="voter_email" required value="{{ old('voter_email') }}" class="w-full px-4 py-3.5 rounded-xl border-2 border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-800 focus:border-primary-500 focus:ring-0 transition font-medium" placeholder="Your Email Address">
                                @error('voter_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-3">Pay With</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all text-center" :class="paymentMethod === '{{ $paymentProvider }}' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400' : 'border-surface-200 dark:border-surface-700 hover:border-surface-300'">
                                    <input type="radio" name="payment_method" value="{{ $paymentProvider }}" x-model="paymentMethod" class="sr-only">
                                    <svg class="w-6 h-6 text-primary-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <path d="M2 10h20" />
                                    </svg>
                                    <span class="font-bold text-sm">{{ $paymentProviderLabel }}</span>
                                    <span class="text-[10px] text-surface-500">Card/USSD</span>
                                    <div x-show="paymentMethod === '{{ $paymentProvider }}'" class="absolute -top-2 -right-2 w-5 h-5 bg-primary-500 rounded-full text-white flex items-center justify-center border-2 border-white dark:border-surface-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="m5 13 4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                                <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all text-center" :class="paymentMethod === 'bank_transfer' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400' : 'border-surface-200 dark:border-surface-700 hover:border-surface-300'">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="sr-only">
                                    <svg class="w-6 h-6 text-primary-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M3 21h18" />
                                        <path d="M5 21V9" />
                                        <path d="M9 21V9" />
                                        <path d="M15 21V9" />
                                        <path d="M19 21V9" />
                                        <path d="M2 9l10-6 10 6" />
                                    </svg>
                                    <span class="font-bold text-sm">Transfer</span>
                                    <span class="text-[10px] text-surface-500">Manual</span>
                                    <div x-show="paymentMethod === 'bank_transfer'" class="absolute -top-2 -right-2 w-5 h-5 bg-primary-500 rounded-full text-white flex items-center justify-center border-2 border-white dark:border-surface-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="m5 13 4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-primary-700 hover:bg-primary-800 text-white font-black rounded-xl shadow-xl hover:shadow-2xl transition-all hover:-translate-y-1 text-lg flex items-center justify-center gap-2 group">
                            Submit <span x-text="votes"></span> Votes
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
                @endif
            </div>

            {{-- QR Code Card --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 text-center">
                <h4 class="font-bold mb-2">Campaign QR Code</h4>
                <p class="text-xs text-surface-500 mb-4">Scan to visit this page directly</p>
                <div class="bg-white p-4 rounded-xl inline-block shadow-inner border border-surface-100">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($shareUrl) }}&color=0f172a" alt="QR Code" class="w-32 h-32 mx-auto">
                </div>
                <button @click="window.open('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode($shareUrl) }}&color=0f172a')" class="mt-4 text-sm font-semibold text-primary-600 hover:underline">Download High-Res QR</button>
            </div>
        </div>
    </div>

    {{-- Floating Action Button (Mobile Only) --}}
    <div class="fixed bottom-6 right-6 lg:hidden z-40">
        <a href="#vote-section" class="w-14 h-14 bg-gradient-to-r from-primary-600 to-primary-800 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function campaignPage() {
        return {
            votes: 1,
            votePrice: {{ $votePrice }},
            paymentMethod: '{{ $paymentProvider }}',
            linkCopied: false,
            get totalAmount() {
                return this.votes * this.votePrice;
            },
            incrementVotes() {
                if (this.votes < 10000) this.votes++;
            },
            decrementVotes() {
                if (this.votes > 1) this.votes--;
            },
            copyLink() {
                navigator.clipboard.writeText('{{ $shareUrl }}');
                this.linkCopied = true;
                setTimeout(() => this.linkCopied = false, 2000);
            },
            trackShare() {
                fetch('{{ route("api.candidate.share", $candidate) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).catch(console.error);
            }
        }
    }

    function countdown(endDate) {
        return {
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00',
            timer: null,
            init() {
                this.updateTimer();
                this.timer = setInterval(() => this.updateTimer(), 1000);
            },
            updateTimer() {
                const target = new Date(endDate).getTime();
                const now = new Date().getTime();
                const distance = target - now;

                if (distance < 0) {
                    clearInterval(this.timer);
                    return;
                }

                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }
    }
</script>
@endpush
