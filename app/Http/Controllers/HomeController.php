<?php

namespace App\Http\Controllers;

use App\Services\PortfolioDataService;
use App\Services\PublicSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly PortfolioDataService $portfolioData
    ) {}

    public function __invoke(Request $request): View
    {
        $tracking = PublicSubmission::tracking($request->query());
        $request->session()->put($tracking);

        return view('pages.home', [
            'hero' => $this->portfolioData->getHeroData(),
            'about' => $this->portfolioData->getAboutData(),
            'skillsGrouped' => $this->portfolioData->getSkillsGroupedByCategory(),
            'services' => $this->portfolioData->getServices(),
            'projects' => $this->portfolioData->getProjects(),
            'categories' => $this->portfolioData->getProjectCategories(),
            'blogPosts' => $this->portfolioData->getBlogPosts(),
            'contact' => $this->portfolioData->getContactData(),
            'contactTracking' => PublicSubmission::tracking($request->session()->only(PublicSubmission::TRACKING_FIELDS)),
        ]);
    }
}
