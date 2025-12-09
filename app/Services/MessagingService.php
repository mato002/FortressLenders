<?php

namespace App\Services;

use App\Models\ContactMessageReply;
use App\Models\JobApplicationMessage;
use App\Models\LoanApplicationMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessagingService
{
    /**
     * Send a message via the specified channel
     */
    public function send(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        try {
            switch ($message->channel) {
                case 'email':
                    return $this->sendEmailMessage($message);
                case 'sms':
                    return $this->sendSMSMessage($message);
                case 'whatsapp':
                    return $this->sendWhatsAppMessage($message);
                default:
                    throw new \Exception("Unknown channel: {$message->channel}");
            }
        } catch (\Exception $e) {
            $message->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("Failed to send {$message->channel} message", [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send email message (generic method that works with all types)
     */
    protected function sendEmailMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        try {
            $subject = $this->getEmailSubject($message);
            
            Mail::raw($message->message, function ($mailMessage) use ($message, $subject) {
                $mailMessage->to($message->recipient)->subject($subject);
            });

            $message->update([
                'status' => 'sent',
                'metadata' => ['sent_at' => now()->toIso8601String()],
            ]);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get email subject based on message type
     */
    protected function getEmailSubject(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): string
    {
        if ($message instanceof ContactMessageReply) {
            return 'Re: ' . ($message->contactMessage->subject ?? 'Your Inquiry');
        } elseif ($message instanceof JobApplicationMessage) {
            // Load relationships if not already loaded
            $message->loadMissing(['jobApplication.jobPost']);
            $jobTitle = optional($message->jobApplication)->jobPost->title ?? 'Your Application';
            return 'Re: Your Job Application - ' . $jobTitle;
        } elseif ($message instanceof LoanApplicationMessage) {
            $message->loadMissing('loanApplication');
            $loanType = optional($message->loanApplication)->loan_type ?? 'Loan';
            return 'Re: Your Loan Application - ' . $loanType;
        }
        return 'Message from Fortress Lenders';
    }


    /**
     * Send SMS message (generic method)
     */
    protected function sendSMSMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        if ($message instanceof ContactMessageReply) {
            return $this->sendSMS($message);
        } elseif ($message instanceof JobApplicationMessage) {
            return $this->sendSMSJobApplication($message);
        } else {
            return $this->sendSMSLoanApplication($message);
        }
    }

    /**
     * Send SMS via BulkSMS CRM API (ContactMessageReply)
     * API Documentation: https://crm.pradytecai.com/api-documentation
     */
    protected function sendSMS(ContactMessageReply $reply): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($reply->recipient);
            
            Log::info('Sending SMS', [
                'api_url' => $apiUrl,
                'phone' => $phone,
                'sender_id' => $senderId,
                'client_id' => $clientId,
                'message_length' => strlen($reply->message),
            ]);
            
            // BulkSMS CRM API request
            // Based on API documentation: https://crm.pradytecai.com/api-documentation
            // Endpoint format: /api/{client_id}/messages/send (unified - recommended)
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            // Payload according to API documentation
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $reply->message,
            ];
            
            Log::info('SMS API Request', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'api_key_set' => !empty($apiKey),
            ]);
            
            // API uses X-API-KEY header (not Bearer token)
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);
            
            Log::info('SMS API Response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check API response status (API returns { "status": "success/error", ... })
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    Log::error('SMS API returned error', [
                        'response' => $responseData,
                        'reply_id' => $reply->id,
                    ]);
                    throw new \Exception($errorMessage);
                }
                
                // Success - update reply status
                $reply->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                Log::info('SMS sent successfully', [
                    'reply_id' => $reply->id,
                    'message_id' => $responseData['data']['id'] ?? null,
                ]);
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                Log::error('SMS API failed', [
                    'status_code' => $statusCode,
                    'error_body' => $errorBody,
                    'reply_id' => $reply->id,
                ]);
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'reply_id' => $reply->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Send SMS via BulkSMS CRM API (JobApplicationMessage)
     */
    protected function sendSMSJobApplication(JobApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send WhatsApp message (generic method)
     */
    protected function sendWhatsAppMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        if ($message instanceof ContactMessageReply) {
            return $this->sendWhatsApp($message);
        } elseif ($message instanceof JobApplicationMessage) {
            return $this->sendWhatsAppJobApplication($message);
        } else {
            return $this->sendWhatsAppLoanApplication($message);
        }
    }

    /**
     * Send WhatsApp message via BulkSMS CRM API (ContactMessageReply)
     * Uses the unified messages endpoint with channel='whatsapp'
     * API Documentation: https://crm.pradytecai.com/api-documentation
     */
    protected function sendWhatsApp(ContactMessageReply $reply): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('WhatsApp API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($reply->recipient);
            
            Log::info('Sending WhatsApp', [
                'api_url' => $apiUrl,
                'phone' => $phone,
                'sender_id' => $senderId,
                'client_id' => $clientId,
            ]);
            
            // Use unified messages endpoint with channel='whatsapp'
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'whatsapp',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $reply->message,
            ];
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);
            
            Log::info('WhatsApp API Response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check API response status
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'WhatsApp API returned error status';
                    Log::error('WhatsApp API returned error', [
                        'response' => $responseData,
                        'reply_id' => $reply->id,
                    ]);
                    throw new \Exception($errorMessage);
                }
                
                $reply->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                Log::info('WhatsApp sent successfully', ['reply_id' => $reply->id]);
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "WhatsApp API request failed with status {$statusCode}");
                
                Log::error('WhatsApp API failed', [
                    'status_code' => $statusCode,
                    'error_body' => $errorBody,
                    'reply_id' => $reply->id,
                ]);
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp sending exception', [
                'reply_id' => $reply->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Send WhatsApp via BulkSMS CRM API (JobApplicationMessage)
     */
    protected function sendWhatsAppJobApplication(JobApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('WhatsApp API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'whatsapp',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'WhatsApp API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "WhatsApp API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send SMS via BulkSMS CRM API (LoanApplicationMessage)
     */
    protected function sendSMSLoanApplication(LoanApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send WhatsApp via BulkSMS CRM API (LoanApplicationMessage)
     */
    protected function sendWhatsAppLoanApplication(LoanApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('WhatsApp API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/{$clientId}/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'whatsapp',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $response = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'WhatsApp API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "WhatsApp API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If it starts with 0, replace with country code
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }

        // If it doesn't start with country code, add it
        if (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}

