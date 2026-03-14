<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use App\Models\Counter;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use Illuminate\Support\Collection;

class PortfolioDataService
{
    public function getHeroData(): array
    {
        $cvUrl = SiteSetting::get('hero_cv_url');
        if ($cvUrl && ! str_starts_with((string) $cvUrl, 'http')) {
            $cvUrl = $this->publicFileUrl($cvUrl);
        }

        $image = SiteSetting::get('hero_image');
        if ($image && ! str_starts_with((string) $image, 'http')) {
            $image = $this->publicFileUrl($image);
        }

        return [
            'name' => SiteSetting::get('hero_name', ''),
            'title' => SiteSetting::get('hero_title', ''),
            'subtitle' => SiteSetting::get('hero_subtitle', ''),
            'image' => $image,
            'cvUrl' => $cvUrl,
        ];
    }

    public function getAboutData(): array
    {
        return [
            'text' => SiteSetting::get('about_text', ''),
            'counters' => Counter::ordered()->get(),
        ];
    }

    public function getSkillsGroupedByCategory(): Collection
    {
        $categoryOrder = [
            'Fundamentals',
            'Frontend',
            'Backend',
            'Databases',
            'DevOps & Cloud',
            'Testing',
            'AI Integration',
            'Tools',
            'Architecture',
        ];
        $grouped = Skill::ordered()->get()->groupBy('category');

        return collect($categoryOrder)
            ->filter(fn (string $cat) => $grouped->has($cat))
            ->mapWithKeys(fn (string $cat) => [$cat => $grouped->get($cat)]);
    }

    public function getServices(): Collection
    {
        return Service::ordered()->get();
    }

    public function getProjects(): Collection
    {
        return Project::published()->ordered()->with('category')->get();
    }

    public function getProjectCategories(): Collection
    {
        return ProjectCategory::ordered()->get();
    }

    public function getBlogPosts(int $limit = 6): Collection
    {
        return BlogPost::published()->latest('published_at')->limit($limit)->get();
    }

    public function getContactData(): array
    {
        $cvUrl = SiteSetting::get('hero_cv_url');
        if ($cvUrl && ! str_starts_with((string) $cvUrl, 'http')) {
            $cvUrl = $this->publicFileUrl($cvUrl);
        }

        return [
            'email' => SiteSetting::get('contact_email', ''),
            'github' => SiteSetting::get('contact_github'),
            'twitter' => SiteSetting::get('contact_twitter'),
            'cvUrl' => $cvUrl,
            'calendlyUrl' => SiteSetting::get('calendly_url'),
        ];
    }

    /**
     * Resolve URL for files: public disk (blog/profile/cv) or legacy storage.
     */
    private function publicFileUrl(string $path): string
    {
        $path = ltrim($path, '/');
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        return Storage::url($path);
    }
}
