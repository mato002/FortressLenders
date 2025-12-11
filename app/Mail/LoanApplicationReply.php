<?php

namespace App\Mail;

use App\Models\LoanApplication;
use App\Models\LoanApplicationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApplicationReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LoanApplication $application,
        public LoanApplicationMessage $message
    ) {
    }

    public function build(): self
    {
        $subject = 'Re: Your Loan Application - ' . $this->application->loan_type;
        
        return $this
            ->subject($subject)
            ->markdown('emails.loan.application-reply', [
                'application' => $this->application,
                'message' => $this->message,
            ]);
    }
}

