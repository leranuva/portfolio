<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        $projects = $query->orderBy('order')->orderBy('created_at', 'desc')->get();
        
        // Asegurar que technologies sea siempre un array
        $projects->transform(function ($project) {
            if ($project->technologies && !is_array($project->technologies)) {
                if (is_string($project->technologies)) {
                    $decoded = json_decode($project->technologies, true);
                    $project->technologies = is_array($decoded) ? $decoded : [];
                } else {
                    $project->technologies = [];
                }
            } elseif (!$project->technologies) {
                $project->technologies = [];
            }
            return $project;
        });
        
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'problem_resolution' => 'nullable|string',
            'my_role' => 'nullable|string',
            'technologies' => 'nullable|array',
            'demo_link' => 'nullable|url',
            'repository_link' => 'nullable|url',
            'result' => 'nullable|string',
            'image' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Asegurar que technologies sea siempre un array
        if ($project->technologies && !is_array($project->technologies)) {
            if (is_string($project->technologies)) {
                $decoded = json_decode($project->technologies, true);
                $project->technologies = is_array($decoded) ? $decoded : [];
            } else {
                $project->technologies = [];
            }
        } elseif (!$project->technologies) {
            $project->technologies = [];
        }
        
        return response()->json($project);
    }

    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'problem_resolution' => 'nullable|string',
            'my_role' => 'nullable|string',
            'technologies' => 'nullable|array',
            'demo_link' => 'nullable|url',
            'repository_link' => 'nullable|url',
            'result' => 'nullable|string',
            'image' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
