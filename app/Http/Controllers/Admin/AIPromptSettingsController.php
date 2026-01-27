<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AIPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIPromptSettingsController extends Controller
{
    /**
     * Display AI prompts
     */
    public function index(Request $request)
    {
        $selectedRole = $request->get('role', 'default');
        $user = auth()->user();
        
        // Get available roles
        $roles = [
            'default' => 'Default (All Roles)',
            'admin' => 'Admin',
            'hr_manager' => 'HR Manager',
            'client' => 'Client',
        ];
        
        // Get prompts for selected role
        $prompts = $this->getPromptsForRole($selectedRole);
        
        // Get stored prompts from database
        try {
            $storedPrompts = AIPrompt::getPromptsForRole($selectedRole === 'default' ? null : $selectedRole);
            
            // Merge stored prompts with defaults
            foreach ($prompts as $key => &$prompt) {
                if (isset($storedPrompts[$key])) {
                    $prompt['stored_id'] = $storedPrompts[$key]->id;
                    $prompt['content'] = $storedPrompts[$key]->content;
                    $prompt['description'] = $storedPrompts[$key]->description ?? $prompt['description'];
                    $prompt['version'] = $storedPrompts[$key]->version;
                    $prompt['updated_at'] = $storedPrompts[$key]->updated_at;
                }
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet - use defaults only
        }
        
        return view('admin.ai-prompts.index', compact('prompts', 'roles', 'selectedRole'));
    }

    /**
     * Update or create a prompt
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'prompt_type' => 'required|string|in:system,cv_analysis,application_analysis,profile_summary,skill_matching,aptitude_test_analysis,self_interview_analysis',
            'role' => 'nullable|string|in:admin,hr_manager,client',
            'content' => 'required|string|min:10',
            'description' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $role = $validated['role'] === 'default' ? null : $validated['role'];

        DB::transaction(function () use ($validated, $role, $user) {
            // Check if prompt exists
            $existing = AIPrompt::where('prompt_type', $validated['prompt_type'])
                ->where('role', $role)
                ->first();

            if ($existing) {
                // Update existing
                $existing->update([
                    'content' => $validated['content'],
                    'description' => $validated['description'] ?? $existing->description,
                    'version' => $existing->version + 1,
                    'updated_by' => $user->id,
                ]);
            } else {
                // Create new
                AIPrompt::create([
                    'prompt_type' => $validated['prompt_type'],
                    'role' => $role,
                    'content' => $validated['content'],
                    'description' => $validated['description'] ?? $this->getDefaultDescription($validated['prompt_type']),
                    'is_active' => true,
                    'version' => 1,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }
        });

        $roleParam = $role ? "?role={$role}" : '?role=default';
        return redirect()->route('admin.ai-prompts.index', $roleParam)
            ->with('success', 'Prompt updated successfully!');
    }

    /**
     * Reset prompt to default
     */
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'prompt_type' => 'required|string|in:system,cv_analysis,application_analysis,profile_summary,skill_matching,aptitude_test_analysis,self_interview_analysis',
            'role' => 'nullable|string|in:admin,hr_manager,client',
        ]);

        $role = $validated['role'] === 'default' ? null : $validated['role'];

        AIPrompt::where('prompt_type', $validated['prompt_type'])
            ->where('role', $role)
            ->delete();

        $roleParam = $role ? "?role={$role}" : '?role=default';
        return redirect()->route('admin.ai-prompts.index', $roleParam)
            ->with('success', 'Prompt reset to default successfully!');
    }

    /**
     * Get prompts for a role
     */
    private function getPromptsForRole(?string $role = null): array
    {
        return [
            'system' => [
                'name' => 'System Prompt',
                'description' => 'This prompt is always sent to set the AI\'s role and behavior for all interactions.',
                'content' => 'You are an expert HR analyst specializing in candidate evaluation and CV analysis. Provide accurate, professional, and structured responses.',
                'when_used' => 'Always sent with every API call',
                'location' => 'app/Services/AIAnalysisService.php - callOpenAI() method',
            ],
            'cv_analysis' => [
                'name' => 'CV Analysis Prompt',
                'description' => 'Used when analyzing a CV/resume to extract candidate information.',
                'content' => $this->getCvAnalysisPromptTemplate(),
                'when_used' => 'When analyzing a CV/resume',
                'location' => 'app/Services/AIAnalysisService.php - buildAnalysisPrompt() method',
            ],
            'application_analysis' => [
                'name' => 'Application Analysis Prompt',
                'description' => 'The main evaluation prompt used when evaluating a job application against job requirements. This is the most comprehensive and critical prompt.',
                'content' => $this->getApplicationAnalysisPromptTemplate(),
                'when_used' => 'When evaluating a job application',
                'location' => 'app/Services/AIAnalysisService.php - buildApplicationAnalysisPrompt() method',
            ],
            'profile_summary' => [
                'name' => 'Profile Summary Prompt',
                'description' => 'Used when generating a professional candidate profile summary.',
                'content' => $this->getProfileSummaryPromptTemplate(),
                'when_used' => 'When generating candidate profile summary',
                'location' => 'app/Services/AIAnalysisService.php - buildProfileSummaryPrompt() method',
            ],
            'skill_matching' => [
                'name' => 'Skill Matching Prompt',
                'description' => 'Used when matching candidate skills to job requirements.',
                'content' => $this->getSkillMatchingPromptTemplate(),
                'when_used' => 'When matching skills to job requirements',
                'location' => 'app/Services/AIAnalysisService.php - buildSkillMatchingPrompt() method',
            ],
            'aptitude_test_analysis' => [
                'name' => 'Aptitude Test Analysis Prompt',
                'description' => 'Used when analyzing aptitude test results to provide insights on candidate performance.',
                'content' => $this->getAptitudeTestAnalysisPromptTemplate(),
                'when_used' => 'When analyzing aptitude test results',
                'location' => 'app/Services/AIAnalysisService.php - buildAptitudeTestAnalysisPrompt() method',
            ],
            'self_interview_analysis' => [
                'name' => 'Self Interview Analysis Prompt',
                'description' => 'Used when analyzing self interview responses to evaluate candidate fit, communication skills, and cultural alignment.',
                'content' => $this->getSelfInterviewAnalysisPromptTemplate(),
                'when_used' => 'When analyzing self interview responses',
                'location' => 'app/Services/AIAnalysisService.php - buildSelfInterviewAnalysisPrompt() method',
            ],
        ];
    }

    /**
     * Get default description for prompt type
     */
    private function getDefaultDescription(string $promptType): string
    {
        $descriptions = [
            'system' => 'This prompt is always sent to set the AI\'s role and behavior for all interactions.',
            'cv_analysis' => 'Used when analyzing a CV/resume to extract candidate information.',
            'application_analysis' => 'The main evaluation prompt used when evaluating a job application against job requirements.',
            'profile_summary' => 'Used when generating a professional candidate profile summary.',
            'skill_matching' => 'Used when matching candidate skills to job requirements.',
            'aptitude_test_analysis' => 'Used when analyzing aptitude test results to provide insights on candidate performance.',
            'self_interview_analysis' => 'Used when analyzing self interview responses to evaluate candidate fit, communication skills, and cultural alignment.',
        ];

        return $descriptions[$promptType] ?? '';
    }

    /**
     * Get CV Analysis prompt template
     */
    private function getCvAnalysisPromptTemplate(): string
    {
        return "Analyze the following CV/resume and provide a comprehensive summary.\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "CV Content:\n{cv_parsed_data->raw_text}\n\n" .
               "Please provide:\n" .
               "1. A concise summary of the candidate's background (2-3 sentences)\n" .
               "2. Key strengths and relevant experience\n" .
               "3. Education highlights\n" .
               "4. Notable skills and certifications\n" .
               "5. Overall assessment for the position\n\n" .
               "Format your response as JSON with keys: summary, strengths, education_highlights, skills, assessment.";
    }

    /**
     * Get Application Analysis prompt template
     */
    private function getApplicationAnalysisPromptTemplate(): string
    {
        return "Analyze this job application and match it to the job requirements.\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Description: {job_post->description}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "Application Details:\n" .
               "Name: {application->name}\n" .
               "Education: {application->education_level} in {application->area_of_study}\n" .
               "Current Position: {application->current_job_title} at {application->current_company}\n" .
               "Skills: {application->relevant_skills}\n" .
               "Why Interested: {application->why_interested}\n" .
               "Why Good Fit: {application->why_good_fit}\n" .
               "Career Goals: {application->career_goals}\n\n" .
               "CV Parsed Data Available\n\n" .
               "Please provide:\n" .
               "1. Match score (0-100) indicating how well the candidate matches the job\n" .
               "2. Key matching points\n" .
               "3. Missing requirements or gaps\n" .
               "4. Recommendation (pass/reject/manual_review)\n" .
               "5. Confidence level (0-1)\n\n" .
               "Format your response as JSON with keys: match_score, matching_points, missing_requirements, recommendation, confidence.";
    }

    /**
     * Get Profile Summary prompt template
     */
    private function getProfileSummaryPromptTemplate(): string
    {
        return "Generate a professional candidate profile summary based on the following information:\n\n" .
               "Candidate: {application->name}\n" .
               "Email: {application->email}\n\n" .
               "Applied for: {job_post->title}\n\n" .
               "CV Content:\n{cv_parsed_data->raw_text}\n\n" .
               "Create a 3-4 sentence professional summary highlighting:\n" .
               "- Professional background and experience\n" .
               "- Key skills and qualifications\n" .
               "- Notable achievements or strengths\n" .
               "- Relevance to the position (if job post provided)\n\n" .
               "Write in third person, professional tone.";
    }

    /**
     * Get Skill Matching prompt template
     */
    private function getSkillMatchingPromptTemplate(): string
    {
        return "Match the candidate's skills to the job requirements.\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "Candidate Skills:\n" .
               "Technical: {technical_skills}\n" .
               "Soft: {soft_skills}\n" .
               "Additional Skills from Application: {application->relevant_skills}\n\n" .
               "Provide:\n" .
               "1. Matching skills (skills that match job requirements)\n" .
               "2. Missing skills (required skills not found)\n" .
               "3. Bonus skills (additional valuable skills)\n" .
               "4. Match percentage\n\n" .
               "Format as JSON with keys: matching_skills, missing_skills, bonus_skills, match_percentage.";
    }

    /**
     * Get Aptitude Test Analysis prompt template
     */
    private function getAptitudeTestAnalysisPromptTemplate(): string
    {
        return "Analyze the candidate's aptitude test performance and provide insights.\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "Candidate Information:\n" .
               "Name: {application->name}\n" .
               "Education: {application->education_level} in {application->area_of_study}\n" .
               "Current Position: {application->current_job_title} at {application->current_company}\n\n" .
               "Aptitude Test Results:\n" .
               "Overall Score: {aptitude_test_score}% ({total_score}/{total_possible_score} points)\n" .
               "Pass Threshold: {pass_threshold}%\n" .
               "Status: {aptitude_test_passed}\n" .
               "Time Taken: {time_taken_seconds} seconds\n" .
               "Completed At: {aptitude_test_completed_at}\n\n" .
               "Section Performance:\n" .
               "{section_performance}\n\n" .
               "Question Details:\n" .
               "{question_details}\n\n" .
               "Please provide:\n" .
               "1. Overall performance assessment (strengths and weaknesses)\n" .
               "2. Section-by-section analysis (numerical, logical, verbal, scenario)\n" .
               "3. Areas of strength (which sections/questions they excelled in)\n" .
               "4. Areas for improvement (which sections/questions need work)\n" .
               "5. Analysis of calculation questions (if any) - evaluate their mathematical reasoning and accuracy\n" .
               "6. Analysis of text questions (if any) - provide insights on written responses\n" .
               "7. Relevance to job requirements (how test performance relates to job needs)\n" .
               "8. Recommendations for next steps (if passed, what to focus on; if failed, what to improve)\n" .
               "9. Confidence assessment (how reliable is this test result)\n\n" .
               "Note: Multiple choice questions are auto-graded. Calculation questions are auto-graded based on numeric answer comparison (handles formats like 42, 42.0, 42.00). Text questions require manual review.\n\n" .
               "Format your response as JSON with keys: overall_assessment, section_analysis, strengths, areas_for_improvement, calculation_analysis, text_analysis, job_relevance, recommendations, confidence_level.";
    }

    /**
     * Get Self Interview Analysis prompt template
     */
    private function getSelfInterviewAnalysisPromptTemplate(): string
    {
        return "Analyze the candidate's self interview responses and provide comprehensive insights.\n\n" .
               "Job Position: {job_post->title}\n" .
               "Job Description: {job_post->description}\n" .
               "Job Requirements: {job_post->requirements}\n\n" .
               "Candidate Information:\n" .
               "Name: {application->name}\n" .
               "Education: {application->education_level} in {application->area_of_study}\n" .
               "Current Position: {application->current_job_title} at {application->current_company}\n\n" .
               "Self Interview Results:\n" .
               "Overall Score: {self_interview_score}% ({total_score}/{total_possible_score} points)\n" .
               "Pass Threshold: {pass_threshold}%\n" .
               "Status: {self_interview_passed}\n" .
               "Time Taken: {time_taken_seconds} seconds\n" .
               "Completed At: {self_interview_completed_at}\n\n" .
               "Question Responses:\n" .
               "{question_responses}\n\n" .
               "Please provide:\n" .
               "1. Overall assessment of communication skills and self-awareness\n" .
               "2. Analysis of each response (quality, depth, relevance)\n" .
               "3. Cultural fit assessment (alignment with company values and role)\n" .
               "4. Strengths demonstrated in responses\n" .
               "5. Areas of concern or gaps in responses\n" .
               "6. Analysis of calculation questions (if any) - evaluate their problem-solving approach\n" .
               "7. Analysis of text responses - evaluate writing quality, clarity, and thoughtfulness\n" .
               "8. Job relevance (how responses relate to job requirements)\n" .
               "9. Recommendations for next steps (proceed to interview, request clarification, etc.)\n" .
               "10. Confidence level in assessment\n\n" .
               "Note: Multiple choice questions are auto-graded. Calculation questions are auto-graded based on numeric answer comparison. Text questions require manual review and should be evaluated for quality, depth, and relevance.\n\n" .
               "Format your response as JSON with keys: overall_assessment, response_analysis, cultural_fit, strengths, concerns, calculation_analysis, text_analysis, job_relevance, recommendations, confidence_level.";
    }
}
