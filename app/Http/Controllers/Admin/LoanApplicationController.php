<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = LoanApplication::query()
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status'))
            )
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'pending' => LoanApplication::where('status', 'pending')->count(),
            'in_review' => LoanApplication::where('status', 'in_review')->count(),
            'approved' => LoanApplication::where('status', 'approved')->count(),
            'rejected' => LoanApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.loan-applications.index', compact('applications', 'statusCounts'));
    }

    public function show(LoanApplication $loanApplication): View
    {
        return view('admin.loan-applications.show', compact('loanApplication'));
    }

    public function update(Request $request, LoanApplication $loanApplication): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,in_review,approved,rejected'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $loanApplication->fill($data);

        if (in_array($data['status'], ['approved', 'rejected'], true) && ! $loanApplication->handled_at) {
            $loanApplication->handled_at = now();
        } elseif (! in_array($data['status'], ['approved', 'rejected'], true)) {
            $loanApplication->handled_at = null;
        }

        $loanApplication->save();

        return back()->with('status', 'Application updated successfully.');
    }

    public function destroy(LoanApplication $loanApplication): RedirectResponse
    {
        $loanApplication->delete();

        return redirect()
            ->route('admin.loan-applications.index')
            ->with('status', 'Application deleted.');
    }
}



