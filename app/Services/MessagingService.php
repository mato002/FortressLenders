<?php

namespace App\Services;

use App\Models\ContactMessageReply;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessagingService
{
    /**
     * Send a message via the specified channel
     */
    public function send(ContactMessageReply $reply): bool
    {
        try {
            switch ($reply->channel) {
                case 'email':
                    return $this->sendEmail($reply);
                case 'sms':
                    return $this->sendSMS($reply);
                case 'whatsapp':
                    return $this->sendWhatsApp($reply);
                default:
                    throw new \Exception("Unknown channel: {$reply->channel}");
            }
        } catch (\Exception $e) {
            $reply->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("Failed to send {$reply->channel} message", [
                'reply_id' => $reply->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send email message
     */
    protected function sendEmail(ContactMessageReply $reply): bool
    {
        try {
            Mail::raw($reply->message, function ($message) use ($reply) {
                $message->to($reply->recipient)
                    ->subject('Re: ' . ($reply->contactMessage->subject ?? 'Your Inquiry'));
            });

            $reply->update([
                'status' => 'sent',
                'metadata' => ['sent_at' => now()->toIso8601String()],
            ]);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send SMS via BulkSMS CRM API
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
     * Send WhatsApp message via BulkSMS CRM API
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

