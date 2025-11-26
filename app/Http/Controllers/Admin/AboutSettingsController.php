<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSettingsController extends Controller
{
    public function edit()
    {
        $settings = AboutSetting::query()->latest()->first() ?? new AboutSetting();

        return view('admin.about.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $settings = AboutSetting::query()->latest()->first() ?? new AboutSetting();

        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image_path) {
                Storage::disk('public')->delete($settings->hero_image_path);
            }

            $path = $request->file('hero_image')->store('about', 'public');
            $settings->hero_image_path = $path;
        }

        $settings->save();

        return redirect()
            ->route('admin.about.edit')
            ->with('status', 'About page settings updated.');
    }
}


