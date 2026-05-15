@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold">{{ number_format($stats['total_votes']) }}</p>
        <p class="text-sm text-surface-500 dark:text-surface-400">Total Votes</p>
    </div>

    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold">₦{{ number_format($stats['total_revenue'] / 100, 2) }}</p>
        <p class="text-sm text-surface-500 dark:text-surface-400">Total Revenue</p>
    </div>

    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold">{{ $stats['total_candidates'] }}</p>
        <p class="text-sm text-surface-500 dark:text-surface-400">Candidates</p>
    </div>

    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold">{{ $stats['pending_transfers'] }}</p>
        <p class="text-sm text-surface-500 dark:text-surface-400">Pending Transfers</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <h3 class="font-bold text-lg mb-4">Votes per Day</h3>
        <canvas id="votesChart" height="200"></canvas>
    </div>
    <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm">
        <h3 class="font-bold text-lg mb-4">Votes by Category</h3>
        <canvas id="categoryChart" height="200"></canvas>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
        <h3 class="font-bold text-lg">Recent Transactions</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-primary-600 dark:text-primary-400 font-medium hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-50 dark:bg-surface-900 text-sm text-surface-500">
                    <th class="px-6 py-3 text-left">Reference</th>
                    <th class="px-6 py-3 text-left">Voter</th>
                    <th class="px-6 py-3 text-left">Candidate</th>
                    <th class="px-6 py-3 text-center">Votes</th>
                    <th class="px-6 py-3 text-right">Amount</th>
                    <th class="px-6 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $txn)
                <tr class="border-t border-surface-100 dark:border-surface-700">
                    <td class="px-6 py-3 text-sm font-mono">{{ Str::limit($txn->reference, 18) }}</td>
                    <td class="px-6 py-3 text-sm">{{ $txn->voter_name }}</td>
                    <td class="px-6 py-3 text-sm">{{ $txn->candidate->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-sm text-center font-bold">{{ $txn->votes }}</td>
                    <td class="px-6 py-3 text-sm text-right font-medium">{{ $txn->formatted_amount }}</td>
                    <td class="px-6 py-3 text-center">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $txn->payment_status === 'success' ? 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300' : ($txn->payment_status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                            {{ ucfirst($txn->payment_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Votes per Day chart
    const votesCtx = document.getElementById('votesChart').getContext('2d');
    new Chart(votesCtx, {
        type: 'line',
        data: {
            labels: @json($votesPerDay['labels']),
            datasets: [{
                label: 'Votes',
                data: @json($votesPerDay['data']),
                borderColor: '#24795b',
                backgroundColor: 'rgba(36, 121, 91, 0.12)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Votes by Category chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: @json($votesByCategory['labels']),
            datasets: [{
                data: @json($votesByCategory['data']),
                backgroundColor: ['#24795b', '#2f8f6e', '#66c1a3', '#cfeee0', '#94a3b8', '#0f172a'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@endpush
