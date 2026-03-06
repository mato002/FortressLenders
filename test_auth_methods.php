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

echo "Testing different authentication methods...\n\n";

$endpoint = "/2/messages/send";
$payload = [
    'client_id' => (int) $clientId,
    'channel' => 'sms',
    'recipient' => '254728883160',
    'sender' => $senderId,
    'body' => 'Test message from Fortress Lenders',
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
    'Authorization Basic' => [
        'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
    'API-Key header' => [
        'API-Key' => $apiKey,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
];

foreach ($authMethods as $method => $headers) {
    echo "Testing: $method\n";
    
    try {
        $httpClient = Http::timeout(30)->withHeaders($headers);
        
        if (app()->environment('local')) {
            $httpClient = $httpClient->withoutVerifying();
        }
        
        $response = $httpClient->post($apiUrl . $endpoint, $payload);
        
        echo "Status: " . $response->status() . "\n";
        echo "Response: " . $response->body() . "\n\n";
        
        if ($response->successful()) {
            echo "SUCCESS! Auth method: $method\n";
            break;
        }
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}

// Also test adding API key to payload
echo "Testing API key in payload...\n";
$payloadWithKey = array_merge($payload, ['api_key' => $apiKey]);

try {
    $httpClient = Http::timeout(30)->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ]);
    
    if (app()->environment('local')) {
        $httpClient = $httpClient->withoutVerifying();
    }
    
    $response = $httpClient->post($apiUrl . $endpoint, $payloadWithKey);
    
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . $response->body() . "\n\n";
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n\n";
}
