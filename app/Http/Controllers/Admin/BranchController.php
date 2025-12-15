<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Branch::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address_line1', 'like', "%{$search}%")
                  ->orWhere('address_line2', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('phone_primary', 'like', "%{$search}%")
                  ->orWhere('phone_secondary', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $totalBranchesCount = Branch::count();
        $activeBranchesCount = Branch::where('is_active', true)->count();
        $hiddenBranchesCount = Branch::where('is_active', false)->count();
        $filteredBranchesCount = $query->count();

        $branches = $query->orderBy('display_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.branches.index', compact('branches', 'totalBranchesCount', 'activeBranchesCount', 'hiddenBranchesCount', 'filteredBranchesCount'));
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

