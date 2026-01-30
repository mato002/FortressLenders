<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplication;
use App\Models\Candidate;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateFilterController
{
    public function index(Request $request): View
    {
        $query = Candidate::query();

        // Filter by skills
        if ($request->filled('skills')) {
            $skills = $request->input('skills');
            $query->where(function ($q) use ($skills) {
                foreach ($skills as $skill) {
                    $q->orWhereRaw("FIND_IN_SET(?, skills)", [$skill]);
                }
            });
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $experience = $request->input('experience_level');
            $query->whereIn('experience_level', $experience);
        }

        // Filter by location
        if ($request->filled('location')) {
            $locations = $request->input('location');
            $query->whereIn('current_location', $locations);
        }

        // Filter by salary expectation range
        if ($request->filled('salary_min') || $request->filled('salary_max')) {
            $min = $request->input('salary_min', 0);
            $max = $request->input('salary_max', 999999999);
            $query->whereBetween('expected_salary', [$min, $max]);
        }

        // Filter by application status
        if ($request->filled('application_status')) {
            $status = $request->input('application_status');
            $query->whereHas('jobApplications', function ($q) use ($status) {
                $q->whereIn('status', $status);
            });
        }

        // Filter by education level
        if ($request->filled('education_level')) {
            $education = $request->input('education_level');
            $query->whereIn('education_level', $education);
        }

        // Filter by availability
        if ($request->filled('notice_period')) {
            $noticePeriod = $request->input('notice_period');
            $query->whereIn('notice_period', $noticePeriod);
        }

        $candidates = $query->with('jobApplications')
            ->paginate(20)
            ->appends($request->all());

        // Get available filter options
        $skillsList = $this->getAvailableSkills();
        $locations = $this->getAvailableLocations();
        $experienceLevels = ['entry', 'mid', 'senior', 'lead', 'expert'];
        $educationLevels = ['high_school', 'diploma', 'bachelor', 'master', 'phd'];
        $noticePeriods = ['immediate', '1_week', '2_weeks', '1_month', '3_months'];
        $applicationStatuses = [
            'pending' => 'Pending Review',
            'sieving_passed' => 'Sieving Passed',
            'sieving_failed' => 'Sieving Failed',
            'aptitude_passed' => 'Aptitude Passed',
            'aptitude_failed' => 'Aptitude Failed',
            'interview_passed' => 'Interview Passed',
            'interview_failed' => 'Interview Failed',
            'hired' => 'Hired',
        ];

        return view('admin.candidates.filter', [
            'candidates' => $candidates,
            'skillsList' => $skillsList,
            'locations' => $locations,
            'experienceLevels' => $experienceLevels,
            'educationLevels' => $educationLevels,
            'noticePeriods' => $noticePeriods,
            'applicationStatuses' => $applicationStatuses,
            'filters' => $request->all(),
        ]);
    }

    private function getAvailableSkills(): array
    {
        return DB::table('job_applications')
            ->whereNotNull('skills')
            ->distinct()
            ->pluck('skills')
            ->flatten()
            ->unique()
            ->take(50)
            ->toArray();
    }

    private function getAvailableLocations(): array
    {
        return Candidate::distinct()
            ->whereNotNull('current_location')
            ->pluck('current_location')
            ->toArray();
    }
}
