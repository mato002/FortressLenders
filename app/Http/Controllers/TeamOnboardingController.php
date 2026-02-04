<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamOnboardingController extends Controller
{
    /**
     * Show the team onboarding form (for company members to self-register).
     */
    public function create(): View
    {
        return view('team-onboarding.form');
    }

    /**
     * Store a new team member submission from the public form.
     * New members are saved with is_active=false (pending admin review).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = false; // Pending review before showing on website
        $validated['display_order'] = TeamMember::count() + 1;

        $teamMember = TeamMember::create($validated);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('team', 'public');
            $teamMember->update(['photo_path' => $path]);
        }

        return redirect()
            ->route('team.onboarding.success')
            ->with('status', 'Thank you! Your profile has been submitted. Our team will review it and add you to the website soon.');
    }

    /**
     * Success page after form submission.
     */
    public function success(): View
    {
        return view('team-onboarding.success');
    }
}
