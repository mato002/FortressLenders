<?php

namespace Tests\Feature;

use App\Models\AptitudeTestQuestion;
use App\Models\AptitudeTestSession;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\SelfInterviewQuestion;
use App\Models\SelfInterviewSession;
use App\Models\Company;
use App\Models\User;
use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a company
        $this->company = Company::factory()->create();
        
        // Create an admin user
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'company_id' => $this->company->id,
        ]);
        
        // Create a job post
        $this->jobPost = JobPost::factory()->create([
            'company_id' => $this->company->id,
            'is_active' => true,
        ]);
        
        // Create a candidate
        $this->candidate = Candidate::factory()->create();
        
        // Create a job application
        $this->application = JobApplication::factory()->create([
            'job_post_id' => $this->jobPost->id,
            'company_id' => $this->company->id,
            'candidate_id' => $this->candidate->id,
            'email' => $this->candidate->email,
            'status' => 'sieving_passed',
        ]);
    }

    /** Test 1: Create Aptitude Test Questions */
    public function test_can_create_aptitude_test_questions(): void
    {
        $this->actingAs($this->admin);
        
        // Test Multiple Choice Question
        $mcQuestion = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'multiple_choice',
            'question' => 'What is 5 + 3?',
            'options' => ['a' => '6', 'b' => '7', 'c' => '8', 'd' => '9'],
            'correct_answer' => 'c',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertTrue($mcQuestion->isMultipleChoice());
        $this->assertFalse($mcQuestion->isCalculation());
        $this->assertFalse($mcQuestion->isText());
        
        // Test Calculation Question
        $calcQuestion = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'calculation',
            'question' => 'Calculate: 15 × 4',
            'correct_answer' => '60',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertFalse($calcQuestion->isMultipleChoice());
        $this->assertTrue($calcQuestion->isCalculation());
        $this->assertFalse($calcQuestion->isText());
        
        // Test Text Question
        $textQuestion = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'scenario',
            'question_type' => 'text',
            'question' => 'Describe a challenging project',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertFalse($textQuestion->isMultipleChoice());
        $this->assertFalse($textQuestion->isCalculation());
        $this->assertTrue($textQuestion->isText());
    }

    /** Test 2: Create Self Interview Questions */
    public function test_can_create_self_interview_questions(): void
    {
        $this->actingAs($this->admin);
        
        // Test Multiple Choice Question
        $mcQuestion = SelfInterviewQuestion::create([
            'company_id' => $this->company->id,
            'question_type' => 'multiple_choice',
            'question' => 'Years of experience?',
            'options' => ['a' => '0-1', 'b' => '2-3', 'c' => '4-5', 'd' => '5+'],
            'correct_answer' => 'c',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertTrue($mcQuestion->isMultipleChoice());
        
        // Test Calculation Question
        $calcQuestion = SelfInterviewQuestion::create([
            'company_id' => $this->company->id,
            'question_type' => 'calculation',
            'question' => '8 hours × 5 days = ?',
            'correct_answer' => '40',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertTrue($calcQuestion->isCalculation());
        
        // Test Text Question
        $textQuestion = SelfInterviewQuestion::create([
            'company_id' => $this->company->id,
            'question_type' => 'text',
            'question' => 'Why do you want this job?',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $this->assertTrue($textQuestion->isText());
    }

    /** Test 3: Aptitude Test Scoring - Multiple Choice */
    public function test_aptitude_test_scoring_multiple_choice(): void
    {
        $question = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'multiple_choice',
            'question' => 'What is 5 + 3?',
            'options' => ['a' => '6', 'b' => '7', 'c' => '8', 'd' => '9'],
            'correct_answer' => 'c',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $session = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => 'c'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        
        $session->calculateScore();
        
        $this->assertEquals(4, $session->total_score);
        $this->assertEquals(4, $session->total_possible_score);
        $this->assertTrue($session->is_passed);
    }

    /** Test 4: Aptitude Test Scoring - Calculation (Different Formats) */
    public function test_aptitude_test_scoring_calculation_formats(): void
    {
        $question = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'calculation',
            'question' => 'Calculate: 15 × 4',
            'correct_answer' => '60',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        // Test with "60"
        $session1 = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => '60'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session1->calculateScore();
        $this->assertEquals(4, $session1->total_score);
        
        // Test with "60.0"
        $session2 = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => '60.0'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session2->calculateScore();
        $this->assertEquals(4, $session2->total_score);
        
        // Test with "60.00"
        $session3 = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => '60.00'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session3->calculateScore();
        $this->assertEquals(4, $session3->total_score);
        
        // Test with wrong answer
        $session4 = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => '61'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session4->calculateScore();
        $this->assertEquals(0, $session4->total_score);
    }

    /** Test 5: Self Interview Scoring - Multiple Choice */
    public function test_self_interview_scoring_multiple_choice(): void
    {
        $question = SelfInterviewQuestion::create([
            'company_id' => $this->company->id,
            'question_type' => 'multiple_choice',
            'question' => 'Years of experience?',
            'options' => ['a' => '0-1', 'b' => '2-3', 'c' => '4-5', 'd' => '5+'],
            'correct_answer' => 'c',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $session = SelfInterviewSession::create([
            'job_application_id' => $this->application->id,
            'answers' => [$question->id => 'c'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        
        $session->calculateScore();
        
        $this->assertEquals(4, $session->total_score);
        $this->assertEquals(4, $session->total_possible_score);
        $this->assertTrue($session->is_passed);
    }

    /** Test 6: Self Interview Scoring - Calculation */
    public function test_self_interview_scoring_calculation(): void
    {
        $question = SelfInterviewQuestion::create([
            'company_id' => $this->company->id,
            'question_type' => 'calculation',
            'question' => '8 hours × 5 days = ?',
            'correct_answer' => '40',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        // Test with "40"
        $session1 = SelfInterviewSession::create([
            'job_application_id' => $this->application->id,
            'answers' => [$question->id => '40'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session1->calculateScore();
        $this->assertEquals(4, $session1->total_score);
        
        // Test with "40.0"
        $session2 = SelfInterviewSession::create([
            'job_application_id' => $this->application->id,
            'answers' => [$question->id => '40.0'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        $session2->calculateScore();
        $this->assertEquals(4, $session2->total_score);
    }

    /** Test 7: Text Questions Don't Auto-Score */
    public function test_text_questions_dont_auto_score(): void
    {
        $question = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'scenario',
            'question_type' => 'text',
            'question' => 'Describe a challenging project',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $session = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [$question->id => 'I worked on a complex project...'],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        
        $session->calculateScore();
        
        // Text questions without correct_answer should not be auto-scored
        $this->assertEquals(0, $session->total_score);
        $this->assertEquals(0, $session->total_possible_score);
    }

    /** Test 8: Mixed Question Types Scoring */
    public function test_mixed_question_types_scoring(): void
    {
        $mcQuestion = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'multiple_choice',
            'question' => 'What is 5 + 3?',
            'options' => ['a' => '6', 'b' => '7', 'c' => '8', 'd' => '9'],
            'correct_answer' => 'c',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $calcQuestion = AptitudeTestQuestion::create([
            'company_id' => $this->company->id,
            'section' => 'numerical',
            'question_type' => 'calculation',
            'question' => 'Calculate: 15 × 4',
            'correct_answer' => '60',
            'points' => 4,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        
        $session = AptitudeTestSession::create([
            'job_application_id' => $this->application->id,
            'questions_answered' => [
                $mcQuestion->id => 'c',  // Correct
                $calcQuestion->id => '60.0',  // Correct (different format)
            ],
            'pass_threshold' => 70,
            'started_at' => now(),
        ]);
        
        $session->calculateScore();
        
        $this->assertEquals(8, $session->total_score);
        $this->assertEquals(8, $session->total_possible_score);
        $this->assertTrue($session->is_passed);
    }
}
