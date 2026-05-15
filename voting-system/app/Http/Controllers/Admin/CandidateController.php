<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\ActivityLog;
use App\Models\Candidate;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with('category')
            ->orderByDesc('vote_count')
            ->paginate(20);

        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.candidates.create', compact('categories'));
    }

    public function store(StoreCandidateRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('candidates', 'public');
        }

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        $data['status'] = $request->boolean('status', true);

        Candidate::create($data);

        ActivityLog::log('candidate_created', "Created candidate: {$data['name']}");

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate created successfully.');
    }

    public function edit(Candidate $candidate)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.candidates.edit', compact('candidate', 'categories'));
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }
            $data['image'] = $request->file('image')->store('candidates', 'public');
        }

        $data['status'] = $request->boolean('status', true);

        $candidate->update($data);

        ActivityLog::log('candidate_updated', "Updated candidate: {$data['name']}");

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $name = $candidate->name;

        if ($candidate->image) {
            Storage::disk('public')->delete($candidate->image);
        }

        $candidate->delete();

        ActivityLog::log('candidate_deleted', "Deleted candidate: {$name}");

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }
}
