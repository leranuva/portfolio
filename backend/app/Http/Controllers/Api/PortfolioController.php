<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortfolioSetting;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function publicData()
    {
        $settings = PortfolioSetting::pluck('value', 'key')->toArray();
        $projects = Project::where('is_featured', true)
            ->orderBy('order')
            ->get();
        $skills = Skill::orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return response()->json([
            'settings' => $settings,
            'projects' => $projects,
            'skills' => $skills,
        ]);
    }

    public function getSettings()
    {
        $settings = PortfolioSetting::all();
        return response()->json($settings);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.type' => 'nullable|string',
        ]);

        foreach ($request->settings as $setting) {
            PortfolioSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'] ?? null,
                    'type' => $setting['type'] ?? 'text',
                ]
            );
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function getSetting($key)
    {
        $setting = PortfolioSetting::where('key', $key)->first();
        return response()->json($setting);
    }

    public function updateSetting(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'nullable',
            'type' => 'nullable|string',
        ]);

        $setting = PortfolioSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $validated['value'] ?? null,
                'type' => $validated['type'] ?? 'text',
            ]
        );

        return response()->json($setting);
    }
}
