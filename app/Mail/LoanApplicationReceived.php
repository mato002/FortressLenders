<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanApplication $application)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('New Loan Application Received')
            ->markdown('emails.loan.application-received', [
                'application' => $this->application,
            ]);
    }
}







