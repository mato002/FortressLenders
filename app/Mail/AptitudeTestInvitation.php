<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AptitudeTestInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public JobApplication $application,
        public ?Candidate $candidate = null,
        public ?string $temporaryPassword = null
    ) {
    }

    public function build(): self
    {
        $this->application->loadMissing('jobPost');
        
        // Generate secure token for status page access
        $token = md5($this->application->email . $this->application->id . config('app.key'));
        $statusUrl = route('application.status', [
            'application' => $this->application->id,
            'token' => $token
        ]);
        
        // Direct link to aptitude test
        $testUrl = route('aptitude-test.show', $this->application);
        
        // Login URL (candidates use the same login page)
        $loginUrl = route('login');
        
        // Get candidate if not provided
        if (!$this->candidate && $this->application->candidate_id) {
            $this->candidate = $this->application->candidate;
        }
        
        // Get temporary password if candidate has it (from account creation)
        if ($this->candidate && isset($this->candidate->temporary_password)) {
            $this->temporaryPassword = $this->candidate->temporary_password;
        }
        
        return $this
            ->subject('Congratulations! You\'ve Passed Initial Screening - Aptitude Test Required')
            ->view('emails.job.aptitude-test-invitation', [
                'application' => $this->application,
                'statusUrl' => $statusUrl,
                'testUrl' => $testUrl,
                'loginUrl' => $loginUrl,
                'candidate' => $this->candidate,
                'temporaryPassword' => $this->temporaryPassword,
            ]);
    }
}

