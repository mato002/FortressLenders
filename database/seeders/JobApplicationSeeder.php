<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Services\AISievingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = JobPost::query()
            ->where('is_active', true)
            ->take(5)
            ->get();

        if ($jobs->isEmpty()) {
            return;
        }

        $sievingService = new AISievingService();

        foreach ($jobs as $job) {
            // Strong candidates (likely to pass sieving)
            for ($i = 1; $i <= 3; $i++) {
                $application = JobApplication::create([
                    'job_post_id' => $job->id,
                    'name' => "Strong Candidate {$i} for {$job->title}",
                    'phone' => '+2547' . rand(10000000, 99999999),
                    'email' => "strong{$i}." . Str::slug($job->title) . '@example.com',
                    'why_interested' => "I am passionate about {$job->department} and have closely followed Fortress Lenders. I believe this role aligns with my long-term career goals and allows me to contribute to financial inclusion in Kenya.",
                    'why_good_fit' => "I have proven experience in {$job->department} and have consistently exceeded performance targets. I am detail-oriented, analytical, and comfortable working with customers and data.",
                    'career_goals' => "To grow within Fortress Lenders, take on leadership responsibilities, and support the growth of the loan portfolio while maintaining high credit quality.",
                    'salary_expectations' => 'Competitive based on company scale and role seniority',
                    'availability_date' => now()->addDays(rand(14, 45)),
                    'relevant_skills' => 'Financial analysis, customer service, MS Excel, communication, teamwork, problem solving',
                    'education_level' => "Bachelor's Degree",
                    'area_of_study' => 'Finance',
                    'institution' => 'University of Nairobi',
                    'education_status' => 'Completed',
                    'education_start_year' => 2016,
                    'education_end_year' => 2020,
                    'other_achievements' => 'Top 10% of class, Dean’s list, led a student finance club.',
                    'work_experience' => [
                        [
                            'company' => 'ABC Microfinance',
                            'role' => 'Loan Officer',
                            'start_date' => now()->subYears(3)->format('Y-m-d'),
                            'end_date' => now()->format('Y-m-d'),
                        ],
                    ],
                    'current_job_title' => 'Loan Officer',
                    'current_company' => 'ABC Microfinance',
                    'currently_working' => true,
                    'duties_and_responsibilities' => 'Evaluating loan applications, meeting clients, monitoring portfolio performance.',
                    'other_experiences' => 'Volunteered in financial literacy programs.',
                    'support_details' => null,
                    'referrers' => [],
                    'notice_period' => '1 month',
                    'agreement_accepted' => true,
                    'application_message' => 'Looking forward to contributing to Fortress Lenders.',
                    'status' => 'pending',
                ]);

                // Run AI sieving to simulate real flow
                $sievingService->evaluate($application);
            }

            // Weak candidates (likely to be rejected or manual review)
            for ($i = 1; $i <= 3; $i++) {
                $application = JobApplication::create([
                    'job_post_id' => $job->id,
                    'name' => "Weak Candidate {$i} for {$job->title}",
                    'phone' => '+2547' . rand(10000000, 99999999),
                    'email' => "weak{$i}." . Str::slug($job->title) . '@example.com',
                    'why_interested' => 'I need a job.',
                    'why_good_fit' => 'I think I can do it.',
                    'career_goals' => 'To work somewhere.',
                    'salary_expectations' => 'Very high salary',
                    'availability_date' => now()->addDays(rand(90, 180)),
                    'relevant_skills' => 'Hard worker',
                    'education_level' => null,
                    'area_of_study' => null,
                    'institution' => null,
                    'education_status' => null,
                    'education_start_year' => null,
                    'education_end_year' => null,
                    'other_achievements' => null,
                    'work_experience' => [],
                    'current_job_title' => null,
                    'current_company' => null,
                    'currently_working' => false,
                    'duties_and_responsibilities' => null,
                    'other_experiences' => null,
                    'support_details' => null,
                    'referrers' => [],
                    'notice_period' => null,
                    'agreement_accepted' => true,
                    'application_message' => 'Please hire me.',
                    'status' => 'pending',
                ]);

                // Run AI sieving to simulate real flow
                $sievingService->evaluate($application);
            }
        }
    }
}


