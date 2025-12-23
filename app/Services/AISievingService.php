<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\JobSievingCriteria;
use App\Models\AISievingDecision;
use App\Mail\AptitudeTestInvitation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AISievingService
{
    /**
     * Evaluate a job application using AI sieving
     */
    public function evaluate(JobApplication $application): AISievingDecision
    {
        $jobPost = $application->jobPost;
        
        // Get or create default criteria
        $criteria = $jobPost->sievingCriteria ?? $this->getDefaultCriteria($jobPost);
        
        // Calculate rule-based score
        $ruleScore = $this->calculateRuleBasedScore($application, $criteria);
        
        // Calculate AI confidence (simplified - can be enhanced with LLM)
        $confidence = $this->calculateConfidence($application, $ruleScore);
        
        // Determine decision
        $decision = $this->makeDecision($ruleScore, $confidence, $criteria);
        
        // Extract strengths and weaknesses
        $strengths = $this->extractStrengths($application, $criteria);
        $weaknesses = $this->extractWeaknesses($application, $criteria);
        
        // Generate reasoning
        $reasoning = $this->generateReasoning($application, $ruleScore, $decision, $strengths, $weaknesses);
        
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
        
        return JobSievingCriteria::create([
            'job_post_id' => $jobPost->id,
            'criteria_json' => $defaultCriteria,
            'created_by' => auth()->id(),
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
    private function calculateConfidence(JobApplication $application, int $score): float
    {
        // Higher confidence for extreme scores
        if ($score >= 80 || $score <= 30) {
            return 0.90;
        } elseif ($score >= 70 || $score <= 40) {
            return 0.85;
        } else {
            // Lower confidence for middle scores
            return 0.70;
        }
    }

    /**
     * Make decision based on score and confidence
     */
    private function makeDecision(int $score, float $confidence, JobSievingCriteria $criteria): string
    {
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
    private function extractStrengths(JobApplication $application, JobSievingCriteria $criteria): array
    {
        $strengths = [];
        
        if (!empty($application->education_level)) {
            $strengths[] = "Education: {$application->education_level}";
        }
        
        if (!empty($application->current_job_title)) {
            $strengths[] = "Current Position: {$application->current_job_title}";
        }
        
        if (!empty($application->relevant_skills)) {
            $strengths[] = "Relevant Skills: " . substr($application->relevant_skills, 0, 100);
        }
        
        return $strengths;
    }

    /**
     * Extract weaknesses
     */
    private function extractWeaknesses(JobApplication $application, JobSievingCriteria $criteria): array
    {
        $weaknesses = [];
        
        if (empty($application->education_level)) {
            $weaknesses[] = "Education level not specified";
        }
        
        if (empty($application->work_experience) && empty($application->current_job_title)) {
            $weaknesses[] = "No work experience provided";
        }
        
        if (strlen($application->why_interested ?? '') < 50) {
            $weaknesses[] = "Why interested response is too brief";
        }
        
        return $weaknesses;
    }

    /**
     * Generate reasoning text
     */
    private function generateReasoning(JobApplication $application, int $score, string $decision, array $strengths, array $weaknesses): string
    {
        $reasoning = "AI Evaluation Score: {$score}/100\n\n";
        $reasoning .= "Decision: " . strtoupper($decision) . "\n\n";
        
        if (!empty($strengths)) {
            $reasoning .= "Strengths:\n" . implode("\n", array_map(fn($s) => "- {$s}", $strengths)) . "\n\n";
        }
        
        if (!empty($weaknesses)) {
            $reasoning .= "Weaknesses:\n" . implode("\n", array_map(fn($w) => "- {$w}", $weaknesses)) . "\n\n";
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
        
        // Only auto-update if high confidence
        if ($aiDecision->ai_decision === 'pass' && 
            $aiDecision->ai_confidence >= $criteria->auto_pass_confidence) {
            $newStatus = 'sieving_passed';
        } elseif ($aiDecision->ai_decision === 'reject' && 
                  $aiDecision->ai_confidence >= $criteria->auto_reject_confidence) {
            $newStatus = 'sieving_rejected';
        } elseif ($aiDecision->ai_decision === 'manual_review') {
            $newStatus = 'pending_manual_review';
        }
        
        // Update status if it changed
        if ($newStatus !== $previousStatus) {
            $application->update(['status' => $newStatus]);
            
            // Record status change in history
            \App\Models\JobApplicationStatusHistory::create([
                'job_application_id' => $application->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'changed_by' => null, // System/AI change
                'source' => 'ai_sieving',
                'notes' => "AI sieving decision: {$aiDecision->ai_decision} (Score: {$aiDecision->ai_score}/100, Confidence: " . number_format($aiDecision->ai_confidence * 100, 1) . "%)",
            ]);
            
            // Send email notification if status changed to sieving_passed
            if ($newStatus === 'sieving_passed') {
                try {
                    Mail::to($application->email)->send(new AptitudeTestInvitation($application));
                } catch (\Exception $e) {
                    Log::error('Failed to send aptitude test invitation email', [
                        'application_id' => $application->id,
                        'error' => $e->getMessage()
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
}

