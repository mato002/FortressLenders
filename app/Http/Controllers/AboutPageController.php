<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use App\Models\TeamMember;

class AboutPageController extends Controller
{
    public function __invoke()
    {
        $aboutSettings = AboutSetting::query()->latest()->first();
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('about', compact('aboutSettings', 'teamMembers'));
    }
}


