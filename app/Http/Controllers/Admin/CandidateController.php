<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Candidate::withCount(['jobApplications', 'documents', 'appraisals']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Bio data completion filter
        if ($request->filled('bio_data_completed') && $request->string('bio_data_completed') !== 'all') {
            $query->where('bio_data_completed', $request->boolean('bio_data_completed'));
        }

        $totalCandidatesCount = Candidate::count();
        $bioDataCompletedCount = Candidate::where('bio_data_completed', true)->count();
        $bioDataIncompleteCount = Candidate::where('bio_data_completed', false)->count();
        $filteredCandidatesCount = $query->count();

        $candidates = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Check if shared templates exist so we can show a quick status/badge.
        $hasTemplates = DocumentTemplate::whereIn('document_type', ['offer_letter', 'contract'])->exists();

        return view('admin.candidates.index', compact(
            'candidates',
            'totalCandidatesCount',
            'bioDataCompletedCount',
            'bioDataIncompleteCount',
            'filteredCandidatesCount',
            'hasTemplates'
        ));
    }

    public function show(Candidate $candidate): View
    {
        $candidate->load([
            'jobApplications.jobPost',
            'documents',
            'appraisals'
        ]);

        // Decode bio data JSON
        $bioData = $candidate->bio_data ? json_decode($candidate->bio_data, true) : [];

        // Get application statistics
        $stats = [
            'total' => $candidate->jobApplications->count(),
            'pending' => $candidate->jobApplications->where('status', 'pending')->count(),
            'sieving_passed' => $candidate->jobApplications->where('status', 'sieving_passed')->count(),
            'sieving_rejected' => $candidate->jobApplications->where('status', 'sieving_rejected')->count(),
            'aptitude_failed' => $candidate->jobApplications->where('status', 'aptitude_failed')->count(),
            'stage_2_passed' => $candidate->jobApplications->where('status', 'stage_2_passed')->count(),
            'hired' => $candidate->jobApplications->where('status', 'hired')->count(),
        ];

        return view('admin.candidates.show', compact('candidate', 'bioData', 'stats'));
    }
}
