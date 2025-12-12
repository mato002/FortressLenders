<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\SessionManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('profile.edit', [
            'user' => $request->user(),
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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Redirect to admin profile if accessed from admin panel, otherwise to regular profile
        if ($request->user()->is_admin) {
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

        return Redirect::to('/');
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
