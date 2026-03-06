<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
$apiKey = config('services.bulksms.api_key');
$clientId = config('services.bulksms.client_id');
$senderId = config('services.bulksms.sender_id', 'FORTRESS');

echo "Testing BulkSMS API directly...\n";
echo "API URL: $apiUrl\n";
echo "Client ID: $clientId\n";
echo "Sender ID: $senderId\n";
echo "API Key: " . substr($apiKey, 0, 10) . "...\n\n";

// Test different endpoints
$endpoints = [
    "/2/messages/send",
    "/messages/send", 
    "/v2/messages/send",
    "/api/2/messages/send"
];

foreach ($endpoints as $endpoint) {
    echo "Testing endpoint: $endpoint\n";
    
    $payload = [
        'client_id' => (int) $clientId,
        'channel' => 'sms',
        'recipient' => '254728883160',
        'sender' => $senderId,
        'body' => 'Test message from Fortress Lenders',
    ];
    
    try {
        $httpClient = Http::timeout(30)->withHeaders([
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        // Disable SSL verification in local development only
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $response = $httpClient->post($apiUrl . $endpoint, $payload);
        
        echo "Status: " . $response->status() . "\n";
        echo "Response: " . $response->body() . "\n\n";
        
        if ($response->successful()) {
            echo "SUCCESS! This endpoint works.\n";
            break;
        }
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}
