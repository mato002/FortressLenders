<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfInterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'answers',
        'total_score',
        'total_possible_score',
        'is_passed',
        'pass_threshold',
        'started_at',
        'completed_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'answers' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    /**
     * Calculate score and update application self-interview fields.
     */
    public function calculateScore(): void
    {
        $score = 0;
        $totalPossible = 0;

        foreach ($this->answers ?? [] as $questionId => $answer) {
            $question = SelfInterviewQuestion::find($questionId);
            if (! $question) {
                continue;
            }

            // Only questions with a defined correct_answer are auto‑marked.
            // Open‑ended questions (no correct_answer) are for qualitative review
            // and do NOT reduce the candidate's percentage score.
            if ($question->correct_answer !== null && $question->correct_answer !== '') {
                $totalPossible += $question->points;

                // Handle different question types
                if ($question->isMultipleChoice()) {
                    // Multiple choice: exact string match (case-insensitive)
                    if (strtolower(trim($answer)) === strtolower(trim($question->correct_answer))) {
                        $score += $question->points;
                    }
                } elseif ($question->isCalculation()) {
                    // Calculation: numeric comparison (handles 42, 42.0, 42.00, etc.)
                    if ($this->isNumericAnswerCorrect($answer, $question->correct_answer)) {
                        $score += $question->points;
                    }
                } else {
                    // Text questions: require manual review, don't auto-score
                    // They will be marked as needing review
                }
            }
        }

        // If there are no auto‑marked questions, treat the self interview as 100%
        $percentageScore = $totalPossible > 0 ? round(($score / $totalPossible) * 100) : 100;

        $this->total_score = $score;
        $this->total_possible_score = $totalPossible;
        $this->is_passed = $percentageScore >= $this->pass_threshold;
        $this->save();

        $application = $this->application;

        $application->update([
            'self_interview_score' => $percentageScore,
            'self_interview_passed' => $this->is_passed,
            'self_interview_completed_at' => now(),
        ]);

        // Record in status history for auditing (does not change status)
        \App\Models\JobApplicationStatusHistory::create([
            'job_application_id' => $application->id,
            'previous_status' => $application->status,
            'new_status' => $application->status,
            'changed_by' => null,
            'source' => 'self_interview_completion',
            'notes' => "Self interview completed. Score: {$percentageScore}% ({$score}/{$totalPossible}). " . ($this->is_passed ? 'Passed' : 'Failed'),
        ]);
    }

    /**
     * Check if numeric answer is correct (handles different formats)
     */
    private function isNumericAnswerCorrect(?string $candidateAnswer, ?string $correctAnswer): bool
    {
        if (empty($candidateAnswer) || empty($correctAnswer)) {
            return false;
        }

        // Remove whitespace and convert to lowercase
        $candidateAnswer = trim(strtolower($candidateAnswer));
        $correctAnswer = trim(strtolower($correctAnswer));

        // Try numeric comparison
        if (is_numeric($candidateAnswer) && is_numeric($correctAnswer)) {
            // Compare as floats to handle decimals
            $candidateFloat = (float) $candidateAnswer;
            $correctFloat = (float) $correctAnswer;
            
            // Allow small floating point differences (0.01 tolerance)
            return abs($candidateFloat - $correctFloat) < 0.01;
        }

        // Fallback to string comparison if not numeric
        return $candidateAnswer === $correctAnswer;
    }
}


