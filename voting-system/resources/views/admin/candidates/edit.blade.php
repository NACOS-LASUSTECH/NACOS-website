@extends('layouts.admin')
@section('title', 'Edit Candidate')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm p-6">
        <h3 class="text-lg font-bold mb-6">Edit: {{ $candidate->name }}</h3>

        <form method="POST" action="{{ route('admin.candidates.update', $candidate) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $candidate->name) }}" required class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 focus:ring-2 focus:ring-primary-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Category</label>
                <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 focus:ring-2 focus:ring-primary-500 transition">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $candidate->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Photo</label>
                @if($candidate->image)
                    <img src="{{ $candidate->image_url }}" alt="{{ $candidate->name }}" class="w-20 h-20 rounded-xl object-cover mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-100 file:text-primary-700 transition">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Bio</label>
                <textarea name="bio" rows="3" class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 focus:ring-2 focus:ring-primary-500 transition">{{ old('bio', $candidate->bio) }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="status" value="1" {{ $candidate->status ? 'checked' : '' }} class="rounded text-primary-600 focus:ring-primary-500">
                <label class="text-sm font-medium">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Update Candidate</button>
                <a href="{{ route('admin.candidates.index') }}" class="px-6 py-3 border border-surface-300 dark:border-surface-600 rounded-xl font-medium hover:bg-surface-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
