@extends('layouts.admin')
@section('title', 'Candidates')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold">Manage Candidates</h2>
    <a href="{{ route('admin.candidates.create') }}" class="px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition text-sm">+ Add Candidate</a>
</div>

<div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-50 dark:bg-surface-900 text-sm text-surface-500">
                    <th class="px-6 py-3 text-left">Candidate</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-center">Votes</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $candidate)
                <tr class="border-t border-surface-100 dark:border-surface-700 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ substr($candidate->name, 0, 1) }}
                            </div>
                            <span class="font-semibold text-sm">{{ $candidate->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-surface-500">{{ $candidate->category->name }}</td>
                    <td class="px-6 py-4 text-center font-bold text-primary-600 dark:text-primary-400">{{ number_format($candidate->vote_count) }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $candidate->status ? 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300' : 'bg-red-100 text-red-700' }}">
                            {{ $candidate->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.candidates.edit', $candidate) }}" class="px-3 py-1.5 bg-surface-100 dark:bg-surface-700 rounded-lg text-xs font-medium hover:bg-surface-200 transition">Edit</a>
                            <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" onsubmit="return confirm('Delete this candidate?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-xs font-medium hover:bg-red-200 transition">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
        {{ $candidates->links() }}
    </div>
</div>
@endsection
