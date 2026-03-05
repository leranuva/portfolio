<?php

namespace App\Http\Controllers;

use App\Services\PortfolioDataService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly PortfolioDataService $portfolioData
    ) {}

    public function __invoke(): View
    {
        return view('pages.home', [
            'hero' => $this->portfolioData->getHeroData(),
            'about' => $this->portfolioData->getAboutData(),
            'skillsGrouped' => $this->portfolioData->getSkillsGroupedByCategory(),
            'services' => $this->portfolioData->getServices(),
            'projects' => $this->portfolioData->getProjects(),
            'categories' => $this->portfolioData->getProjectCategories(),
            'blogPosts' => $this->portfolioData->getBlogPosts(),
            'contact' => $this->portfolioData->getContactData(),
        ]);
    }
}
