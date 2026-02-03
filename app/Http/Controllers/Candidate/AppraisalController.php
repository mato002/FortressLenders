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
    public function index(Request $request)
    {
        $candidate = current_portal_candidate();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        // Base query
        $baseQuery = $candidate->appraisals()->with('createdBy');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $baseQuery->where('type', $request->string('type'));
        }

        // Status filter (acknowledged/pending)
        if ($request->filled('status')) {
            if ($request->string('status') === 'acknowledged') {
                $baseQuery->where('is_acknowledged', true);
            } elseif ($request->string('status') === 'pending') {
                $baseQuery->where('is_acknowledged', false);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date('date_to'));
        }

        // Sort
        $sortBy = $request->string('sort_by', 'created_at');
        $sortOrder = $request->string('sort_order', 'desc');
        $baseQuery->orderBy($sortBy, $sortOrder);

        $appraisals = $baseQuery->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => $candidate->appraisals()->count(),
            'performance_reviews' => $candidate->appraisals()->where('type', 'performance_review')->count(),
            'hr_communications' => $candidate->appraisals()->where('type', 'hr_communication')->count(),
            'warnings' => $candidate->appraisals()->where('type', 'warning')->count(),
            'acknowledged' => $candidate->appraisals()->where('is_acknowledged', true)->count(),
            'pending' => $candidate->appraisals()->where('is_acknowledged', false)->count(),
        ];

        // Group by type for tabbed view (limit to prevent memory issues)
        $performanceReviews = $candidate->appraisals()
            ->where('type', 'performance_review')
            ->select('id', 'candidate_id', 'type', 'title', 'content', 'review_date', 'is_acknowledged', 'created_at', 'created_by')
            ->with('createdBy:id,name')
            ->orderBy('review_date', 'desc')
            ->limit(50) // Limit to prevent memory exhaustion
            ->get();

        $hrCommunications = $candidate->appraisals()
            ->where('type', 'hr_communication')
            ->select('id', 'candidate_id', 'type', 'title', 'content', 'is_acknowledged', 'created_at', 'created_by')
            ->with('createdBy:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to prevent memory exhaustion
            ->get();

        $warnings = $candidate->appraisals()
            ->where('type', 'warning')
            ->select('id', 'candidate_id', 'type', 'title', 'content', 'severity', 'is_acknowledged', 'created_at', 'created_by')
            ->with('createdBy:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to prevent memory exhaustion
            ->get();

        return view('candidate.appraisals.index', compact(
            'candidate',
            'appraisals',
            'performanceReviews',
            'hrCommunications',
            'warnings',
            'stats'
        ));
    }

    /**
     * Show a specific appraisal.
     */
    public function show(CandidateAppraisal $appraisal)
    {
        $candidate = current_portal_candidate();
        
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
        $candidate = current_portal_candidate();
        
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
