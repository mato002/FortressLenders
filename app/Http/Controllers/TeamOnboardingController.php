<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Support\Str;
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
        $settings = \App\Models\GeneralSetting::latest()->first();

        $companyName = $settings?->company_name ?: 'Fortress Lenders Ltd';

        // The app stores logo paths as disk-relative paths (e.g. "logo/xyz.png").
        // If the value ever includes a "storage/" prefix, normalize it so the public disk can find it.
        $logoPath = $settings?->logo_path;
        if (is_string($logoPath)) {
            $logoPath = ltrim($logoPath, '/');
            if (str_starts_with($logoPath, 'storage/')) {
                $logoPath = substr($logoPath, strlen('storage/'));
            }
        }

        $hasLogo = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);

        return view('team-onboarding.form', [
            'companyName' => $companyName,
            'logoPath' => $hasLogo ? $logoPath : null,
        ]);
    }

    /**
     * Store a new team member submission from the public form.
     * New members are saved with is_active=false (pending admin review).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'country_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        // Combine country code and phone number
        $countryCode = $validated['country_code'];
        $rawPhone = trim($validated['phone']);

        // Normalize internal whitespace
        $rawPhone = preg_replace('/\s+/', ' ', $rawPhone);

        // If the user already entered a full international number (starts with +),
        // keep it as-is and ignore the separate country_code to avoid mangling it.
        if (Str::startsWith($rawPhone, '+')) {
            $validated['phone'] = $rawPhone;
        } else {
            // Otherwise, prefix the selected country code and keep the full local part
            $validated['phone'] = trim($countryCode . ' ' . $rawPhone);
        }
        
        unset($validated['country_code']); // Remove country_code as it's not a database field

        $validated['is_active'] = false; // Pending review before showing on website
        // Use the current maximum display_order + 1, so orders remain unique
        // even after deletions.
        $validated['display_order'] = (int) TeamMember::max('display_order') + 1;

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
