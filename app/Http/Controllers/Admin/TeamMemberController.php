<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(): View
    {
        $teamMembers = TeamMember::orderBy('display_order')->orderBy('name')->paginate(15);

        return view('admin.team-members.index', compact('teamMembers'));
    }

    public function create(): View
    {
        $teamMember = new TeamMember([
            'is_active' => true,
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
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = $validated['display_order'] ?? ($teamMember?->display_order ?? TeamMember::count() + 1);

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






