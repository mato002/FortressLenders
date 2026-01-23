<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\JobSievingCriteria;
use App\Models\AISievingDecision;
use App\Models\Candidate;
use App\Mail\AptitudeTestInvitation;
use App\Mail\CandidateAccountCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AISievingService
{
    protected AIAnalysisService $aiAnalysisService;

    public function __construct(AIAnalysisService $aiAnalysisService = null)
    {
        $this->aiAnalysisService = $aiAnalysisService ?? new AIAnalysisService();
    }

    /**
     * Evaluate a job application using AI sieving
     */
    public function evaluate(JobApplication $application): AISievingDecision
    {
        $jobPost = $application->jobPost;
        
        if (!$jobPost) {
            throw new \Exception("Job post not found for application #{$application->id}");
        }
        
        // Get or create default criteria
        $criteria = $jobPost->sievingCriteria ?? $this->getDefaultCriteria($jobPost);
        
        // Calculate rule-based score
        $ruleScore = $this->calculateRuleBasedScore($application, $criteria);
        
        // Get AI-enhanced analysis if enabled
        $aiAnalysis = null;
        $aiScore = null;
        $aiConfidence = null;
        
        if (config('ai.enable_ai_analysis', true)) {
            try {
                $aiAnalysis = $this->aiAnalysisService->analyzeApplication($application);
                
                if (!empty($aiAnalysis)) {
                    $aiScore = $aiAnalysis['match_score'] ?? null;
                    $aiConfidence = $aiAnalysis['confidence'] ?? null;
                    
                    // Blend rule-based and AI scores (weighted average)
                    if ($aiScore !== null && $aiConfidence !== null) {
                        $aiWeight = min(0.7, $aiConfidence); // Use AI score more if confidence is high
                        $ruleWeight = 1 - $aiWeight;
                        $ruleScore = (int) round(($ruleScore * $ruleWeight) + ($aiScore * $aiWeight));
                    }
                }
            } catch (\Exception $e) {
                Log::warning('AI analysis failed during sieving, using rule-based only', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Calculate confidence
        $confidence = $this->calculateConfidence($application, $ruleScore, $aiConfidence);
        
        // CRITICAL: If CV is missing, force high confidence for rejection to ensure status update
        if (empty($application->cv_path)) {
            $confidence = max($confidence, 0.95); // Force high confidence when CV is missing
        }
        
        // Determine decision (pass application for CV check)
        $decision = $this->makeDecision($ruleScore, $confidence, $criteria, $aiAnalysis, $application);
        
        // Extract strengths and weaknesses (enhanced with AI if available)
        $strengths = $this->extractStrengths($application, $criteria, $aiAnalysis);
        $weaknesses = $this->extractWeaknesses($application, $criteria, $aiAnalysis);
        
        // Generate reasoning (enhanced with AI insights)
        $reasoning = $this->generateReasoning($application, $ruleScore, $decision, $strengths, $weaknesses, $aiAnalysis);
        
        // Store or update decision
        $aiDecision = AISievingDecision::updateOrCreate(
            ['job_application_id' => $application->id],
            [
                'ai_decision' => $decision,
                'ai_confidence' => $confidence,
                'ai_score' => $ruleScore,
                'ai_reasoning' => $reasoning,
                'ai_strengths' => $strengths,
                'ai_weaknesses' => $weaknesses,
            ]
        );
        
        // Auto-update status if high confidence
        $this->autoUpdateStatus($application, $aiDecision, $criteria);
        
        return $aiDecision;
    }

    /**
     * Get default criteria for a job post
     */
    private function getDefaultCriteria(JobPost $jobPost): JobSievingCriteria
    {
        $defaultCriteria = JobSievingCriteria::getDefaultCriteria();
        
        // Get user ID if available, otherwise use null
        $createdBy = auth()->check() ? auth()->id() : null;
        
        return JobSievingCriteria::create([
            'job_post_id' => $jobPost->id,
            'criteria_json' => $defaultCriteria,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Calculate rule-based score (0-100)
     */
    private function calculateRuleBasedScore(JobApplication $application, JobSievingCriteria $criteria): int
    {
        $criteriaData = $criteria->criteria_json;
        $totalScore = 0;
        
        // Education scoring
        $totalScore += $this->scoreEducation($application, $criteriaData['education'] ?? []);
        
        // Experience scoring
        $totalScore += $this->scoreExperience($application, $criteriaData['experience'] ?? []);
        
        // Skills scoring
        $totalScore += $this->scoreSkills($application, $criteriaData['skills'] ?? []);
        
        // Response quality scoring
        $totalScore += $this->scoreResponseQuality($application, $criteriaData['response_quality'] ?? []);
        
        // Apply red flags
        $totalScore += $this->applyRedFlags($application, $criteriaData['red_flags'] ?? []);
        
        // Ensure score is between 0-100
        return max(0, min(100, $totalScore));
    }

    /**
     * Score education (0-25 points)
     */
    private function scoreEducation(JobApplication $application, array $criteria): int
    {
        $score = 0;
        $educationLevel = $application->education_level ?? '';
        $areaOfStudy = $application->area_of_study ?? '';
        
        // Education level scoring
        $levelScores = [
            'Master\'s' => 25,
            'Bachelor\'s' => 20,
            'Diploma' => 15,
            'Certificate' => 5,
            'High School' => 0,
        ];
        
        foreach ($levelScores as $level => $points) {
            if (stripos($educationLevel, $level) !== false) {
                $score = $points;
                break;
            }
        }
        
        // Field match bonus
        if (!empty($areaOfStudy) && !empty($criteria['relevant_fields'])) {
            foreach ($criteria['relevant_fields'] as $field) {
                if (stripos($areaOfStudy, $field) !== false) {
                    $score += $criteria['field_match_bonus'] ?? 5;
                    break;
                }
            }
        }
        
        return min($criteria['weight'] ?? 25, $score);
    }

    /**
     * Score experience (0-30 points)
     */
    private function scoreExperience(JobApplication $application, array $criteria): int
    {
        $score = 0;
        $workExperience = $application->work_experience ?? [];
        
        if (empty($workExperience) && !empty($application->current_job_title)) {
            // Estimate from current position
            $years = $this->estimateYearsFromTitle($application->current_job_title);
            $score = min($criteria['max_points'] ?? 30, $years * ($criteria['points_per_year'] ?? 8));
        } else {
            // Calculate from work experience array
            $totalYears = 0;
            foreach ($workExperience as $exp) {
                if (isset($exp['start_date']) && isset($exp['end_date'])) {
                    $start = \Carbon\Carbon::parse($exp['start_date']);
                    $end = \Carbon\Carbon::parse($exp['end_date']);
                    $totalYears += $start->diffInYears($end);
                }
            }
            $score = min($criteria['max_points'] ?? 30, $totalYears * ($criteria['points_per_year'] ?? 8));
        }
        
        // Industry match bonus
        if (!empty($application->current_company) && !empty($criteria['relevant_industries'])) {
            foreach ($criteria['relevant_industries'] as $industry) {
                if (stripos($application->current_company, $industry) !== false ||
                    stripos($application->current_job_title ?? '', $industry) !== false) {
                    $score += $criteria['industry_match_bonus'] ?? 10;
                    break;
                }
            }
        }
        
        return min($criteria['max_points'] ?? 30, $score);
    }

    /**
     * Score skills (0-25 points)
     */
    private function scoreSkills(JobApplication $application, array $criteria): int
    {
        $score = 0;
        $skills = strtolower($application->relevant_skills ?? '');
        
        // Required skills
        foreach ($criteria['required_skills'] ?? [] as $skill) {
            if (stripos($skills, strtolower($skill)) !== false) {
                $score += $criteria['points_per_required'] ?? 5;
            }
        }
        
        // Preferred skills
        foreach ($criteria['preferred_skills'] ?? [] as $skill) {
            if (stripos($skills, strtolower($skill)) !== false) {
                $score += $criteria['points_per_preferred'] ?? 3;
            }
        }
        
        return min($criteria['max_points'] ?? 25, $score);
    }

    /**
     * Score response quality (0-20 points)
     */
    private function scoreResponseQuality(JobApplication $application, array $criteria): int
    {
        $score = 0;
        $minLength = $criteria['min_length'] ?? 50;
        
        // Why interested
        $whyInterested = $application->why_interested ?? '';
        if (strlen($whyInterested) >= $minLength) {
            $score += 8;
            // Check for quality indicators
            foreach ($criteria['quality_indicators'] ?? [] as $indicator) {
                if (stripos($whyInterested, $indicator) !== false) {
                    $score += 2;
                }
            }
        }
        
        // Why good fit
        $whyGoodFit = $application->why_good_fit ?? '';
        if (strlen($whyGoodFit) >= $minLength) {
            $score += 8;
            foreach ($criteria['quality_indicators'] ?? [] as $indicator) {
                if (stripos($whyGoodFit, $indicator) !== false) {
                    $score += 2;
                }
            }
        }
        
        // Career goals
        $careerGoals = $application->career_goals ?? '';
        if (strlen($careerGoals) >= $minLength) {
            $score += 4;
        }
        
        return min($criteria['max_points'] ?? 20, $score);
    }

    /**
     * Apply red flags penalties
     */
    private function applyRedFlags(JobApplication $application, array $redFlags): int
    {
        $penalty = 0;
        
        // Missing CV - CRITICAL: Should not pass without CV
        if (empty($application->cv_path)) {
            $penalty += $redFlags['missing_cv'] ?? -50; // Heavy penalty for missing CV
        }
        
        // Incomplete application
        if (empty($application->education_level) || empty($application->why_interested)) {
            $penalty += $redFlags['incomplete_application'] ?? -20;
        }
        
        // Generic responses (very short or repetitive)
        $whyInterested = $application->why_interested ?? '';
        $whyGoodFit = $application->why_good_fit ?? '';
        if (strlen($whyInterested) < 30 || strlen($whyGoodFit) < 30) {
            $penalty += $redFlags['generic_responses'] ?? -10;
        }
        
        // Availability delayed
        if (!empty($application->availability_date)) {
            $availabilityDate = \Carbon\Carbon::parse($application->availability_date);
            $daysUntilAvailable = now()->diffInDays($availabilityDate, false);
            if ($daysUntilAvailable > ($redFlags['availability_delayed_days'] ?? 90)) {
                $penalty += $redFlags['availability_penalty'] ?? -10;
            }
        }
        
        return $penalty;
    }

    /**
     * Calculate confidence level (0.0-1.0)
     */
    private function calculateConfidence(JobApplication $application, int $score, ?float $aiConfidence = null): float
    {
        // Base confidence from score
        $baseConfidence = 0.70;
        if ($score >= 80 || $score <= 30) {
            $baseConfidence = 0.90;
        } elseif ($score >= 70 || $score <= 40) {
            $baseConfidence = 0.85;
        }
        
        // If AI confidence is available, blend it
        if ($aiConfidence !== null) {
            // Weighted average: 60% AI confidence, 40% rule-based
            return min(1.0, ($aiConfidence * 0.6) + ($baseConfidence * 0.4));
        }
        
        return $baseConfidence;
    }

    /**
     * Make decision based on score and confidence
     */
    private function makeDecision(int $score, float $confidence, JobSievingCriteria $criteria, ?array $aiAnalysis = null, ?JobApplication $application = null): string
    {
        // CRITICAL: Auto-reject if CV is missing (regardless of other scores)
        if ($application && empty($application->cv_path)) {
            return 'reject';
        }
        
        // If AI analysis recommends a decision, consider it
        if ($aiAnalysis && isset($aiAnalysis['recommendation'])) {
            $aiRecommendation = $aiAnalysis['recommendation'];
            $aiConfidence = $aiAnalysis['confidence'] ?? 0.5;
            
            // Use AI recommendation if confidence is high enough
            if ($aiConfidence >= config('ai.min_confidence_for_auto_pass', 0.85)) {
                if ($aiRecommendation === 'pass' && $score >= $criteria->auto_pass_threshold) {
                    return 'pass';
                } elseif ($aiRecommendation === 'reject' && $score <= $criteria->auto_reject_threshold) {
                    return 'reject';
                }
            }
        }
        
        // Auto-pass: high score + high confidence
        if ($score >= $criteria->auto_pass_threshold && $confidence >= $criteria->auto_pass_confidence) {
            return 'pass';
        }
        
        // Auto-reject: low score + high confidence
        if ($score <= $criteria->auto_reject_threshold && $confidence >= $criteria->auto_reject_confidence) {
            return 'reject';
        }
        
        // Manual review for everything else
        return 'manual_review';
    }

    /**
     * Extract strengths
     */
    private function extractStrengths(JobApplication $application, JobSievingCriteria $criteria, ?array $aiAnalysis = null): array
    {
        $strengths = [];
        
        // Add AI-identified strengths if available
        if ($aiAnalysis && !empty($aiAnalysis['matching_points'])) {
            $strengths = array_merge($strengths, (array) $aiAnalysis['matching_points']);
        }
        
        // Add rule-based strengths
        if (!empty($application->education_level)) {
            $strengths[] = "Education: {$application->education_level}";
        }
        
        if (!empty($application->current_job_title)) {
            $strengths[] = "Current Position: {$application->current_job_title}";
        }
        
        if (!empty($application->relevant_skills)) {
            $strengths[] = "Relevant Skills: " . substr($application->relevant_skills, 0, 100);
        }
        
        // Remove duplicates
        return array_unique($strengths);
    }

    /**
     * Extract weaknesses
     */
    private function extractWeaknesses(JobApplication $application, JobSievingCriteria $criteria, ?array $aiAnalysis = null): array
    {
        $weaknesses = [];
        
        // Add AI-identified weaknesses if available
        if ($aiAnalysis && !empty($aiAnalysis['missing_requirements'])) {
            $weaknesses = array_merge($weaknesses, (array) $aiAnalysis['missing_requirements']);
        }
        
        // Add rule-based weaknesses
        if (empty($application->cv_path)) {
            $weaknesses[] = "CV/Resume not uploaded - REQUIRED";
        }
        
        if (empty($application->education_level)) {
            $weaknesses[] = "Education level not specified";
        }
        
        if (empty($application->work_experience) && empty($application->current_job_title)) {
            $weaknesses[] = "No work experience provided";
        }
        
        if (strlen($application->why_interested ?? '') < 50) {
            $weaknesses[] = "Why interested response is too brief";
        }
        
        // Remove duplicates
        return array_unique($weaknesses);
    }

    /**
     * Generate reasoning text
     */
    private function generateReasoning(JobApplication $application, int $score, string $decision, array $strengths, array $weaknesses, ?array $aiAnalysis = null): string
    {
        $reasoning = "AI Evaluation Score: {$score}/100\n\n";
        
        // Add AI match score if available
        if ($aiAnalysis && isset($aiAnalysis['match_score'])) {
            $reasoning .= "AI Match Score: {$aiAnalysis['match_score']}/100\n";
        }
        
        $reasoning .= "Decision: " . strtoupper($decision) . "\n\n";
        
        if (!empty($strengths)) {
            $reasoning .= "Strengths:\n" . implode("\n", array_map(fn($s) => "- {$s}", $strengths)) . "\n\n";
        }
        
        if (!empty($weaknesses)) {
            $reasoning .= "Weaknesses:\n" . implode("\n", array_map(fn($w) => "- {$w}", $weaknesses)) . "\n\n";
        }
        
        // Add AI assessment if available
        if ($aiAnalysis && !empty($aiAnalysis['assessment'])) {
            $reasoning .= "AI Assessment: {$aiAnalysis['assessment']}\n\n";
        }
        
        return $reasoning;
    }

    /**
     * Auto-update application status based on AI decision
     */
    private function autoUpdateStatus(JobApplication $application, AISievingDecision $aiDecision, JobSievingCriteria $criteria): void
    {
        $previousStatus = $application->status;
        $newStatus = $previousStatus;
        
        // CRITICAL: Force reject if CV is missing (regardless of confidence or previous status)
        if (empty($application->cv_path) && $aiDecision->ai_decision === 'reject') {
            $newStatus = 'sieving_rejected';
        }
        // Force reject if decision is reject and confidence meets threshold
        elseif ($aiDecision->ai_decision === 'reject' && 
                $aiDecision->ai_confidence >= $criteria->auto_reject_confidence) {
            $newStatus = 'sieving_rejected';
        }
        // For all other cases (pass, manual_review, or low confidence), set to sieving_passed
        // This ensures we only have two states: passed or rejected
        else {
            $newStatus = 'sieving_passed';
        }
        
        // Update status if it changed (allow downgrade from sieving_passed to sieving_rejected)
        // This ensures re-sieving can change status even if it was previously passed
        if ($newStatus !== $previousStatus) {
            $application->update(['status' => $newStatus]);
            
            // Record status change in history
            $notes = "AI sieving decision: {$aiDecision->ai_decision} (Score: {$aiDecision->ai_score}/100, Confidence: " . number_format($aiDecision->ai_confidence * 100, 1) . "%)";
            if (empty($application->cv_path) && $aiDecision->ai_decision === 'reject') {
                $notes .= " - CV missing (forced rejection)";
            }
            
            \App\Models\JobApplicationStatusHistory::create([
                'job_application_id' => $application->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'changed_by' => null, // System/AI change
                'source' => 'ai_sieving',
                'notes' => $notes,
            ]);
            
            // Send email notification if status changed to sieving_passed
            // Only send if email hasn't been sent before (or if it was sent more than 24 hours ago)
            if ($newStatus === 'sieving_passed') {
                // Check if email was already sent recently (within last 24 hours)
                $emailSentAt = $application->sieving_passed_email_sent_at;
                $shouldSendEmail = !$emailSentAt || $emailSentAt->diffInHours(now()) >= 24;
                
                if ($shouldSendEmail) {
                    try {
                        // Track if this is a new account or existing one
                        $isNewAccount = false;
                        
                        // Ensure candidate account exists and send login credentials
                        $candidate = $application->candidate;
                        
                        // If no candidate account exists, create one
                        if (!$candidate) {
                            $candidate = $this->createCandidateAccount($application);
                            $isNewAccount = true;
                        } else {
                            // For existing candidates, ensure they have a temporary password
                            // This will generate a new password if needed
                            $candidate = $this->ensureCandidateHasTemporaryPassword($candidate, $application);
                        }
                        
                        // Send emails if account exists
                        if ($candidate) {
                            $temporaryPassword = $candidate->temporary_password ?? null;
                            
                            if ($temporaryPassword) {
                                // Send account credentials email (for both new and existing candidates with new password)
                                try {
                                    Log::info('Sending candidate account credentials email', [
                                        'application_id' => $application->id,
                                        'candidate_id' => $candidate->id,
                                        'email' => $application->email,
                                        'is_new_account' => $isNewAccount,
                                    ]);
                                    
                                    Mail::to($application->email)->send(new CandidateAccountCreated($candidate, $temporaryPassword));

                                    // Track credentials email and surface in Admin -> History tab
                                    $application->update(['candidate_credentials_sent_at' => now()]);
                                    \App\Models\JobApplicationStatusHistory::create([
                                        'job_application_id' => $application->id,
                                        'previous_status' => $application->status,
                                        'new_status' => $application->status,
                                        'changed_by' => null,
                                        'source' => 'system_email',
                                        'notes' => 'Candidate account login credentials sent to applicant.',
                                    ]);
                                    
                                    Log::info('Candidate account credentials email sent successfully', [
                                        'application_id' => $application->id,
                                        'candidate_id' => $candidate->id,
                                        'email' => $application->email,
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to send candidate account credentials email', [
                                        'application_id' => $application->id,
                                        'candidate_id' => $candidate->id,
                                        'email' => $application->email,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                            }
                            
                            // Send aptitude test invitation email
                            try {
                                Log::info('Sending aptitude test invitation email', [
                                    'application_id' => $application->id,
                                    'candidate_id' => $candidate->id,
                                    'email' => $application->email,
                                    'has_password' => !empty($temporaryPassword),
                                ]);
                                
                                Mail::to($application->email)->send(new AptitudeTestInvitation($application, $candidate, $temporaryPassword));
                                
                                // Mark email as sent
                                $application->update(['sieving_passed_email_sent_at' => now()]);
                                \App\Models\JobApplicationStatusHistory::create([
                                    'job_application_id' => $application->id,
                                    'previous_status' => $application->status,
                                    'new_status' => $application->status,
                                    'changed_by' => null,
                                    'source' => 'system_email',
                                    'notes' => 'Aptitude test invitation email sent to applicant.',
                                ]);
                                
                                Log::info('Aptitude test invitation email sent successfully', [
                                    'application_id' => $application->id,
                                    'candidate_id' => $candidate->id,
                                    'email' => $application->email,
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send aptitude test invitation email', [
                                    'application_id' => $application->id,
                                    'candidate_id' => $candidate->id,
                                    'email' => $application->email,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        } else {
                            // Fallback: send invitation without login credentials
                            try {
                                Log::warning('Sending aptitude test invitation without credentials (candidate account creation failed)', [
                                    'application_id' => $application->id,
                                    'email' => $application->email,
                                ]);
                                
                                Mail::to($application->email)->send(new AptitudeTestInvitation($application));
                                
                                // Mark email as sent even if credentials weren't included
                                $application->update(['sieving_passed_email_sent_at' => now()]);
                                \App\Models\JobApplicationStatusHistory::create([
                                    'job_application_id' => $application->id,
                                    'previous_status' => $application->status,
                                    'new_status' => $application->status,
                                    'changed_by' => null,
                                    'source' => 'system_email',
                                    'notes' => 'Aptitude test invitation email sent to applicant (no credentials).',
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send aptitude test invitation email (fallback)', [
                                    'application_id' => $application->id,
                                    'email' => $application->email,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send emails after sieving passed', [
                            'application_id' => $application->id,
                            'email' => $application->email,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                } else {
                    Log::info('Skipping sieving passed email - already sent recently', [
                        'application_id' => $application->id,
                        'email' => $application->email,
                        'sent_at' => $emailSentAt,
                        'hours_ago' => $emailSentAt->diffInHours(now()),
                    ]);
                }
            }
        }
    }

    /**
     * Estimate years of experience from job title
     */
    private function estimateYearsFromTitle(?string $title): int
    {
        if (empty($title)) {
            return 0;
        }
        
        $title = strtolower($title);
        
        // Senior/Lead/Manager positions suggest 3+ years
        if (preg_match('/\b(senior|lead|manager|director|head|chief)\b/', $title)) {
            return 3;
        }
        
        // Mid-level positions suggest 1-2 years
        if (preg_match('/\b(associate|specialist|coordinator|officer)\b/', $title)) {
            return 1;
        }
        
        // Junior/Entry level
        if (preg_match('/\b(junior|entry|intern|trainee)\b/', $title)) {
            return 0;
        }
        
        // Default assumption
        return 1;
    }

    /**
     * Create candidate account if it doesn't exist
     */
    private function createCandidateAccount(JobApplication $application): ?Candidate
    {
        // Check if candidate already exists
        $candidate = Candidate::where('email', $application->email)->first();
        
        if ($candidate) {
            // Link application to existing candidate
            if (!$application->candidate_id && \Schema::hasColumn('job_applications', 'candidate_id')) {
                try {
                    $application->update(['candidate_id' => $candidate->id]);
                } catch (\Exception $e) {
                    Log::warning('Failed to link application to existing candidate', [
                        'application_id' => $application->id,
                        'candidate_id' => $candidate->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Return existing candidate - password will be handled by ensureCandidateHasTemporaryPassword
            return $candidate;
        }
        
        // Create new candidate account
        $temporaryPassword = Str::random(12); // Generate secure random password
        
        try {
            $candidate = Candidate::create([
                'name' => $application->name,
                'email' => $application->email,
                'password' => Hash::make($temporaryPassword),
                'email_verified_at' => now(), // Auto-verify since they applied
            ]);
            
            // Link application to candidate
            if (\Schema::hasColumn('job_applications', 'candidate_id')) {
                try {
                    $application->update(['candidate_id' => $candidate->id]);
                } catch (\Exception $e) {
                    Log::warning('Failed to link application to candidate after creation', [
                        'application_id' => $application->id,
                        'candidate_id' => $candidate->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Store password temporarily in a property for email (not persisted)
            $candidate->temporary_password = $temporaryPassword;
            
            Log::info('Created new candidate account during sieving', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
                'email' => $candidate->email,
            ]);
            
            return $candidate;
        } catch (\Exception $e) {
            Log::error('Failed to create candidate account during sieving', [
                'application_id' => $application->id,
                'email' => $application->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Ensure existing candidate has a temporary password for email sending
     */
    private function ensureCandidateHasTemporaryPassword(Candidate $candidate, JobApplication $application): Candidate
    {
        // Generate a new temporary password for existing candidates
        $temporaryPassword = Str::random(12);
        
        try {
            $candidate->update([
                'password' => Hash::make($temporaryPassword),
            ]);
            
            // Store password temporarily in a property for email (not persisted)
            $candidate->temporary_password = $temporaryPassword;
            
            Log::info('Generated new temporary password for existing candidate', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update password for existing candidate', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            // Continue without temporary password - email will be sent without credentials
        }
        
        return $candidate;
    }
}

