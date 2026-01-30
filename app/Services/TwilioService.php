<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class TwilioService
{
    private $client;

    public function __construct()
    {
        if ($this->isEnabled()) {
            $this->client = new Client(
                config('integrations.twilio.account_sid'),
                config('integrations.twilio.auth_token')
            );
        }
    }

    public function isEnabled(): bool
    {
        return config('integrations.twilio.enabled') && 
               !empty(config('integrations.twilio.account_sid')) && 
               !empty(config('integrations.twilio.auth_token'));
    }

    /**
     * Send an SMS notification
     */
    public function sendSms(string $to, string $message): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => config('integrations.twilio.phone_number'),
                    'body' => $message,
                ]
            );

            return true;
        } catch (TwilioException $e) {
            \Log::error('Twilio SMS error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk SMS messages
     */
    public function sendBulkSms(array $recipients, string $message): array
    {
        $results = [];

        foreach ($recipients as $phone) {
            $results[$phone] = $this->sendSms($phone, $message);
        }

        return $results;
    }

    /**
     * Send SMS for job application status update
     */
    public function notifyJobApplicationStatus(string $phone, string $candidateName, string $jobTitle, string $status): bool
    {
        $statusText = match($status) {
            'sieving_passed' => 'has moved to the next stage',
            'sieving_failed' => 'has not progressed',
            'aptitude_passed' => 'passed the aptitude test',
            'aptitude_failed' => 'did not pass the aptitude test',
            'interview_passed' => 'has been selected for an interview',
            'interview_failed' => 'was not selected after the interview',
            'hired' => 'Congratulations! You have been hired',
            default => 'status has been updated',
        };

        $message = "Hi {$candidateName}, your application for {$jobTitle} {$statusText}. " .
                   "Login to your account for more details. Fortress Lenders";

        return $this->sendSms($phone, $message);
    }

    /**
     * Send SMS for loan application status update
     */
    public function notifyLoanApplicationStatus(string $phone, string $applicantName, string $loanType, string $status, string $amount = null): bool
    {
        $statusText = match($status) {
            'pending' => 'is under review',
            'approved' => 'has been approved',
            'rejected' => 'was not approved at this time',
            'disbursed' => 'has been disbursed',
            default => 'status has been updated',
        };

        $message = "Hi {$applicantName}, your {$loanType} loan application for KES {$amount} {$statusText}. " .
                   "Login to your account for details. Fortress Lenders";

        return $this->sendSms($phone, $message);
    }

    /**
     * Verify phone number (send verification code)
     */
    public function sendVerificationCode(string $phone): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        try {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $message = "Your Fortress Lenders verification code is: {$code}. Do not share this code.";
            
            $result = $this->sendSms($phone, $message);
            
            if ($result) {
                // Store code in cache for verification later (5 minutes expiry)
                cache()->put("phone_verification_{$phone}", $code, now()->addMinutes(5));
            }

            return $result;
        } catch (TwilioException $e) {
            \Log::error('Twilio verification code error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify a phone code
     */
    public function verifyCode(string $phone, string $code): bool
    {
        $cachedCode = cache()->get("phone_verification_{$phone}");
        return $cachedCode && hash_equals($cachedCode, $code);
    }
}
