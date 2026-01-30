<?php

namespace App\Services;

use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeHandle;
use Exception;

class SendGridService
{
    private $sendgrid;

    public function __construct()
    {
        if (config('integrations.sendgrid.enabled')) {
            $this->sendgrid = new \SendGrid(config('integrations.sendgrid.api_key'));
        }
    }

    public function isEnabled(): bool
    {
        return config('integrations.sendgrid.enabled') && !empty(config('integrations.sendgrid.api_key'));
    }

    /**
     * Send a transactional email via SendGrid
     */
    public function send(string $to, string $subject, string $htmlContent, array $options = []): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        try {
            $mail = new Mail();
            $mail->setFrom(
                config('integrations.sendgrid.from_email'),
                config('integrations.sendgrid.from_name')
            );
            $mail->setSubject($subject);
            $mail->addTo($to);
            $mail->addContent('text/html', $htmlContent);

            // Add Reply-To if specified
            if (isset($options['reply_to'])) {
                $mail->setReplyTo($options['reply_to']);
            }

            // Add categories for tracking
            if (isset($options['categories'])) {
                foreach ((array) $options['categories'] as $category) {
                    $mail->addCategory($category);
                }
            }

            $response = $this->sendgrid->send($mail);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;
        } catch (Exception $e) {
            \Log::error('SendGrid error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk emails
     */
    public function sendBulk(array $recipients, string $subject, string $htmlContent, array $options = []): array
    {
        $results = [];

        foreach ($recipients as $email => $name) {
            $results[$email] = $this->send($email, $subject, $htmlContent, $options);
        }

        return $results;
    }

    /**
     * Get SendGrid statistics
     */
    public function getStats(string $startDate = null, string $endDate = null): array
    {
        if (!$this->isEnabled()) {
            return [];
        }

        try {
            // This would require the full SendGrid API client
            // Placeholder for demonstration
            return [
                'bounces' => 0,
                'clicks' => 0,
                'opens' => 0,
                'delivered' => 0,
            ];
        } catch (Exception $e) {
            \Log::error('SendGrid stats error: ' . $e->getMessage());
            return [];
        }
    }
}
