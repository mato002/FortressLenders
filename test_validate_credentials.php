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

echo "Validating BulkSMS credentials...\n\n";

// Test account info endpoints
$testEndpoints = [
    '/account',
    '/user', 
    '/me',
    '/client',
    '/balance',
    '/2/account',
    '/2/user',
    '/2/me',
    '/2/client',
    '/2/balance',
];

foreach ($testEndpoints as $endpoint) {
    echo "Testing: $endpoint\n";
    
    try {
        $httpClient = Http::timeout(30)->withHeaders([
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        // Try GET request for account info
        $response = $httpClient->get($apiUrl . $endpoint);
        
        echo "GET Status: " . $response->status() . "\n";
        echo "GET Response: " . substr($response->body(), 0, 200) . "...\n\n";
        
        if ($response->successful()) {
            echo "SUCCESS! Account info available at: $endpoint\n";
            break;
        }
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}

// Test with different client IDs (common ones)
echo "\nTesting different client IDs...\n";
$clientIds = [1, 2, 3, 10, 11, 12, 100, 1000];

foreach ($clientIds as $testClientId) {
    echo "Testing client_id: $testClientId\n";
    
    $payload = [
        'client_id' => (int) $testClientId,
        'channel' => 'sms',
        'recipient' => '254728883160',
        'sender' => $senderId,
        'body' => 'Test message',
    ];
    
    try {
        $httpClient = Http::timeout(10)->withHeaders([
            'X-API-KEY' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $response = $httpClient->post($apiUrl . '/2/messages/send', $payload);
        
        echo "Status: " . $response->status() . "\n";
        
        if ($response->status() !== 403) {
            echo "Response: " . $response->body() . "\n";
            if ($response->successful()) {
                echo "SUCCESS! Correct client_id is: $testClientId\n";
                break;
            }
        }
        echo "\n";
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
}
