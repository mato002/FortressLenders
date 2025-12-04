<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanApplication $application)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Your Loan Application Has Been Received')
            ->markdown('emails.loan.application-confirmation', [
                'application' => $this->application,
            ]);
    }
}


