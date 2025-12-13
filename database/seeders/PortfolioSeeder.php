<?php

namespace Database\Seeders;

use App\Models\PortfolioConfig;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Portfolio Configuration
        PortfolioConfig::create([
            'name' => 'Tu Nombre',
            'role' => 'Full-Stack Web Developer',
            'summary' => 'Desarrollador apasionado por crear soluciones web innovadoras y eficientes. Me especializo en construir aplicaciones modernas con las últimas tecnologías, enfocándome en la experiencia del usuario y el rendimiento.',
            'values_style' => 'Valoro el código limpio, la colaboración y el aprendizaje continuo. Mi estilo se caracteriza por la atención al detalle, la usabilidad y el diseño minimalista que prioriza la funcionalidad.',
            'email' => 'tu-email@ejemplo.com',
            'linkedin_url' => 'https://linkedin.com/in/tu-perfil',
            'github_url' => 'https://github.com/tu-usuario',
            'dark_mode_enabled' => true,
        ]);

        // Skills
        $skills = [
            // Frontend
            ['name' => 'HTML5', 'category' => 'frontend', 'proficiency' => 95, 'order' => 1],
            ['name' => 'CSS3', 'category' => 'frontend', 'proficiency' => 90, 'order' => 2],
            ['name' => 'JavaScript', 'category' => 'frontend', 'proficiency' => 88, 'order' => 3],
            ['name' => 'Vue.js', 'category' => 'frontend', 'proficiency' => 85, 'order' => 4],
            ['name' => 'React', 'category' => 'frontend', 'proficiency' => 80, 'order' => 5],
            ['name' => 'Tailwind CSS', 'category' => 'frontend', 'proficiency' => 90, 'order' => 6],
            
            // Backend
            ['name' => 'PHP', 'category' => 'backend', 'proficiency' => 92, 'order' => 1],
            ['name' => 'Laravel', 'category' => 'backend', 'proficiency' => 90, 'order' => 2],
            ['name' => 'Node.js', 'category' => 'backend', 'proficiency' => 85, 'order' => 3],
            ['name' => 'Python', 'category' => 'backend', 'proficiency' => 75, 'order' => 4],
            ['name' => 'RESTful APIs', 'category' => 'backend', 'proficiency' => 88, 'order' => 5],
            
            // Database
            ['name' => 'MySQL', 'category' => 'database', 'proficiency' => 90, 'order' => 1],
            ['name' => 'PostgreSQL', 'category' => 'database', 'proficiency' => 80, 'order' => 2],
            ['name' => 'MongoDB', 'category' => 'database', 'proficiency' => 75, 'order' => 3],
            ['name' => 'Redis', 'category' => 'database', 'proficiency' => 70, 'order' => 4],
            
            // DevOps
            ['name' => 'Git', 'category' => 'devops', 'proficiency' => 90, 'order' => 1],
            ['name' => 'Docker', 'category' => 'devops', 'proficiency' => 80, 'order' => 2],
            ['name' => 'CI/CD', 'category' => 'devops', 'proficiency' => 75, 'order' => 3],
            ['name' => 'AWS', 'category' => 'devops', 'proficiency' => 70, 'order' => 4],
            
            // Design
            ['name' => 'Figma', 'category' => 'design', 'proficiency' => 85, 'order' => 1],
            ['name' => 'UI/UX Design', 'category' => 'design', 'proficiency' => 80, 'order' => 2],
            ['name' => 'Responsive Design', 'category' => 'design', 'proficiency' => 95, 'order' => 3],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Projects
        $projects = [
            [
                'name' => 'E-commerce Platform',
                'description' => 'Plataforma completa de comercio electrónico con gestión de inventario, pagos y panel administrativo.',
                'problem_solution' => 'El cliente necesitaba una solución escalable para vender productos online con gestión de inventario en tiempo real y múltiples métodos de pago.',
                'role' => 'Desarrollador Full-Stack - Diseñé la arquitectura, implementé el backend con Laravel, desarrollé el frontend con Vue.js y configuré la infraestructura en AWS.',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'AWS', 'Stripe API'],
                'live_url' => 'https://ejemplo-ecommerce.com',
                'repository_url' => 'https://github.com/usuario/ecommerce',
                'results_learnings' => 'Reducción del 40% en tiempo de carga, aumento del 60% en conversiones. Aprendí sobre optimización de consultas y caching avanzado.',
                'order' => 1,
                'featured' => true,
            ],
            [
                'name' => 'Sistema de Gestión de Tareas',
                'description' => 'Aplicación web para gestión colaborativa de proyectos con tiempo real y notificaciones.',
                'problem_solution' => 'Equipos remotos necesitaban una herramienta para coordinar tareas y proyectos de manera eficiente con actualizaciones en tiempo real.',
                'role' => 'Desarrollador Full-Stack - Implementé WebSockets para tiempo real, sistema de notificaciones push y API RESTful completa.',
                'technologies' => ['Laravel', 'React', 'PostgreSQL', 'WebSockets', 'Docker'],
                'live_url' => 'https://ejemplo-tasks.com',
                'repository_url' => 'https://github.com/usuario/task-manager',
                'results_learnings' => 'Mejora del 50% en productividad del equipo. Dominé WebSockets y arquitectura de aplicaciones en tiempo real.',
                'order' => 2,
                'featured' => true,
            ],
            [
                'name' => 'API de Análisis de Datos',
                'description' => 'API RESTful para análisis y visualización de datos empresariales con dashboard interactivo.',
                'problem_solution' => 'La empresa requería procesar grandes volúmenes de datos y generar reportes en tiempo real para toma de decisiones.',
                'role' => 'Desarrollador Backend - Diseñé la arquitectura de microservicios, implementé procesamiento asíncrono y optimicé consultas complejas.',
                'technologies' => ['Node.js', 'Python', 'MongoDB', 'Redis', 'Docker', 'Kubernetes'],
                'live_url' => null,
                'repository_url' => 'https://github.com/usuario/analytics-api',
                'results_learnings' => 'Procesamiento 10x más rápido. Aprendí sobre microservicios, message queues y optimización de bases de datos NoSQL.',
                'order' => 3,
                'featured' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
