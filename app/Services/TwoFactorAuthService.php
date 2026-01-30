<?php

namespace App\Services;

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackend;
use BaconQrCode\Writer;

class TwoFactorAuthService
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for 2FA
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Get QR code URL for authenticator setup
     */
    public function getQrCodeUrl(string $email, string $secret, string $appName = 'Fortress Lenders'): string
    {
        return $this->google2fa->getQRCodeUrl($appName, $email, $secret);
    }

    /**
     * Generate QR code as SVG
     */
    public function generateQrCodeSvg(string $email, string $secret, string $appName = 'Fortress Lenders'): string
    {
        $url = $this->getQrCodeUrl($email, $secret, $appName);
        
        $renderer = new ImageRenderer(
            new SvgImageBackend(),
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200)
        );
        
        $writer = new Writer($renderer);
        
        return $writer->writeString($url);
    }

    /**
     * Verify a one-time password
     */
    public function verifyOtp(string $secret, string $otp, int $window = 1): bool
    {
        return $this->google2fa->verifyKeyNewer($secret, $otp, $window);
    }

    /**
     * Generate backup codes
     */
    public function generateBackupCodes(int $count = 10): array
    {
        $codes = [];
        
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
        }
        
        return $codes;
    }

    /**
     * Verify and consume a backup code
     */
    public function verifyBackupCode(array $backupCodes, string $code): bool
    {
        foreach ($backupCodes as $key => $backupCode) {
            if (hash_equals($backupCode, $code)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Remove a used backup code
     */
    public function consumeBackupCode(array &$backupCodes, string $code): array
    {
        return array_values(
            array_filter($backupCodes, fn($c) => !hash_equals($c, $code))
        );
    }
}
