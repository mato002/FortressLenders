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
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        $bioData = $candidate->bio_data ? json_decode($candidate->bio_data, true) : [];

        return view('candidate.bio-data.index', compact('candidate', 'bioData'));
    }

    /**
     * Update bio data.
     */
    public function update(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
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
            'previous_employer' => ['nullable', 'string', 'max:255'],
            'previous_position' => ['nullable', 'string', 'max:255'],
            'previous_start_date' => ['nullable', 'date'],
            'previous_end_date' => ['nullable', 'date', 'after_or_equal:previous_start_date'],
            'skills' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
        ]);

        $candidate->bio_data = json_encode($validated);
        $candidate->bio_data_completed = true;
        $candidate->bio_data_completed_at = now();
        $candidate->save();

        return redirect()->route('candidate.bio-data.index')
            ->with('success', 'Bio data updated successfully.');
    }
}
