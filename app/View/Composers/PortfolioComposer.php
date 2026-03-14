<?php

namespace App\View\Composers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioComposer
{
    public function compose(View $view): void
    {
        $heroName = SiteSetting::get('hero_name', config('app.name'));
        $heroTitle = SiteSetting::get('hero_title', '');
        $subtitle = SiteSetting::get('hero_subtitle', '');
        $aboutText = SiteSetting::get('about_text', '');
        $heroImage = SiteSetting::get('hero_image');

        $metaTitle = SiteSetting::get('meta_title') ?: trim("{$heroName} - {$heroTitle}") ?: config('app.name');
        $metaDescription = SiteSetting::get('meta_description') ?: $this->excerpt($subtitle ?: $aboutText ?: '', 160);

        $ogImage = null;
        if ($heroImage && ! str_starts_with($heroImage, 'http')) {
            $path = ltrim($heroImage, '/');
            $ogImage = file_exists(public_path($path)) ? asset($path) : url(Storage::url($heroImage));
        } elseif ($heroImage) {
            $ogImage = $heroImage;
        }

        $calendlyUrl = SiteSetting::get('calendly_url');
        $navLinks = [
            url('/') . '#home' => 'Home',
            url('/') . '#problem' => 'Problem',
            url('/') . '#about' => 'About',
            url('/') . '#case-studies' => 'Cases',
            url('/') . '#offers' => 'Offers',
            url('/') . '#skills' => 'Skills',
            url('/') . '#services' => 'Services',
            url('/') . '#portfolio' => 'Portfolio',
            url('/') . '#blog' => 'Blog',
            url('/') . '#contact' => 'Contact',
        ];
        if ($calendlyUrl) {
            $navLinks[url('/') . '#calendly'] = 'Book a call';
        }

        $view->with([
            'heroName' => $heroName,
            'navLinks' => $navLinks,
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'ogImage' => $ogImage,
        ]);
    }

    private function excerpt(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        if (strlen($text) <= $length) {
            return trim($text) ?: config('app.name');
        }
        return trim(mb_substr($text, 0, $length - 3)) . '...';
    }
}
