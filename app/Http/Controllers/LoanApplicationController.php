<?php

namespace App\Http\Controllers;

use App\Mail\LoanApplicationConfirmation;
use App\Mail\LoanApplicationReceived;
use App\Models\LoanApplication;
use App\Models\LoanProductType;
use App\Models\Branch;
use App\Models\LoanCalculatorLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LoanApplicationController extends Controller
{
    public function create(): View
    {
        $loanProductTypes = LoanProductType::where('is_active', true)
            ->orderBy('min_loan_amount')
            ->orderBy('max_loan_amount')
            ->orderBy('display_order')
            ->get();
        
        // Group products by loan amount ranges
        $loanRanges = [];
        foreach ($loanProductTypes as $product) {
            $rangeKey = $product->min_loan_amount . '-' . $product->max_loan_amount;
            
            if (!isset($loanRanges[$rangeKey])) {
                $loanRanges[$rangeKey] = [
                    'min_amount' => $product->min_loan_amount,
                    'max_amount' => $product->max_loan_amount,
                    'products' => [],
                ];
            }
            
            $loanRanges[$rangeKey]['products'][] = $product;
        }
        
        // Sort ranges by min amount
        usort($loanRanges, function($a, $b) {
            return $a['min_amount'] <=> $b['min_amount'];
        });
        
        // Get unique cities/regions from active branches
        $regions = Branch::active()
            ->whereNotNull('city')
            ->orderBy('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();
        
        return view('apply-loan', compact('loanRanges', 'loanProductTypes', 'regions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            // Expect 9 Kenyan digits (without +254) from the form
            'phone' => ['required', 'regex:/^\d{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'town' => ['required', 'string', 'max:255'],
            'residence' => ['nullable', 'string', 'max:255'],
            // client_type and loan_type removed from front-end; keep optional here if ever sent
            'client_type' => ['nullable', 'in:business,employed,casual,student'],
            'loan_type' => ['nullable', 'string', 'max:255'],
            'amount_requested' => ['required', 'numeric', 'min:0'],
            'repayment_period' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string'],
            'agree_to_terms' => ['accepted'],
        ]);

        // Normalize phone to full E.164 format with +254 prefix
        $phoneDigits = preg_replace('/\D/', '', $data['phone']);
        if (strlen($phoneDigits) === 9) {
            $normalizedPhone = '+254' . $phoneDigits;
        } else {
            // Fallback: store as submitted if it doesn't match expected length
            $normalizedPhone = $data['phone'];
        }

        $application = LoanApplication::create([
            'full_name' => $data['full_name'],
            'phone' => $normalizedPhone,
            'email' => $data['email'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'town' => $data['town'] ?? null,
            'residence' => $data['residence'] ?? null,
            'client_type' => $data['client_type'] ?? null,
            'loan_type' => $data['loan_type'] ?? null,
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

    /**
     * Store a loan calculator WhatsApp lead.
     */
    public function storeCalculatorLead(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => [
                'required',
                'string',
                'max:30',
                // Basic international format validation e.g. +2547...
                'regex:/^\+[1-9]\d{6,14}$/',
            ],
            'loan_amount' => ['required', 'numeric', 'min:1'],
            'loan_duration_value' => ['required', 'integer', 'min:1'],
            'loan_duration_unit' => ['required', 'in:weeks,months'],
            'service_charge' => ['required', 'numeric', 'min:0'],
            'total_repayment' => ['required', 'numeric', 'min:1'],
            'payment_frequency' => ['nullable', 'in:weekly,monthly'],
        ]);

        LoanCalculatorLead::create($data);

        return response()->json([
            'success' => true,
        ]);
    }
}









