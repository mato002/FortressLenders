<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\MessagingService;
use App\Models\ContactMessageReply;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Get a contact message and user for the reply
    $contactMessage = \App\Models\ContactMessage::first();
    $user = \App\Models\User::first();
    
    if (!$contactMessage) {
        echo "No contact message found. Creating a dummy one...\n";
        $contactMessage = \App\Models\ContactMessage::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '254728883160',
            'subject' => 'Test SMS',
            'message' => 'Test message for SMS'
        ]);
    }
    
    if (!$user) {
        echo "No user found. Please create a user first.\n";
        exit(1);
    }
    
    // Create test message
    $message = new ContactMessageReply([
        'contact_message_id' => $contactMessage->id,
        'sent_by' => $user->id,
        'recipient' => '254728883160',
        'message' => 'Test SMS from Fortress Lenders - ' . date('Y-m-d H:i:s'),
        'channel' => 'sms',
        'status' => 'pending'
    ]);
    
    $message->save();
    echo "Created test message with ID: " . $message->id . "\n";
    
    // Send SMS
    $service = new MessagingService();
    $result = $service->send($message);
    
    echo "SMS " . ($result ? "sent successfully" : "failed") . "\n";
    echo "Final status: " . $message->status . "\n";
    
    if ($message->error_message) {
        echo "Error message: " . $message->error_message . "\n";
    }
    
    if ($message->metadata) {
        echo "Metadata: " . json_encode($message->metadata, JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
