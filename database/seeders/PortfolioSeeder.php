<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Project::truncate();
        ProjectCategory::truncate();
        Counter::truncate();
        Service::truncate();
        Skill::truncate();
        Schema::enableForeignKeyConstraints();

        $this->seedSiteSettings();
        $this->seedSkills();
        $this->seedServices();
        $this->seedCounters();
        $this->seedProjectCategories();
        $this->seedProjects();
    }

    private function seedSiteSettings(): void
    {
        $settings = [
            ['hero_name', 'Ramiro Núñez Valverde', 'hero'],
            ['hero_title', 'Full-Stack Web Developer', 'hero'],
            ['hero_subtitle', 'Full-Stack Developer specialized in creating modern and scalable web applications. With experience in cutting-edge technologies, I focus on delivering solutions that combine functionality, performance, and an excellent user experience.', 'hero'],
            ['hero_image', null, 'hero'],
            ['hero_cv_url', null, 'hero'],
            ['about_text', "I am a Full-Stack developer passionate about transforming ideas into functional and elegant web applications. My approach combines solid technical knowledge with a user-centered vision, always seeking the perfect balance between innovation and practicality.\n\nI work with modern technologies like Laravel, Vue.js, React, and Node.js, but what matters most to me is understanding project needs and choosing the right tools for each situation. Every line of code I write has a clear purpose: creating solutions that are scalable, maintainable, and truly add value.\n\nI value clean code, collaboration, and continuous learning. My style is characterized by attention to detail, usability, and minimalist design that prioritizes functionality. I always strive to write maintainable code and follow industry best practices.", 'about'],
            ['contact_email', 'info@ramironuva.com', 'contact'],
            ['contact_github', 'https://github.com/leranuva', 'contact'],
            ['contact_twitter', null, 'contact'],
        ];

        foreach ($settings as [$key, $value, $group]) {
            SiteSetting::set($key, $value, $group);
        }
    }

    private function seedSkills(): void
    {
        $skills = [
            // 1. Fundamentals
            ['Semantic HTML5', 95, 'code-bracket', 'Fundamentals', 1],
            ['Modern CSS (Flexbox, Grid)', 92, 'paint-brush', 'Fundamentals', 2],
            ['Animations & Responsive', 90, 'device-phone-mobile', 'Fundamentals', 3],
            ['Advanced JavaScript (ES6+)', 90, 'code-bracket-square', 'Fundamentals', 4],
            ['async/await, modules, closures', 88, 'sparkles', 'Fundamentals', 5],
            ['TypeScript', 85, 'code-bracket', 'Fundamentals', 6],
            // 2. Frontend
            ['React', 88, 'sparkles', 'Frontend', 7],
            ['Next.js', 85, 'globe-alt', 'Frontend', 8],
            ['Vue.js', 85, 'sparkles', 'Frontend', 9],
            ['Svelte / SvelteKit', 75, 'sparkles', 'Frontend', 10],
            ['Tailwind CSS', 92, 'paint-brush', 'Frontend', 11],
            ['State management (Redux, Zustand, Pinia)', 82, 'squares-2x2', 'Frontend', 12],
            ['Accessibility (a11y)', 85, 'user-circle', 'Frontend', 13],
            ['PWA & Web Components', 78, 'puzzle-piece', 'Frontend', 14],
            // 3. Backend
            ['Node.js', 88, 'server', 'Backend', 15],
            ['Laravel / PHP', 90, 'server-stack', 'Backend', 16],
            ['Express.js / NestJS', 82, 'cube', 'Backend', 17],
            ['Django / FastAPI', 78, 'code-bracket-square', 'Backend', 18],
            ['REST APIs & GraphQL', 90, 'arrow-path', 'Backend', 19],
            ['Authentication (JWT, OAuth)', 88, 'key', 'Backend', 20],
            ['Microservices & modular architecture', 85, 'squares-plus', 'Backend', 21],
            // 4. Databases
            ['PostgreSQL', 88, 'circle-stack', 'Databases', 22],
            ['MySQL', 88, 'circle-stack', 'Databases', 23],
            ['MongoDB', 82, 'circle-stack', 'Databases', 24],
            ['Redis (cache)', 85, 'circle-stack', 'Databases', 25],
            ['ORMs (Prisma, TypeORM)', 85, 'table-cells', 'Databases', 26],
            ['Schema design & optimization', 88, 'chart-bar', 'Databases', 27],
            // 5. DevOps & Cloud
            ['Docker', 88, 'cube', 'DevOps & Cloud', 28],
            ['Kubernetes (basics)', 75, 'cloud', 'DevOps & Cloud', 29],
            ['CI/CD pipelines', 85, 'arrow-path', 'DevOps & Cloud', 30],
            ['AWS / GCP / Azure', 80, 'cloud', 'DevOps & Cloud', 31],
            ['Monitoring', 82, 'chart-bar', 'DevOps & Cloud', 32],
            // 6. Testing
            ['Jest (unit testing)', 88, 'beaker', 'Testing', 33],
            ['Cypress / Playwright (e2e)', 85, 'play', 'Testing', 34],
            ['Integration testing', 85, 'check-badge', 'Testing', 35],
            // 7. AI Integration
            ['AI APIs (OpenAI)', 82, 'sparkles', 'AI Integration', 36],
            ['LangChain, RAG, embeddings', 78, 'sparkles', 'AI Integration', 37],
            ['Chatbots & AI automation', 80, 'chat-bubble-left-right', 'AI Integration', 38],
            // 8. Tools
            ['Git / GitHub', 92, 'code-bracket-square', 'Tools', 39],
            ['VS Code', 90, 'code-bracket-square', 'Tools', 40],
            ['Monorepos & package managers', 85, 'folder', 'Tools', 41],
            // 9. Architecture
            ['Clean Architecture', 88, 'cube', 'Architecture', 42],
            ['Domain Driven Design', 85, 'squares-2x2', 'Architecture', 43],
            ['API design & scalability', 88, 'server-stack', 'Architecture', 44],
            ['Web security', 88, 'shield-check', 'Architecture', 45],
        ];

        foreach ($skills as [$name, $percentage, $icon, $category, $order]) {
            Skill::create([
                'name' => $name,
                'percentage' => $percentage,
                'icon' => $icon,
                'category' => $category,
                'order' => $order,
            ]);
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['Web Development', 'Modern websites and applications with Laravel, React, Vue.js and more. Scalable and maintainable solutions.', 'globe-alt', 1],
            ['APIs & Backend', 'Design and implementation of robust RESTful APIs. Clean and scalable architecture.', 'server-stack', 2],
            ['Software Architecture', 'System design with DDD, Clean Architecture and best practices. Maintainable and testable code.', 'cube', 3],
        ];

        foreach ($services as [$title, $description, $icon, $order]) {
            Service::create([
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'order' => $order,
            ]);
        }
    }

    private function seedCounters(): void
    {
        $counters = [
            ['Years of experience', 5, 'briefcase', '+', 1],
            ['Projects completed', 4, 'folder-open', '+', 2],
            ['Technologies mastered', 22, 'sparkles', '+', 3],
        ];

        foreach ($counters as [$label, $value, $icon, $suffix, $order]) {
            Counter::create([
                'label' => $label,
                'value' => $value,
                'icon' => $icon,
                'suffix' => $suffix,
                'order' => $order,
            ]);
        }
    }

    private function seedProjectCategories(): void
    {
        $categories = [
            ['E-commerce', 'ecommerce', 1],
            ['Web App', 'web-app', 2],
            ['Enterprise System', 'sistema-empresarial', 3],
        ];

        foreach ($categories as [$name, $slug, $order]) {
            ProjectCategory::create([
                'name' => $name,
                'slug' => $slug,
                'order' => $order,
            ]);
        }
    }

    private function seedProjects(): void
    {
        $ecommerce = ProjectCategory::where('slug', 'ecommerce')->first();
        $webApp = ProjectCategory::where('slug', 'web-app')->first();
        $empresarial = ProjectCategory::where('slug', 'sistema-empresarial')->first();

        $projects = [
            [
                'title' => 'E-commerce Platform',
                'description' => "Modern e-commerce project developed with Laravel 12, Livewire 3, Filament 4, and Domain-Driven Design (DDD) architecture. A comprehensive platform featuring product management, variants, shopping cart, Stripe integration, and an advanced administrative panel.\n\nDevelopment of a scalable e-commerce solution requiring complex product management with variants (size, color, material), an order system powered by a state machine, customer wishlists, and secure payment processing. The DDD architecture ensures organized and scalable code, while Livewire 3 provides seamless interactivity without the need for an additional REST API.",
                'category' => $ecommerce,
                'url' => 'https://github.com/leranuva',
                'order' => 1,
            ],
            [
                'title' => 'Blue Draft Project',
                'description' => "Modern web project developed with cutting-edge technologies. A comprehensive application featuring responsive design, interactive functionalities, API integrations, and an optimized user experience. A robust system that combines a modern frontend with a scalable backend to deliver a complete and professional solution.\n\nDesigned the complete system architecture, implemented the backend with RESTful APIs, and developed the frontend using interactive components and modern design.",
                'category' => $webApp,
                'url' => 'https://leranuva.com',
                'order' => 2,
            ],
            [
                'title' => 'Task Management System',
                'description' => "Comprehensive web application for collaborative project and task management. A robust system featuring real-time updates via WebSockets, push notifications, role-based task assignment, progress tracking, live comments, and an interactive dashboard. It enables remote teams to efficiently coordinate projects through seamless communication and detailed tracking of every task.\n\nDesigned the system architecture featuring WebSockets for real-time functionality. Developed the entire backend with Laravel, implemented a push notification system, and built a RESTful API. Created the frontend using React for an interactive user experience.",
                'category' => $webApp,
                'url' => 'https://github.com/leranuva',
                'order' => 3,
            ],
            [
                'title' => 'Flat Rate Imports - Quoting System',
                'description' => "Comprehensive web system for managing and quoting imports from the United States to Ecuador. An enterprise-grade platform featuring a full administration panel, real-time quotation engine, product management, shipping rates, and package tracking.\n\nThe company required an integrated system to manage imports from the United States to Ecuador, including automated quotes, rate calculations, product management, and package tracking. Built with Laravel 12, Tailwind CSS and Alpine.js. Comprehensive system featuring 19 models, 20 controllers, 30+ migrations, 80+ views, and over 25 reusable components.",
                'category' => $empresarial,
                'url' => 'https://flatrateimports.com',
                'order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            $category = $project['category'];
            unset($project['category']);

            Project::create([
                'title' => $project['title'],
                'slug' => Str::slug($project['title']),
                'description' => $project['description'],
                'image' => null,
                'project_category_id' => $category?->id,
                'url' => $project['url'],
                'video_url' => null,
                'order' => $project['order'],
                'published_at' => now(),
            ]);
        }
    }
}
