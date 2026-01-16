<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Dispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\MessagingService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The message model instance (JobApplicationMessage|LoanApplicationMessage|ContactMessageReply)
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $message;

    /** Number of attempts */
    public int $tries = 3;

    /**
     * Backoff schedule in seconds for retries.
     *
     * @return array<int>
     */
    public function backoff(): array
    {
        return [60, 300, 900];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(Model $message)
    {
        $this->message = $message;
        $this->onQueue('messages');
    }

    /**
     * Execute the job.
     */
    public function handle(MessagingService $messagingService): void
    {
        // Refresh model to ensure latest state
        $this->message->refresh();

        $sent = $messagingService->send($this->message);

        if (! $sent) {
            // Let the job fail so it can be retried according to $tries/backoff
            throw new \Exception('Message sending failed; will retry if attempts remain.');
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        try {
            $this->message->refresh();
            $this->message->update([
                'status' => 'failed',
                'error_message' => $this->message->error_message ?? $exception->getMessage(),
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to mark message as failed in SendMessageJob::failed', ['error' => $e->getMessage()]);
        }
        Log::error('SendMessageJob failed', ['message_id' => $this->message->id ?? null, 'error' => $exception->getMessage()]);
    }
}
