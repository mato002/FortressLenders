<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSettingsController extends Controller
{
    public function edit()
    {
        $settings = HomeSetting::query()->latest()->first() ?? new HomeSetting();

        return view('admin.home.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $settings = HomeSetting::query()->latest()->first() ?? new HomeSetting();

        if ($request->hasFile('hero_image')) {
            if ($settings->hero_image_path) {
                Storage::disk('public')->delete($settings->hero_image_path);
            }

            $path = $request->file('hero_image')->store('home', 'public');
            $settings->hero_image_path = $path;
        }

        $settings->save();

        return redirect()
            ->route('admin.home.edit')
            ->with('status', 'Home page settings updated.');
    }
}



