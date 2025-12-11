<?php

namespace App\Http\Controllers;

use App\Mail\LoanApplicationConfirmation;
use App\Mail\LoanApplicationReceived;
use App\Models\LoanApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LoanApplicationController extends Controller
{
    public function create(): View
    {
        return view('apply-loan');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'town' => ['nullable', 'string', 'max:255'],
            'residence' => ['nullable', 'string', 'max:255'],
            'client_type' => ['nullable', 'in:business,employed,casual,student'],
            'loan_type' => ['required', 'string', 'max:255'],
            'amount_requested' => ['required', 'numeric', 'min:0'],
            'repayment_period' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string'],
            'agree_to_terms' => ['accepted'],
        ]);

        $application = LoanApplication::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'town' => $data['town'] ?? null,
            'residence' => $data['residence'] ?? null,
            'client_type' => $data['client_type'] ?? null,
            'loan_type' => $data['loan_type'],
            'amount_requested' => $data['amount_requested'],
            'repayment_period' => $data['repayment_period'],
            'purpose' => $data['purpose'] ?? null,
            'agreed_to_terms' => true,
            'status' => 'pending',
        ]);

        $this->notifyTeam($application);
        $this->acknowledgeApplicant($application);

        return redirect()
            ->route('loan.apply')
            ->with('status', 'Thank you! Your loan application has been received. Our team will contact you shortly.');
    }

    protected function notifyTeam(LoanApplication $application): void
    {
        $recipients = config('loan.notification_recipients', []);

        if (empty($recipients)) {
            return;
        }

        Mail::to($recipients)->send(new LoanApplicationReceived($application));
    }

    protected function acknowledgeApplicant(LoanApplication $application): void
    {
        if (! $application->email) {
            return;
        }

        Mail::to($application->email)->send(new LoanApplicationConfirmation($application));
    }
}








