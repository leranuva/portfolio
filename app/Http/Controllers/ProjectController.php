<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function show(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)->with('category')->firstOrFail();

        return view('projects.show', ['project' => $project]);
    }
}
