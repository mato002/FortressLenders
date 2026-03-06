<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\CandidateProfileUpdateRequest;
use App\Services\SessionManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Candidate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected SessionManagementService $sessionManagementService
    ) {}
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Check if candidate is logged in
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            return view('candidate.profile', [
                'candidate' => $candidate,
                'user' => $candidate, // For backward compatibility in views
            ]);
        }
        
        // Otherwise, it's an employee
        $user = $request->user();
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Display the admin-styled profile view.
     */
    public function editAdmin(Request $request): View
    {
        $user = $request->user();
        $sessions = $user->activeSessions()->get();
        $currentSessionId = $request->session()->getId();

        return view('admin.profile', [
            'user' => $user,
            'sessions' => $sessions,
            'currentSessionId' => $currentSessionId,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // Handle candidate profile update
        $candidate = Auth::guard('candidate')->user();
        if ($candidate) {
            // Validate candidate request
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:candidates,email,' . $candidate->id],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:100'],
                'country' => ['nullable', 'string', 'max:100'],
            ]);
            
            $candidate->fill($validated);
            $emailChanged = $candidate->isDirty('email');

            if ($emailChanged) {
                $candidate->email_verified_at = null;
            }

            $candidate->save();

            if ($emailChanged && method_exists($candidate, 'sendEmailVerificationNotification')) {
                // Send verification link and redirect with message
                try {
                    $candidate->sendEmailVerificationNotification();
                } catch (\Exception $e) {
                    // Log and continue
                    \Log::error('Failed to send verification email to candidate', ['error' => $e->getMessage(), 'candidate_id' => $candidate->id]);
                }
                return Redirect::route(\Route::has('candidate.profile.edit') ? 'candidate.profile.edit' : 'profile.edit')
                    ->with('status', 'verification-link-sent');
            }

            return Redirect::route(\Route::has('candidate.profile.edit') ? 'candidate.profile.edit' : 'profile.edit')->with('status', 'profile-updated');
        }
        
        // Handle employee profile update
        $user = $request->user();
        
        // Validate user request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);
        
        $user->fill($validated);

        $emailChanged = $user->isDirty('email');

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged && method_exists($user, 'sendEmailVerificationNotification')) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email to user', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            }
            return Redirect::route($user->is_admin ? 'admin.profile' : 'profile.edit')->with('status', 'verification-link-sent');
        }

        // Redirect to admin profile if accessed from admin panel
        if ($user->is_admin) {
            return Redirect::route('admin.profile')->with('status', 'profile-updated');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }

    /**
     * Revoke a specific session.
     */
    public function revokeSession(Request $request, string $sessionId): RedirectResponse
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        // Prevent revoking current session
        if ($sessionId === $currentSessionId) {
            return back()->withErrors(['session' => 'You cannot revoke your current session.']);
        }

        // Verify the session belongs to the user
        $session = $user->sessions()->where('session_id', $sessionId)->first();
        
        if (!$session) {
            return back()->withErrors(['session' => 'Session not found.']);
        }

        $this->sessionManagementService->revokeSession($sessionId);

        return back()->with('status', 'Session revoked successfully.');
    }

    /**
     * Revoke all other sessions.
     */
    public function revokeOtherSessions(Request $request): RedirectResponse
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        $count = $this->sessionManagementService->revokeOtherSessions($user, $currentSessionId);

        return back()->with('status', "Revoked {$count} other session(s).");
    }
}
