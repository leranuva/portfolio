<?php

namespace App\Http\Controllers;

use App\Models\PortfolioConfig;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $config = PortfolioConfig::first();
        $projects = Project::where('featured', true)
            ->orderBy('order')
            ->get();
        $skills = Skill::orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('portfolio.index', compact('config', 'projects', 'skills'));
    }
}
