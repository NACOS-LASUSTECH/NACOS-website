<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('candidates')
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        Category::create($data);

        ActivityLog::log('category_created', "Created category: {$data['name']}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);

        $category->update($data);

        ActivityLog::log('category_updated', "Updated category: {$data['name']}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $name = $category->name;

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        ActivityLog::log('category_deleted', "Deleted category: {$name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
