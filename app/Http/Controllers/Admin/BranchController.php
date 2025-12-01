<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.branches.index', compact('branches'));
    }

    public function create(): View
    {
        $branch = new Branch([
            'accent_color' => 'teal',
            'display_order' => 0,
            'is_active' => true,
        ]);

        return view('admin.branches.create', compact('branch'));
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        Branch::create($request->validated());

        return redirect()
            ->route('admin.branches.index')
            ->with('status', 'Branch created successfully.');
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $branch->update($request->validated());

        return redirect()
            ->route('admin.branches.index')
            ->with('status', 'Branch updated.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with('status', 'Branch removed.');
    }
}

