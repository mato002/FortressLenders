<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowTokenAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $allocation;
    protected $company;
    protected $percentageRemaining;

    public function __construct($company, $allocation, float $percentageRemaining)
    {
        $this->company = $company;
        $this->allocation = $allocation;
        $this->percentageRemaining = $percentageRemaining;
        $this->onQueue(config('queue.default'));
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $remaining = $this->allocation->remaining_tokens;
        $allocated = $this->allocation->allocated_tokens;

        return (new MailMessage)
            ->subject('Low Token Alert: Action Recommended')
            ->greeting('Hello ' . ($this->company->name ?? 'Admin'))
            ->line("Your account has {$remaining} tokens remaining ({$this->percentageRemaining}% of {$allocated}).")
            ->line('Please consider purchasing or allocating more tokens to avoid service interruptions.')
            ->action('Manage Tokens', url('/admin/company/' . ($this->company->id ?? '')))
            ->line('This is an automated notification.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'company_id' => $this->company->id ?? null,
            'allocation_id' => $this->allocation->id ?? null,
            'remaining_tokens' => $this->allocation->remaining_tokens ?? null,
            'allocated_tokens' => $this->allocation->allocated_tokens ?? null,
            'percentage_remaining' => $this->percentageRemaining,
            'message' => 'Token balance is low. Please top up to avoid interruptions.',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
