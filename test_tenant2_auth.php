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

echo "Testing different authentication for tenant 2...\n";
echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "Client ID: $clientId\n";
echo "Sender ID: $senderId\n\n";

$payload = [
    'client_id' => (int) $clientId,
    'channel' => 'sms',
    'recipient' => '254728883160',
    'sender' => $senderId,
    'body' => 'Test message from tenant 2',
];

// Test different auth methods
$authMethods = [
    'X-API-KEY' => [
        'X-API-KEY' => $apiKey,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'Authorization Bearer' => [
        'Authorization' => 'Bearer ' . $apiKey,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'API-Key in payload' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
];

foreach ($authMethods as $method => $headers) {
    echo "Testing: $method\n";
    
    try {
        $httpClient = Http::timeout(15)->withHeaders($headers);
        
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $testPayload = $payload;
        if ($method === 'API-Key in payload') {
            $testPayload['api_key'] = $apiKey;
        }
        
        $response = $httpClient->post($apiUrl . '/2/messages/send', $testPayload);
        
        echo "Status: " . $response->status() . "\n";
        echo "Response: " . $response->body() . "\n\n";
        
        if ($response->successful()) {
            echo "✅ SUCCESS! Auth method: $method\n";
            break;
        }
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}

// Test different endpoints
echo "\nTesting different endpoints...\n";
$endpoints = [
    '/2/messages/send',
    '/messages/send',
    '/v2/messages/send',
];

foreach ($endpoints as $endpoint) {
    echo "Testing endpoint: $endpoint\n";
    
    try {
        $httpClient = Http::timeout(15)->withHeaders([
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $response = $httpClient->post($apiUrl . $endpoint, $payload);
        
        echo "Status: " . $response->status() . "\n";
        
        if ($response->status() !== 403 && $response->status() !== 404) {
            echo "Response: " . $response->body() . "\n";
            if ($response->successful()) {
                echo "✅ SUCCESS! Endpoint: $endpoint\n";
                break;
            }
        }
        echo "\n";
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}
