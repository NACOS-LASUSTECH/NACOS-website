@extends('layouts.admin')
@section('title', 'Create Category')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm p-6">
        <h3 class="text-lg font-bold mb-6">Create New Category</h3>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Category Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-surface-300 dark:border-surface-600 bg-surface-50 dark:bg-surface-700 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-100 file:text-primary-700 transition">
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="status" value="1" checked class="rounded text-primary-600 focus:ring-primary-500">
                <label class="text-sm font-medium">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Create Category</button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 border border-surface-300 dark:border-surface-600 rounded-xl font-medium hover:bg-surface-50 dark:hover:bg-surface-700 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
