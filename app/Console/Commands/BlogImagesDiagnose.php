<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BlogImagesDiagnose extends Command
{
    protected $signature = 'blog:diagnose-images';

    protected $description = 'Diagnose blog post images: show stored paths and file locations';

    public function handle(): int
    {
        $posts = BlogPost::whereNotNull('image')->get();

        if ($posts->isEmpty()) {
            $this->info('No blog posts with images found.');
            return 0;
        }

        $this->table(
            ['ID', 'Slug', 'Stored path', 'Public exists?', 'Private exists?'],
            $posts->map(fn (BlogPost $post) => [
                $post->id,
                $post->slug,
                $post->image,
                Storage::disk('public')->exists($post->image) ? 'Yes' : 'No',
                Storage::disk('local')->exists($post->image) ? 'Yes' : 'No',
            ])
        );

        $this->newLine();
        $this->info('Public disk root: ' . Storage::disk('public')->path(''));
        $this->info('Private disk root: ' . Storage::disk('local')->path(''));
        $this->newLine();
        $this->info('Image URL example: ' . $posts->first()->image_url);

        return 0;
    }
}
