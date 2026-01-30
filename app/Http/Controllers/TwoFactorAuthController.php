<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TwoFactorAuthController extends Controller
{
    private TwoFactorAuthService $twoFactorService;

    public function __construct(TwoFactorAuthService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
        $this->middleware('auth:web,candidate');
    }

    public function index(): View
    {
        $user = auth()->user() ?? auth()->guard('candidate')->user();

        return view('two-factor-auth.index', [
            'twoFactorEnabled' => $user->two_factor_enabled,
        ]);
    }

    public function setup(): View
    {
        $user = auth()->user() ?? auth()->guard('candidate')->user();
        $secret = $this->twoFactorService->generateSecret();
        
        // Store temporarily in session
        session(['2fa_temp_secret' => $secret]);

        $qrCode = $this->twoFactorService->generateQrCodeSvg($user->email, $secret);

        return view('two-factor-auth.setup', [
            'secret' => $secret,
            'qrCode' => $qrCode,
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $secret = session('2fa_temp_secret');
        
        if (!$secret || !$this->twoFactorService->verifyOtp($secret, $request->otp)) {
            return back()->with('error', 'Invalid OTP code. Please try again.');
        }

        $user = auth()->user() ?? auth()->guard('candidate')->user();
        $backupCodes = $this->twoFactorService->generateBackupCodes();

        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_backup_codes' => json_encode($backupCodes),
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        session()->forget('2fa_temp_secret');

        return redirect()->route('two-factor-auth.backup-codes')
            ->with('success', 'Two-factor authentication enabled successfully!')
            ->with('backup_codes', $backupCodes);
    }

    public function backupCodes(): View
    {
        $backupCodes = session('backup_codes', []);

        if (empty($backupCodes)) {
            return redirect()->route('two-factor-auth.index');
        }

        return view('two-factor-auth.backup-codes', [
            'backupCodes' => $backupCodes,
        ]);
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user() ?? auth()->guard('candidate')->user();

        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_backup_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('two-factor-auth.index')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $user = auth()->user() ?? auth()->guard('candidate')->user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('dashboard');
        }

        // Try OTP first
        if ($this->twoFactorService->verifyOtp($user->two_factor_secret, $request->otp)) {
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard');
        }

        // Try backup code
        $backupCodes = json_decode($user->two_factor_backup_codes, true) ?? [];
        if ($this->twoFactorService->verifyBackupCode($backupCodes, $request->otp)) {
            // Consume the backup code
            $remaining = $this->twoFactorService->consumeBackupCode($backupCodes, $request->otp);
            $user->update(['two_factor_backup_codes' => json_encode($remaining)]);

            session(['2fa_verified' => true]);
            return redirect()->route('dashboard')
                ->with('warning', 'Backup code used. ' . count($remaining) . ' remaining.');
        }

        return back()->with('error', 'Invalid code. Please try again.');
    }
}
