<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BioDataController extends Controller
{
    /**
     * Show the bio data form.
     */
    public function index()
    {
        $candidate = current_portal_candidate();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        $bioData = $candidate->bio_data ? json_decode($candidate->bio_data, true) : [];

        // Calculate completion statistics
        $requiredFields = [
            'full_name', 'date_of_birth', 'gender', 'nationality', 'id_number', 'phone',
            'address', 'city', 'emergency_contact_name', 'emergency_contact_phone',
            'emergency_contact_relationship', 'education_level', 'institution', 'qualification'
        ];
        
        $optionalFields = [
            'postal_code', 'year_completed', 'previous_employer', 'previous_position',
            'previous_start_date', 'previous_end_date', 'skills', 'languages', 'additional_info'
        ];
        
        $completedRequired = collect($requiredFields)->filter(function($field) use ($bioData) {
            return !empty($bioData[$field] ?? null);
        })->count();
        
        $completedOptional = collect($optionalFields)->filter(function($field) use ($bioData) {
            return !empty($bioData[$field] ?? null);
        })->count();
        
        $completionPercentage = count($requiredFields) > 0 
            ? round(($completedRequired / count($requiredFields)) * 100) 
            : 0;
        
        $stats = [
            'completion_percentage' => $completionPercentage,
            'required_completed' => $completedRequired,
            'required_total' => count($requiredFields),
            'optional_completed' => $completedOptional,
            'optional_total' => count($optionalFields),
            'is_complete' => $candidate->bio_data_completed ?? false,
            'completed_at' => $candidate->bio_data_completed_at ?? null,
        ];

        return view('candidate.bio-data.index', compact('candidate', 'bioData', 'stats'));
    }

    /**
     * Update bio data.
     */
    public function update(Request $request)
    {
        $candidate = current_portal_candidate();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        // Prevent edits once bio data is marked as completed.
        if ($candidate->bio_data_completed) {
            return redirect()
                ->route('candidate.bio-data.index')
                ->withErrors(['error' => 'Your bio data has been submitted and locked. Please contact HR or your administrator if you need any changes.']);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'nationality' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:255'],
            'education_level' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],
            'year_completed' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'has_previous_experience' => ['nullable', 'in:yes,no'],
            'previous_employer' => ['nullable', 'string', 'max:255'],
            'previous_position' => ['nullable', 'string', 'max:255'],
            'previous_start_date' => ['nullable', 'date'],
            'previous_end_date' => ['nullable', 'date', 'after_or_equal:previous_start_date'],
            'skills' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
        ]);

        // If candidate indicates no previous experience, clear any previous_* fields
        $hasPrevious = ($validated['has_previous_experience'] ?? 'no') === 'yes';
        if (! $hasPrevious) {
            $validated['has_previous_experience'] = 'no';
            $validated['previous_employer'] = null;
            $validated['previous_position'] = null;
            $validated['previous_start_date'] = null;
            $validated['previous_end_date'] = null;
        }

        $candidate->bio_data = json_encode($validated);
        // Mark as completed on first successful submission
        $candidate->bio_data_completed = true;
        $candidate->bio_data_completed_at = now();
        $candidate->save();

        return redirect()->route('candidate.bio-data.index')
            ->with('success', 'Bio data updated successfully.');
    }
}
