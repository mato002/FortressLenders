<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\CvParsedData;
use App\Models\JobPost;
use App\Models\Company;
use App\Models\AIPrompt;
use App\Services\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class AIAnalysisService
{
    protected string $apiProvider;
    protected ?string $apiKey;
    protected ?string $apiUrl;
    protected string $model;
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService = null)
    {
        $this->apiProvider = config('ai.provider', 'openai'); // openai, anthropic, local
        $this->apiKey = config('ai.api_key') ?: null;
        $this->apiUrl = config('ai.api_url');
        $this->model = config('ai.model', 'gpt-4o-mini');
        $this->tokenService = $tokenService ?? app(TokenService::class);
    }

    /**
     * Analyze CV and generate summary
     */
    public function analyzeCv(JobApplication $application): array
    {
        $cvParsedData = $application->cvParsedData;
        
        if (!$cvParsedData || empty($cvParsedData->raw_text)) {
            Log::warning('No CV parsed data available for analysis', [
                'application_id' => $application->id
            ]);
            return $this->generateFallbackSummary($application);
        }

        try {
            $companyId = $this->getCompanyId($application);
            $prompt = $this->buildAnalysisPrompt($application, $cvParsedData);
            $response = $this->callAI($prompt, $companyId, $application->id, 'cv_analyze');
            
            return $this->parseAIResponse($response);
        } catch (\Exception $e) {
            Log::error('AI CV analysis failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return $this->generateFallbackSummary($application);
        }
    }

    /**
     * Analyze application and match to job requirements
     */
    public function analyzeApplication(JobApplication $application): array
    {
        $jobPost = $application->jobPost;
        
        if (!$jobPost) {
            return [];
        }

        try {
            $companyId = $this->getCompanyId($application);
            $prompt = $this->buildApplicationAnalysisPrompt($application, $jobPost);
            $response = $this->callAI($prompt, $companyId, $application->id, 'scoring');
            
            return $this->parseApplicationAnalysisResponse($response);
        } catch (\Exception $e) {
            Log::error('AI application analysis failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Generate candidate profile summary
     */
    public function generateProfileSummary(JobApplication $application): string
    {
        $cvParsedData = $application->cvParsedData;
        $jobPost = $application->jobPost;
        
        if (!$cvParsedData) {
            return $this->generateBasicSummary($application);
        }

        try {
            $companyId = $this->getCompanyId($application);
            $prompt = $this->buildProfileSummaryPrompt($application, $cvParsedData, $jobPost);
            $response = $this->callAI($prompt, $companyId, $application->id, 'cv_analyze');
            
            return $this->extractTextFromResponse($response);
        } catch (\Exception $e) {
            Log::error('AI profile summary generation failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return $this->generateBasicSummary($application);
        }
    }

    /**
     * Match candidate skills to job requirements
     */
    public function matchSkillsToJob(JobApplication $application): array
    {
        $jobPost = $application->jobPost;
        $cvParsedData = $application->cvParsedData;
        
        if (!$jobPost || !$cvParsedData) {
            return [];
        }

        try {
            $prompt = $this->buildSkillMatchingPrompt($application, $cvParsedData, $jobPost);
            $response = $this->callAI($prompt);
            
            return $this->parseSkillMatchingResponse($response);
        } catch (\Exception $e) {
            Log::error('AI skill matching failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Build analysis prompt for CV
     */
    private function buildAnalysisPrompt(JobApplication $application, CvParsedData $cvParsedData): string
    {
        $jobPost = $application->jobPost;
        
        // Try to get custom prompt from database
        $storedPrompt = AIPrompt::getPrompt('cv_analysis', $this->getUserRole());
        $template = $storedPrompt ? $storedPrompt->content : $this->getDefaultCvAnalysisPrompt();
        
        // Replace placeholders
        $prompt = $template;
        if ($jobPost) {
            $prompt = str_replace('{job_post->title}', $jobPost->title ?? '', $prompt);
            $prompt = str_replace('{job_post->requirements}', $jobPost->requirements ?? '', $prompt);
        }
        $prompt = str_replace('{cv_parsed_data->raw_text}', $cvParsedData->raw_text ?? '', $prompt);

        return $prompt;
    }
    
    /**
     * Get default CV analysis prompt
     */
    private function getDefaultCvAnalysisPrompt(): string
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
     * Build application analysis prompt
     */
    private function buildApplicationAnalysisPrompt(JobApplication $application, JobPost $jobPost): string
    {
        // Try to get custom prompt from database
        $storedPrompt = AIPrompt::getPrompt('application_analysis', $this->getUserRole());
        $template = $storedPrompt ? $storedPrompt->content : $this->getDefaultApplicationAnalysisPrompt();
        
        // Replace placeholders
        $prompt = $template;
        $prompt = str_replace('{job_post->title}', $jobPost->title ?? '', $prompt);
        $prompt = str_replace('{job_post->description}', $jobPost->description ?? '', $prompt);
        $prompt = str_replace('{job_post->requirements}', $jobPost->requirements ?? '', $prompt);
        $prompt = str_replace('{application->name}', $application->name ?? '', $prompt);
        $prompt = str_replace('{application->email}', $application->email ?? '', $prompt);
        $prompt = str_replace('{application->education_level}', $application->education_level ?? '', $prompt);
        $prompt = str_replace('{application->area_of_study}', $application->area_of_study ?? '', $prompt);
        $prompt = str_replace('{application->current_job_title}', $application->current_job_title ?? '', $prompt);
        $prompt = str_replace('{application->current_company}', $application->current_company ?? '', $prompt);
        $prompt = str_replace('{application->relevant_skills}', $application->relevant_skills ?? '', $prompt);
        $prompt = str_replace('{application->why_interested}', $application->why_interested ?? '', $prompt);
        $prompt = str_replace('{application->why_good_fit}', $application->why_good_fit ?? '', $prompt);
        $prompt = str_replace('{application->career_goals}', $application->career_goals ?? '', $prompt);
        
        if ($application->cvParsedData) {
            $prompt = str_replace('CV Parsed Data Available', 'CV Parsed Data Available', $prompt);
        }

        return $prompt;
    }
    
    /**
     * Get default application analysis prompt
     */
    private function getDefaultApplicationAnalysisPrompt(): string
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
     * Build profile summary prompt
     */
    private function buildProfileSummaryPrompt(JobApplication $application, CvParsedData $cvParsedData, ?JobPost $jobPost): string
    {
        // Try to get custom prompt from database
        $storedPrompt = AIPrompt::getPrompt('profile_summary', $this->getUserRole());
        $template = $storedPrompt ? $storedPrompt->content : $this->getDefaultProfileSummaryPrompt();
        
        // Replace placeholders
        $prompt = $template;
        $prompt = str_replace('{application->name}', $application->name ?? '', $prompt);
        $prompt = str_replace('{application->email}', $application->email ?? '', $prompt);
        if ($jobPost) {
            $prompt = str_replace('{job_post->title}', $jobPost->title ?? '', $prompt);
        } else {
            $prompt = str_replace('Applied for: {job_post->title}', '', $prompt);
        }
        $prompt = str_replace('{cv_parsed_data->raw_text}', $cvParsedData->raw_text ?? '', $prompt);

        return $prompt;
    }
    
    /**
     * Get default profile summary prompt
     */
    private function getDefaultProfileSummaryPrompt(): string
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
     * Build skill matching prompt
     */
    private function buildSkillMatchingPrompt(JobApplication $application, CvParsedData $cvParsedData, JobPost $jobPost): string
    {
        $skills = $cvParsedData->parsed_skills ?? [];
        $skillsText = '';
        if (!empty($skills['technical'])) {
            $skillsText .= "Technical: " . implode(', ', $skills['technical']) . "\n";
        }
        if (!empty($skills['soft'])) {
            $skillsText .= "Soft: " . implode(', ', $skills['soft']) . "\n";
        }
        
        // Try to get custom prompt from database
        $storedPrompt = AIPrompt::getPrompt('skill_matching', $this->getUserRole());
        $template = $storedPrompt ? $storedPrompt->content : $this->getDefaultSkillMatchingPrompt();
        
        // Replace placeholders
        $prompt = $template;
        $prompt = str_replace('{job_post->title}', $jobPost->title ?? '', $prompt);
        $prompt = str_replace('{job_post->requirements}', $jobPost->requirements ?? '', $prompt);
        $prompt = str_replace('{technical_skills}', !empty($skills['technical']) ? implode(', ', $skills['technical']) : 'None', $prompt);
        $prompt = str_replace('{soft_skills}', !empty($skills['soft']) ? implode(', ', $skills['soft']) : 'None', $prompt);
        $prompt = str_replace('{application->relevant_skills}', $application->relevant_skills ?? '', $prompt);

        return $prompt;
    }
    
    /**
     * Get default skill matching prompt
     */
    private function getDefaultSkillMatchingPrompt(): string
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
     * Get user role for prompt selection
     */
    private function getUserRole(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        
        // Map user roles to prompt roles
        if ($user->role === 'admin') {
            return 'admin';
        } elseif ($user->role === 'hr_manager') {
            return 'hr_manager';
        } elseif ($user->isClient()) {
            return 'client';
        }
        
        return null; // Default prompts
    }

    /**
     * Call AI API
     */
    private function callAI(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // If no API key configured, return empty (will use fallback)
        if (empty($this->apiKey)) {
            Log::warning('AI API key not configured');
            throw new \Exception('AI API key not configured');
        }

        return match($this->apiProvider) {
            'openai' => $this->callOpenAI($prompt, $companyId, $jobApplicationId, $operationType),
            'anthropic' => $this->callAnthropic($prompt, $companyId, $jobApplicationId, $operationType),
            'local' => $this->callLocalLLM($prompt),
            default => throw new \Exception("Unsupported AI provider: {$this->apiProvider}"),
        };
    }

    /**
     * Get company ID for token tracking
     */
    private function getCompanyId(JobApplication $application): ?int
    {
        // Get company ID from application
        if ($application->company_id) {
            return $application->company_id;
        }
        
        // Fallback to job post company ID
        if ($application->jobPost && $application->jobPost->company_id) {
            return $application->jobPost->company_id;
        }
        
        // Fallback to first company (for backward compatibility)
        $company = Company::first();
        return $company?->id;
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // Estimate tokens before call
        $estimatedTokens = $this->tokenService->estimateTokens($operationType, strlen($prompt));
        
        // Check token availability if company ID provided and user is a client
        $user = auth()->user();
        $isClient = $user && $user->isClient();
        
        if ($companyId && $isClient && !$this->tokenService->hasEnoughTokens($companyId, $estimatedTokens)) {
            throw new \Exception('Insufficient tokens available. Please purchase more tokens.');
        }

        // Get system prompt from database or use default
        $systemPrompt = AIPrompt::getPrompt('system', $this->getUserRole());
        $systemContent = $systemPrompt ? $systemPrompt->content : 'You are an expert HR analyst specializing in candidate evaluation and CV analysis. Provide accurate, professional, and structured responses.';
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemContent,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.3,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '';
        
        // Track token usage (only for client users)
        if ($companyId && $isClient && isset($data['usage'])) {
            $usage = $data['usage'];
            $tokensUsed = $usage['total_tokens'] ?? ($usage['prompt_tokens'] + $usage['completion_tokens']);
            
            $this->tokenService->deductTokens(
                $companyId,
                $tokensUsed,
                $operationType,
                [
                    'input_tokens' => $usage['prompt_tokens'] ?? 0,
                    'output_tokens' => $usage['completion_tokens'] ?? 0,
                    'model' => $this->model,
                ],
                $jobApplicationId
            );
        }
        
        return $content;
    }

    /**
     * Call Anthropic API
     */
    private function callAnthropic(string $prompt, ?int $companyId = null, ?int $jobApplicationId = null, string $operationType = 'other'): string
    {
        // Estimate tokens before call
        $estimatedTokens = $this->tokenService->estimateTokens($operationType, strlen($prompt));
        
        // Check token availability if company ID provided and user is a client
        $user = auth()->user();
        $isClient = $user && $user->isClient();
        
        if ($companyId && $isClient && !$this->tokenService->hasEnoughTokens($companyId, $estimatedTokens)) {
            throw new \Exception('Insufficient tokens available. Please purchase more tokens.');
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $this->model,
            'max_tokens' => 2000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Anthropic API error: ' . $response->body());
        }

        $data = $response->json();
        $content = $data['content'][0]['text'] ?? '';
        
        // Track token usage (Anthropic returns usage in headers or response) - only for client users
        if ($companyId && $isClient && isset($data['usage'])) {
            $usage = $data['usage'];
            $tokensUsed = $usage['input_tokens'] + $usage['output_tokens'];
            
            $this->tokenService->deductTokens(
                $companyId,
                $tokensUsed,
                $operationType,
                [
                    'input_tokens' => $usage['input_tokens'] ?? 0,
                    'output_tokens' => $usage['output_tokens'] ?? 0,
                    'model' => $this->model,
                ],
                $jobApplicationId
            );
        }
        
        return $content;
    }

    /**
     * Call local LLM (e.g., Ollama)
     */
    private function callLocalLLM(string $prompt): string
    {
        $url = $this->apiUrl ?: config('ai.local_api_url', 'http://localhost:11434/api/generate');
        
        $response = Http::post($url, [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            throw new \Exception('Local LLM API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['response'] ?? '';
    }

    /**
     * Parse AI response for CV analysis
     */
    private function parseAIResponse(string $response): array
    {
        // Try to extract JSON from response
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        // Fallback: parse text response
        return [
            'summary' => $response,
            'strengths' => [],
            'education_highlights' => [],
            'skills' => [],
            'assessment' => '',
        ];
    }

    /**
     * Parse application analysis response
     */
    private function parseApplicationAnalysisResponse(string $response): array
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'match_score' => 0,
            'matching_points' => [],
            'missing_requirements' => [],
            'recommendation' => 'manual_review',
            'confidence' => 0.5,
        ];
    }

    /**
     * Parse skill matching response
     */
    private function parseSkillMatchingResponse(string $response): array
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $json = json_decode($matches[0], true);
            if ($json) {
                return $json;
            }
        }

        return [
            'matching_skills' => [],
            'missing_skills' => [],
            'bonus_skills' => [],
            'match_percentage' => 0,
        ];
    }

    /**
     * Extract text from AI response
     */
    private function extractTextFromResponse(string $response): string
    {
        // Remove JSON markers if present
        $response = preg_replace('/^```json\s*/', '', $response);
        $response = preg_replace('/^```\s*/', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        
        return trim($response);
    }

    /**
     * Generate fallback summary when AI is unavailable
     */
    private function generateFallbackSummary(JobApplication $application): array
    {
        $summary = "Application from {$application->name}";
        
        if ($application->education_level) {
            $summary .= " with {$application->education_level}";
            if ($application->area_of_study) {
                $summary .= " in {$application->area_of_study}";
            }
        }
        
        if ($application->current_job_title) {
            $summary .= ". Currently working as {$application->current_job_title}";
            if ($application->current_company) {
                $summary .= " at {$application->current_company}";
            }
        }

        return [
            'summary' => $summary,
            'strengths' => [],
            'education_highlights' => [],
            'skills' => [],
            'assessment' => 'Requires manual review',
        ];
    }

    /**
     * Generate basic summary
     */
    private function generateBasicSummary(JobApplication $application): string
    {
        $summary = "{$application->name} is a candidate";
        
        if ($application->education_level) {
            $summary .= " with a {$application->education_level}";
            if ($application->area_of_study) {
                $summary .= " in {$application->area_of_study}";
            }
        }
        
        if ($application->current_job_title) {
            $summary .= " currently working as {$application->current_job_title}";
        }
        
        $summary .= ".";

        return $summary;
    }
}

