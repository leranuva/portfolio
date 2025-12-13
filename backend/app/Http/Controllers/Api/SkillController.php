<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $query = Skill::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $skills = $query->orderBy('order')->orderBy('name')->get();
        return response()->json($skills);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:frontend,backend,database,devops,design',
            'level' => 'required|integer|min:0|max:100',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $skill = Skill::create($validated);

        return response()->json($skill, 201);
    }

    public function show(string $id)
    {
        $skill = Skill::findOrFail($id);
        return response()->json($skill);
    }

    public function update(Request $request, string $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|in:frontend,backend,database,devops,design',
            'level' => 'sometimes|required|integer|min:0|max:100',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $skill->update($validated);

        return response()->json($skill);
    }

    public function destroy(string $id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
