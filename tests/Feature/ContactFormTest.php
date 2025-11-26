<?php

namespace Tests\Feature;

use App\Mail\ContactMessageConfirmation;
use App\Mail\ContactMessageReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_persists_message_and_sends_mail(): void
    {
        Mail::fake();
        config(['contact.notification_recipients' => ['team@example.com']]);

        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'subject' => 'Loan Inquiry',
            'message' => 'Tell me more about your loans.',
            'company' => '',
        ]);

        $response->assertSessionHas('status')->assertRedirect('/contact');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Loan Inquiry',
        ]);

        Mail::assertSent(ContactMessageReceived::class, 1);
        Mail::assertSent(ContactMessageConfirmation::class, 1);
    }

    public function test_honeypot_field_blocks_spam(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'phone' => null,
            'subject' => 'Spam',
            'message' => 'Spam body',
            'company' => 'Bot Corp',
        ]);

        $response->assertSessionHasErrors()->assertRedirect('/contact');
        $this->assertDatabaseCount('contact_messages', 0);
    }
}

