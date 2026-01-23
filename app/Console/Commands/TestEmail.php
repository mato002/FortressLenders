<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        $this->info('Testing email configuration...');
        $this->newLine();

        // Display current configuration (without password)
        $this->info('Current Mail Configuration:');
        $this->line('  Mailer: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Encryption: ' . (config('mail.mailers.smtp.encryption') ?: 'none'));
        $this->line('  Username: ' . config('mail.mailers.smtp.username'));
        $this->line('  Password: ' . (config('mail.mailers.smtp.password') ? '***' : 'not set'));
        $this->line('  From Address: ' . config('mail.from.address'));
        $this->line('  From Name: ' . config('mail.from.name'));
        $this->newLine();

        // Check if required settings are present
        if (empty(config('mail.mailers.smtp.username'))) {
            $this->error('MAIL_USERNAME is not set in your .env file!');
            return Command::FAILURE;
        }

        if (empty(config('mail.mailers.smtp.password'))) {
            $this->error('MAIL_PASSWORD is not set in your .env file!');
            return Command::FAILURE;
        }

        if (empty(config('mail.mailers.smtp.host'))) {
            $this->error('MAIL_HOST is not set in your .env file!');
            return Command::FAILURE;
        }

        $this->info("Attempting to send test email to: {$email}");
        $this->newLine();

        try {
            Mail::raw('This is a test email from Fortress Lenders. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - Fortress Lenders');
            });

            $this->info('✓ Email sent successfully!');
            $this->info("Please check the inbox (and spam folder) of: {$email}");
            return Command::SUCCESS;

        } catch (TransportExceptionInterface $e) {
            $this->error('✗ Failed to send email!');
            $this->newLine();
            $this->error('Error Details:');
            $this->line($e->getMessage());
            $this->newLine();

            // Provide helpful suggestions based on the error
            if (str_contains($e->getMessage(), '535') || str_contains($e->getMessage(), 'authentication')) {
                $this->warn('Authentication Error - Common Solutions:');
                $this->newLine();
                
                // Check if it's a cPanel/hosting email
                $host = config('mail.mailers.smtp.host');
                if (str_contains($host, 'mail.') || str_contains($host, 'smtp.')) {
                    $this->info('🔧 cPanel/Hosting Email Specific Tips:');
                    $this->line('  1. Check your cPanel email account password - it might be different from your cPanel password');
                    $this->line('  2. Try using just the email username (without @domain.com) as MAIL_USERNAME');
                    $this->line('     Example: If email is info@fortresslenders.com, try: MAIL_USERNAME=info');
                    $this->line('  3. Try SSL instead of TLS:');
                    $this->line('     MAIL_ENCRYPTION=ssl');
                    $this->line('     MAIL_PORT=465');
                    $this->line('  4. Verify the password in cPanel > Email Accounts matches your .env');
                    $this->line('  5. Some hosts require the full email as username - try both formats');
                    $this->newLine();
                }
                
                $this->info('📋 General Troubleshooting:');
                $this->line('  1. Double-check MAIL_USERNAME and MAIL_PASSWORD in .env are exactly correct');
                $this->line('  2. If password has special characters (@, #, $, etc.), wrap it in quotes:');
                $this->line('     MAIL_PASSWORD="your@password#here"');
                $this->line('  3. Check for extra spaces before/after the password in .env');
                $this->line('  4. Try different encryption/port combinations:');
                $this->line('     - TLS: MAIL_ENCRYPTION=tls, MAIL_PORT=587');
                $this->line('     - SSL: MAIL_ENCRYPTION=ssl, MAIL_PORT=465');
                $this->line('     - None: MAIL_ENCRYPTION=null, MAIL_PORT=25');
                $this->line('  5. Contact your hosting provider for exact SMTP settings');
            } elseif (str_contains($e->getMessage(), 'connection') || str_contains($e->getMessage(), 'timeout')) {
                $this->warn('Connection Error - Common Solutions:');
                $this->line('  1. Verify MAIL_HOST is correct for your email provider');
                $this->line('  2. Check if your firewall is blocking the SMTP port');
                $this->line('  3. Verify MAIL_PORT is correct (587 for TLS, 465 for SSL)');
                $this->line('  4. Try changing MAIL_ENCRYPTION (tls/ssl/null)');
            }

            $this->newLine();
            $this->info('After making changes to .env, run: php artisan config:clear');

            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('✗ Unexpected error occurred!');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
