<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
$apiKey = config('services.bulksms.api_key');
$senderId = config('services.bulksms.sender_id', 'FORTRESS');

echo "Testing second tenant with different client IDs...\n";
echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "Sender ID: $senderId\n\n";

// Test different client IDs for tenant 2
$clientIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

foreach ($clientIds as $testClientId) {
    echo "Testing client_id: $testClientId\n";
    
    $payload = [
        'client_id' => (int) $testClientId,
        'channel' => 'sms',
        'recipient' => '254728883160',
        'sender' => $senderId,
        'body' => 'Test message from second tenant',
    ];
    
    try {
        $httpClient = Http::timeout(15)->withHeaders([
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
                echo "✅ SUCCESS! Correct client_id for tenant 2 is: $testClientId\n";
                break;
            }
        } else {
            echo "Response: " . $response->body() . "\n";
        }
        echo "\n";
        
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
    
    echo "---\n";
}
