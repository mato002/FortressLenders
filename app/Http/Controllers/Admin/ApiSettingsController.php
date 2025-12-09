<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    public function edit()
    {
        $settings = ApiSetting::query()->latest()->first() ?? new ApiSetting();

        return view('admin.api.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_name' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'api_endpoint' => ['nullable', 'url', 'max:500'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $settings = ApiSetting::query()->latest()->first() ?? new ApiSetting();
        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.api.edit')
            ->with('status', 'API settings updated successfully.');
    }
}


