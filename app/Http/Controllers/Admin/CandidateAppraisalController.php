<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateAppraisal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateAppraisalController extends Controller
{
    /**
     * Store a new appraisal created by HR for a candidate.
     */
    public function store(Request $request, Candidate $candidate): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:performance_review,hr_communication,warning'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'review_date' => ['nullable', 'date'],
            'severity' => ['nullable', 'string', 'in:low,medium,high'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('candidate_appraisals/' . $candidate->id, $filename, 'private');
        }

        CandidateAppraisal::create([
            'candidate_id' => $candidate->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
            'created_by' => $request->user()->id,
            'review_date' => $validated['review_date'] ?? null,
            'severity' => $validated['severity'] ?? null,
            'is_acknowledged' => false,
        ]);

        return back()->with('success', 'Appraisal created successfully for this candidate.');
    }

    /**
     * Delete an appraisal.
     */
    public function destroy(Candidate $candidate, CandidateAppraisal $appraisal): RedirectResponse
    {
        if ($appraisal->candidate_id !== $candidate->id) {
            abort(404);
        }

        if ($appraisal->file_path) {
            Storage::disk('private')->delete($appraisal->file_path);
        }

        $appraisal->delete();

        return back()->with('success', 'Appraisal deleted successfully.');
    }
}

