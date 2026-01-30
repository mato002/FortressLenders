<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Determine which guard is being used
        $candidate = Auth::guard('candidate')->user();
        $user = Auth::guard('web')->user();
        
        $authenticatedUser = $candidate ?? $user;
        
        if (!$authenticatedUser) {
            return redirect()->route('login')->withErrors([
                'email' => 'You must be logged in to update your password.',
            ]);
        }

        // Validate the request
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Verify current password manually (since current_password rule might not work for candidate guard)
        if (!Hash::check($validated['current_password'], $authenticatedUser->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ])->errorBag('updatePassword');
        }

        // Update the password
        $authenticatedUser->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Regenerate session to maintain authentication after password change
        $request->session()->regenerate();

        return back()->with('status', 'password-updated');
    }
}
