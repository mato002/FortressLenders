<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function build(): self
    {
        $this->application->loadMissing('jobPost');
        
        return $this
            ->subject('Thank You for Applying to Fortress Lenders Limited - Application Received')
            ->view('emails.job.application-confirmation', [
                'application' => $this->application,
            ]);
    }
}

