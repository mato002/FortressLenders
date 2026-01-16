<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateAppraisal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppraisalController extends Controller
{
    /**
     * Display all appraisals.
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        $appraisals = $candidate->appraisals()
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Group by type
        $performanceReviews = $candidate->appraisals()
            ->where('type', 'performance_review')
            ->with('createdBy')
            ->orderBy('review_date', 'desc')
            ->get();

        $hrCommunications = $candidate->appraisals()
            ->where('type', 'hr_communication')
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->get();

        $warnings = $candidate->appraisals()
            ->where('type', 'warning')
            ->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('candidate.appraisals.index', compact(
            'candidate',
            'appraisals',
            'performanceReviews',
            'hrCommunications',
            'warnings'
        ));
    }

    /**
     * Show a specific appraisal.
     */
    public function show(CandidateAppraisal $appraisal)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate || $appraisal->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized.');
        }

        $appraisal->load('createdBy');

        return view('candidate.appraisals.show', compact('appraisal'));
    }

    /**
     * Acknowledge an appraisal.
     */
    public function acknowledge(CandidateAppraisal $appraisal)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate || $appraisal->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized.');
        }

        $appraisal->is_acknowledged = true;
        $appraisal->acknowledged_at = now();
        $appraisal->save();

        return redirect()->route('candidate.appraisals.show', $appraisal)
            ->with('success', 'Appraisal acknowledged.');
    }
}
