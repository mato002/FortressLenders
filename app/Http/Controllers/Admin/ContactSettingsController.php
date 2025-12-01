<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactSettingsController extends Controller
{
    public function edit()
    {
        $settings = ContactSetting::query()->latest()->first() ?? new ContactSetting();

        return view('admin.contact.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $settings = ContactSetting::query()->latest()->first() ?? new ContactSetting();

        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image_path) {
                Storage::disk('public')->delete($settings->hero_image_path);
            }

            $path = $request->file('hero_image')->store('contact', 'public');
            $settings->hero_image_path = $path;
        }

        $settings->save();

        return redirect()
            ->route('admin.contact.edit')
            ->with('status', 'Contact page settings updated.');
    }
}



