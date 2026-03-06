<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\TeamMember;
use App\Models\User;
use App\Mail\TeamMemberLoginCredentials;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = TeamMember::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $totalTeamMembersCount = TeamMember::count();
        $activeTeamMembersCount = TeamMember::where('is_active', true)->count();
        $hiddenTeamMembersCount = TeamMember::where('is_active', false)->count();
        $filteredTeamMembersCount = $query->count();

        $teamMembers = $query->with('user')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.team-members.index', compact('teamMembers', 'totalTeamMembersCount', 'activeTeamMembersCount', 'hiddenTeamMembersCount', 'filteredTeamMembersCount'));
    }

    public function create(): View
    {
        $teamMember = new TeamMember([
            'is_active' => true,
            'account_active' => true,
            'display_order' => TeamMember::count() + 1,
        ]);

        return view('admin.team-members.create', compact('teamMember'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $teamMember = TeamMember::create($data);
        $this->handlePhotoUpload($request, $teamMember);

        return redirect()->route('admin.team-members.index')->with('status', 'Team member added.');
    }

    public function show(TeamMember $teamMember): View
    {
        return view('admin.team-members.show', compact('teamMember'));
    }

    public function edit(TeamMember $teamMember): View
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $data = $this->validatedData($request, $teamMember);
        $teamMember->update($data);
        $this->handlePhotoUpload($request, $teamMember);

        return redirect()->route('admin.team-members.index')->with('status', 'Team member updated.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }

        $teamMember->delete();

        return back()->with('status', 'Team member removed.');
    }

    /**
     * Toggle team member active status
     */
    public function toggleStatus(TeamMember $teamMember): RedirectResponse
    {
        $teamMember->update([
            'is_active' => !$teamMember->is_active
        ]);

        $status = $teamMember->is_active ? 'activated' : 'deactivated';
        // Redirect to index without filters so the member is still visible
        return redirect()->route('admin.team-members.index')->with('status', "Team member {$status} successfully.");
    }

    /**
     * Generate portal login for this team member (creates User + Candidate, links to team member).
     * Employee can then log in with web guard and use the candidate dashboard (except aptitude/self-interview).
     */
    public function generateLogin(Request $request, TeamMember $teamMember): RedirectResponse
    {
        if (! $teamMember->email) {
            return back()->withErrors(['error' => 'Team member must have an email address to generate a login.']);
        }
        if ($teamMember->account_active === false) {
            return back()->withErrors(['error' => 'This team member\'s portal account is disabled. Enable "Portal account active" before generating login.']);
        }
        // If this team member is already linked, direct admin to regenerate flow
        if ($teamMember->user_id) {
            return back()->with('status', 'This team member already has portal access. Use "Regenerate Login" to reset their password.');
        }

        // If a user with this email already exists (e.g. from a previous team-member record),
        // reuse that account instead of blocking with an error.
        $existingUser = User::where('email', $teamMember->email)->first();
        if ($existingUser) {
            \DB::transaction(function () use ($teamMember, $existingUser) {
                // Link existing user to this team member
                $teamMember->update(['user_id' => $existingUser->id]);

                // Ensure candidate exists and is linked to the same user
                $candidate = Candidate::where('email', $teamMember->email)->first();
                if ($candidate) {
                    $candidate->update([
                        'user_id' => $existingUser->id,
                    ]);
                } else {
                    Candidate::create([
                        'name' => $teamMember->name,
                        'email' => $teamMember->email,
                        'user_id' => $existingUser->id,
                        'email_verified_at' => now(),
                        // Do not change password here; keep existing user password
                    ]);
                }
            });

            return redirect()
                ->route('admin.team-members.show', $teamMember)
                ->with('status', 'Existing user account linked to this team member. They can continue using their current password.');
        }

        // No existing user → create a fresh account and credentials
        $password = $request->filled('password')
            ? $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']])['password']
            : Str::random(12);

        \DB::transaction(function () use ($teamMember, $password) {
            $user = User::create([
                'name' => $teamMember->name,
                'email' => $teamMember->email,
                'password' => Hash::make($password),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]);

            $existingCandidate = Candidate::where('email', $teamMember->email)->first();
            if ($existingCandidate) {
                $existingCandidate->update([
                    'user_id' => $user->id,
                    'password' => Hash::make($password),
                ]);
            } else {
                Candidate::create([
                    'name' => $teamMember->name,
                    'email' => $teamMember->email,
                    'password' => Hash::make($password),
                    'user_id' => $user->id,
                    'email_verified_at' => now(),
                ]);
            }

            $teamMember->update(['user_id' => $user->id]);
        });

        // Send login credentials email to team member
        try {
            Mail::to($teamMember->email)->send(new TeamMemberLoginCredentials($teamMember, $password));
        } catch (\Throwable $e) {
            \Log::error('Failed to send team member login credentials email', [
                'team_member_id' => $teamMember->id,
                'email' => $teamMember->email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.team-members.show', $teamMember)
            ->with('status', 'Portal login created. They can log in at the main login page with this email and the password below.')
            ->with('generated_password', $password)
            ->with('generated_email', $teamMember->email);
    }

    /**
     * Regenerate/resend login credentials for a team member who already has portal access.
     */
    public function regenerateLogin(Request $request, TeamMember $teamMember): RedirectResponse
    {
        if (! $teamMember->email) {
            return back()->withErrors(['error' => 'Team member must have an email address to regenerate login.']);
        }
        if ($teamMember->account_active === false) {
            return back()->withErrors(['error' => 'This team member\'s portal account is disabled. Enable "Portal account active" before regenerating login.']);
        }

        if (! $teamMember->user_id) {
            return back()->withErrors(['error' => 'This team member does not have portal access yet. Use "Generate Login" first.']);
        }

        $user = User::find($teamMember->user_id);
        if (! $user) {
            return back()->withErrors(['error' => 'User account not found.']);
        }

        $password = $request->filled('password')
            ? $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']])['password']
            : Str::random(12);

        \DB::transaction(function () use ($user, $teamMember, $password) {
            // Update user password
            $user->update([
                'password' => Hash::make($password),
            ]);

            // Update candidate password if exists
            $candidate = Candidate::where('user_id', $user->id)->first();
            if ($candidate) {
                $candidate->update([
                    'password' => Hash::make($password),
                ]);
            }
        });

        // Send regenerated credentials email to team member
        try {
            Mail::to($teamMember->email)->send(new TeamMemberLoginCredentials($teamMember, $password));
        } catch (\Throwable $e) {
            \Log::error('Failed to send regenerated team member login credentials email', [
                'team_member_id' => $teamMember->id,
                'email' => $teamMember->email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.team-members.show', $teamMember)
            ->with('status', 'Login credentials regenerated. New password is shown below.')
            ->with('generated_password', $password)
            ->with('generated_email', $teamMember->email);
    }

    /**
     * Handle bulk actions for team members (delete, activate, deactivate, generate_login)
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        \Log::info('Bulk action called', [
            'action' => $request->input('action'),
            'selected_members' => $request->input('selected_members'),
            'all_input' => $request->all(),
        ]);

        // Default counters so they are available in catch blocks
        $count = 0;
        $skipped = 0;
        $errors = [];
        $generatedCredentials = [];

        try {
            // Log incoming request data for debugging
            \Log::info('Bulk action request data', [
                'action' => $request->input('action'),
                'selected_members_raw' => $request->input('selected_members'),
                'selected_members_array' => $request->all('selected_members'),
                'all_input' => $request->all(),
            ]);

            $validated = $request->validate([
                'action' => ['required', 'string', 'in:delete,activate,deactivate,generate_login,regenerate_login'],
                'selected_members' => ['required', 'array', 'min:1'],
                'selected_members.*' => ['required', 'integer', 'exists:team_members,id'],
            ], [
                'selected_members.required' => 'Please select at least one team member.',
                'selected_members.array' => 'Invalid selection format.',
                'selected_members.min' => 'Please select at least one team member.',
                'selected_members.*.required' => 'Invalid member selection.',
                'selected_members.*.integer' => 'Invalid member ID format.',
                'selected_members.*.exists' => 'One or more selected members do not exist.',
            ]);

            \Log::info('Bulk action validation passed', ['validated' => $validated]);

            $teamMembers = TeamMember::whereIn('id', $validated['selected_members'])->get();

            if ($teamMembers->isEmpty()) {
                return redirect()
                    ->route('admin.team-members.index')
                    ->with('error', 'No team members found with the selected IDs.');
            }

            foreach ($teamMembers as $member) {
                try {
                    switch ($validated['action']) {
                        case 'delete':
                            if ($member->photo_path) {
                                Storage::disk('public')->delete($member->photo_path);
                            }
                            $member->delete();
                            $count++;
                            break;

                        case 'activate':
                            if (! $member->is_active) {
                                $member->update(['is_active' => true]);
                                $count++;
                            } else {
                                $skipped++;
                            }
                            break;

                        case 'deactivate':
                            if ($member->is_active) {
                                $member->update(['is_active' => false]);
                                $count++;
                            } else {
                                $skipped++;
                            }
                            break;

                        case 'generate_login':
                            if (! $member->email) {
                                $skipped++;
                                continue 2; // Skip members without email - continue outer foreach loop
                            }
                            if ($member->user_id) {
                                $skipped++;
                                continue 2; // Skip members who already have login - continue outer foreach loop
                            }
                            if (User::where('email', $member->email)->where('id', '!=', $member->user_id)->exists()) {
                                $skipped++;
                                continue 2; // Skip if user already exists - continue outer foreach loop
                            }

                            $password = Str::random(12);
                            \DB::transaction(function () use ($member, $password) {
                                $user = User::create([
                                    'name' => $member->name,
                                    'email' => $member->email,
                                    'password' => Hash::make($password),
                                    'role' => 'employee',
                                    'email_verified_at' => now(),
                                ]);

                                $existingCandidate = Candidate::where('email', $member->email)->first();
                                if ($existingCandidate) {
                                    $existingCandidate->update([
                                        'user_id' => $user->id,
                                        'password' => Hash::make($password),
                                    ]);
                                } else {
                                    Candidate::create([
                                        'name' => $member->name,
                                        'email' => $member->email,
                                        'password' => Hash::make($password),
                                        'user_id' => $user->id,
                                        'email_verified_at' => now(),
                                    ]);
                                }

                                $member->update(['user_id' => $user->id]);
                            });

                            $generatedCredentials[] = [
                                'name' => $member->name,
                                'email' => $member->email,
                                'password' => $password,
                            ];
                            $count++;
                            break;

                        case 'regenerate_login':
                            if (! $member->email || ! $member->user_id) {
                                $skipped++;
                                continue 2; // Skip members without email or login - continue outer foreach loop
                            }

                            $user = User::find($member->user_id);
                            if (! $user) {
                                $skipped++;
                                continue 2; // Continue outer foreach loop
                            }

                            $password = Str::random(12);
                            \DB::transaction(function () use ($user, $member, $password) {
                                $user->update([
                                    'password' => Hash::make($password),
                                ]);

                                $candidate = Candidate::where('user_id', $user->id)->first();
                                if ($candidate) {
                                    $candidate->update([
                                        'password' => Hash::make($password),
                                    ]);
                                }
                            });

                            $generatedCredentials[] = [
                                'name' => $member->name,
                                'email' => $member->email,
                                'password' => $password,
                            ];
                            $count++;
                            break;
                    }
                } catch (\Exception $e) {
                    \Log::error('Bulk action failed for team member', [
                        'member_id' => $member->id,
                        'action' => $validated['action'],
                        'error' => $e->getMessage(),
                    ]);
                    $errors[] = "Failed to process {$member->name}: " . $e->getMessage();
                }
            }

            // Build success/error messages
            $actionMessages = [
                'delete' => "{$count} team member(s) deleted successfully.",
                'activate' => "{$count} team member(s) activated successfully." . ($skipped > 0 ? " {$skipped} were already active." : ''),
                'deactivate' => "{$count} team member(s) deactivated successfully." . ($skipped > 0 ? " {$skipped} were already inactive." : ''),
                'generate_login' => "{$count} login(s) generated successfully." . ($skipped > 0 ? " {$skipped} skipped (already have login or no email)." : ''),
                'regenerate_login' => "{$count} login(s) regenerated successfully." . ($skipped > 0 ? " {$skipped} skipped (no login or no email)." : ''),
            ];

            $redirect = redirect()
                ->route('admin.team-members.index')
                ->withQueryString();

            if ($count > 0) {
                $redirect->with('status', $actionMessages[$validated['action']]);
            } else {
                $redirect->with('error', 'No changes were made. ' . ($skipped > 0 ? "All selected members were already in the desired state." : "Please check your selection."));
            }

            if (!empty($errors)) {
                $redirect->with('errors', $errors);
            }

            // Store credentials in session if login actions were performed
            if (in_array($validated['action'], ['generate_login', 'regenerate_login']) && !empty($generatedCredentials)) {
                $redirect->with('bulk_credentials', $generatedCredentials)
                         ->with('show_bulk_credentials', true);
            }

            return $redirect;

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Bulk action validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);

            // Flatten the validation error messages for a human readable summary
            $flatErrors = \Illuminate\Support\Arr::flatten($e->errors());
            $errorMessage = 'Validation failed: ' . implode(', ', $flatErrors);
            
            return redirect()
                ->route('admin.team-members.index')
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', $errorMessage);
        } catch (\Exception $e) {
            \Log::error('Bulk action error', [
                'action' => $request->input('action'),
                'selected_members' => $request->input('selected_members'),
                'processed_count' => $count,
                'skipped_count' => $skipped,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // If we managed to process at least one member, treat this as a soft warning:
            // show success to the user but keep details in the logs for debugging.
            if ($count > 0) {
                return redirect()
                    ->route('admin.team-members.index')
                    ->with('status', 'Bulk action completed, but a minor internal warning was logged. If you notice any issues, please contact support.');
            }

            $errorMessage = 'Something went wrong while processing the bulk action. Please try again.';

            // In debug mode, include more detail for developers.
            if (config('app.debug')) {
                $errorMessage .= ' (File: ' . basename($e->getFile()) . ', Line: ' . $e->getLine() . ')';
            }

            return redirect()
                ->route('admin.team-members.index')
                ->with('error', $errorMessage);
        }
    }

    protected function validatedData(Request $request, ?TeamMember $teamMember = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
            'account_active' => ['sometimes', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['account_active'] = $request->boolean('account_active', true);
        // When no explicit display_order is provided, append to the end using
        // the current maximum order value instead of count() to avoid
        // reusing numbers after deletions.
        $validated['display_order'] = $validated['display_order']
            ?? ($teamMember?->display_order ?? ((int) TeamMember::max('display_order') + 1));

        return $validated;
    }

    protected function handlePhotoUpload(Request $request, TeamMember $teamMember): void
    {
        if (! $request->hasFile('photo')) {
            return;
        }

        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }

        $path = $request->file('photo')->store('team', 'public');
        $teamMember->update(['photo_path' => $path]);
    }
}







