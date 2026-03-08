<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_project_page_loads_for_published_project(): void
    {
        $category = ProjectCategory::create([
            'name' => 'Web App',
            'slug' => 'web-app',
            'order' => 1,
        ]);

        $project = Project::create([
            'title' => 'Test Project',
            'slug' => 'test-project',
            'description' => 'Test description',
            'project_category_id' => $category->id,
            'order' => 1,
            'published_at' => now(),
        ]);

        $response = $this->get("/projects/{$project->slug}");

        $response->assertStatus(200);
        $response->assertSee('Test Project');
    }

    public function test_project_page_returns_404_for_unpublished_project(): void
    {
        $category = ProjectCategory::create([
            'name' => 'Web App',
            'slug' => 'web-app',
            'order' => 1,
        ]);

        Project::create([
            'title' => 'Draft Project',
            'slug' => 'draft-project',
            'description' => 'Draft',
            'project_category_id' => $category->id,
            'order' => 1,
            'published_at' => null,
        ]);

        $response = $this->get('/projects/draft-project');

        $response->assertStatus(404);
    }

    public function test_blog_post_page_loads_for_published_post(): void
    {
        $post = BlogPost::create([
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'published_at' => now(),
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200);
        $response->assertSee('Test Blog Post');
    }

    public function test_blog_post_page_returns_404_for_unpublished_post(): void
    {
        BlogPost::create([
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'content' => 'Draft content',
            'published_at' => null,
        ]);

        $response = $this->get('/blog/draft-post');

        $response->assertStatus(404);
    }

    public function test_lead_magnet_page_loads(): void
    {
        $response = $this->get('/recursos/auditoria');

        $response->assertStatus(200);
    }

    public function test_sitemap_loads(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function test_robots_txt_loads(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertSee('User-agent: *');
        $response->assertSee('Sitemap:');
    }

    public function test_contact_api_accepts_valid_submission(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, I would like to discuss a project.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Message sent successfully. We\'ll get back to you soon.',
        ]);
    }

    public function test_contact_api_rejects_invalid_submission(): void
    {
        $response = $this->postJson('/contact', [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ]);

        $response->assertStatus(422);
    }
}
