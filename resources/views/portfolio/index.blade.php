<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="darkModeManager()" 
      x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>{{ $config->name ?? 'Portfolio' }} - {{ $config->role ?? 'Full-Stack Web Developer' }}</title>
    <meta name="title" content="{{ $config->name ?? 'Portfolio' }} - {{ $config->role ?? 'Full-Stack Web Developer' }}">
    <meta name="description" content="{{ $config->summary ?? 'Portfolio profesional desarrollado con Laravel, Tailwind CSS y Alpine.js. Diseño moderno, responsivo y modo oscuro.' }}">
    <meta name="keywords" content="portfolio, desarrollador web, full-stack, Laravel, PHP, Tailwind CSS, Alpine.js, programador">
    <meta name="author" content="{{ $config->name ?? 'Ramiro Núñez Valverde' }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Spanish">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $config->name ?? 'Portfolio' }} - {{ $config->role ?? 'Full-Stack Web Developer' }}">
    <meta property="og:description" content="{{ $config->summary ?? 'Portfolio profesional desarrollado con Laravel, Tailwind CSS y Alpine.js.' }}">
    @if($config && $config->profile_image)
    <meta property="og:image" content="{{ asset('storage/' . $config->profile_image) }}">
    @else
    <meta property="og:image" content="{{ asset('favicon.svg') }}">
    @endif
    <meta property="og:site_name" content="{{ $config->name ?? 'Portfolio' }}">
    <meta property="og:locale" content="es_ES">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ $config->name ?? 'Portfolio' }} - {{ $config->role ?? 'Full-Stack Web Developer' }}">
    <meta property="twitter:description" content="{{ $config->summary ?? 'Portfolio profesional desarrollado con Laravel, Tailwind CSS y Alpine.js.' }}">
    @if($config && $config->profile_image)
    <meta property="twitter:image" content="{{ asset('storage/' . $config->profile_image) }}">
    @else
    <meta property="twitter:image" content="{{ asset('favicon.svg') }}">
    @endif
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-50 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#home" class="text-xl font-bold text-gray-900 dark:text-white">Portfolio</a>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Sobre mí</a>
                    <a href="#projects" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Proyectos</a>
                    <a href="#skills" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Habilidades</a>
                    <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">Contacto</a>
                </div>
                <button @click="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition" aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero / Landing Section -->
    <section id="home" class="min-h-screen flex items-center justify-center pt-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8 animate-fade-in">
                @if($config && $config->profile_image)
                    @php
                        $imagePath = 'storage/' . $config->profile_image;
                        $fullPath = public_path($imagePath);
                        $imageUrl = file_exists($fullPath) ? asset($imagePath) : url('storage/' . $config->profile_image);
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $config->name }}" 
                         class="w-32 h-32 rounded-full mx-auto mb-6 shadow-lg object-cover border-4 border-white dark:border-gray-800"
                         onerror="console.error('Error cargando imagen: {{ $imageUrl }}'); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                @else
                    <div class="w-32 h-32 rounded-full mx-auto mb-6 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                        {{ substr($config->name ?? 'P', 0, 1) }}
                    </div>
                @endif
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-4 animate-slide-up">
                {{ $config->name ?? 'Tu Nombre' }}
            </h1>
            <p class="text-2xl md:text-3xl text-gray-600 dark:text-gray-400 mb-6 animate-slide-up-delay">
                {{ $config->role ?? 'Full-Stack Web Developer' }}
            </p>
            <p class="text-lg text-gray-700 dark:text-gray-300 max-w-2xl mx-auto mb-8 animate-slide-up-delay-2">
                {{ $config->summary ?? 'Desarrollador apasionado por crear soluciones web innovadoras y eficientes.' }}
            </p>
            <div class="flex justify-center space-x-4 animate-fade-in-delay">
                <a href="#contact" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition transform hover:scale-105">
                    Contáctame
                </a>
                <a href="#projects" class="px-6 py-3 border-2 border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600 rounded-lg transition">
                    Ver Proyectos
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Sobre mí</h2>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8">
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-lg leading-relaxed mb-6 text-gray-700 dark:text-gray-300">
                        Soy un desarrollador Full-Stack con pasión por transformar ideas en aplicaciones web funcionales y elegantes. Mi enfoque combina conocimientos técnicos sólidos con una visión centrada en el usuario, siempre buscando el equilibrio perfecto entre innovación y practicidad.
                    </p>
                    <p class="text-lg leading-relaxed mb-6 text-gray-700 dark:text-gray-300">
                        Trabajo con tecnologías modernas como Laravel, Vue.js, React y Node.js, pero lo más importante para mí es entender las necesidades del proyecto y elegir las herramientas adecuadas para cada situación. Cada línea de código que escribo tiene un propósito claro: crear soluciones que sean escalables, mantenibles y que realmente agreguen valor.
                    </p>
                    @if($config && $config->values_style)
                        <div class="mt-6">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">Valores y Estilo</h3>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $config->values_style }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Proyectos Destacados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($projects as $project)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-2">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600"></div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2">{{ $project->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $project->description }}</p>
                            
                            <div class="mb-4">
                                <h4 class="font-semibold mb-2">Problema/Resolución:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $project->problem_solution }}</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold mb-2">Mi Rol:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $project->role }}</p>
                            </div>

                            @if($project->technologies)
                                <div class="mb-4">
                                    <h4 class="font-semibold mb-2">Tecnologías:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($project->technologies as $tech)
                                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs rounded">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($project->results_learnings)
                                <div class="mb-4">
                                    <h4 class="font-semibold mb-2">Resultados:</h4>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $project->results_learnings }}</p>
                                </div>
                            @endif

                            <div class="flex space-x-2 mt-4">
                                @if($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition">
                                        Ver Demo
                                    </a>
                                @endif
                                @if($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank" class="px-4 py-2 border border-gray-300 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-600 rounded text-sm transition">
                                        Repositorio
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">No hay proyectos destacados aún.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Habilidades y Stack</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(['frontend' => 'Frontend', 'backend' => 'Backend', 'database' => 'Base de Datos', 'devops' => 'DevOps', 'design' => 'Diseño/UX'] as $key => $label)
                    @if(isset($skills[$key]) && $skills[$key]->count() > 0)
                        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                            <h3 class="text-2xl font-bold mb-4">{{ $label }}</h3>
                            <div class="space-y-3">
                                @foreach($skills[$key] as $skill)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium">{{ $skill->name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $skill->proficiency }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $skill->proficiency }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Contacto</h2>
            <p class="text-lg text-gray-700 dark:text-gray-300 text-center mb-8">
                ¿Tienes un proyecto en mente? Me encantaría escucharte.
            </p>
            
            <!-- Contact Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8" x-data="contactForm()">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            x-model="form.name"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="Tu nombre completo"
                        >
                        <p x-show="errors.name" x-text="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            x-model="form.email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="tu@email.com"
                        >
                        <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Asunto <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            x-model="form.subject"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="¿Sobre qué quieres hablar?"
                        >
                        <p x-show="errors.subject" x-text="errors.subject" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mensaje <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            x-model="form.message"
                            required
                            rows="6"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize-none"
                            placeholder="Cuéntame sobre tu proyecto o idea..."
                        ></textarea>
                        <p x-show="errors.message" x-text="errors.message" class="mt-1 text-sm text-red-600 dark:text-red-400"></p>
                    </div>

                    <!-- Success/Error Messages -->
                    <div x-show="successMessage" x-cloak class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                        <p x-text="successMessage"></p>
                    </div>
                    <div x-show="errorMessage" x-cloak class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                        <p x-text="errorMessage"></p>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="loading"
                        class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white rounded-lg transition transform hover:scale-105 flex items-center justify-center space-x-2"
                    >
                        <svg x-show="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!loading">Enviar Mensaje</span>
                        <span x-show="loading">Enviando...</span>
                    </button>
                </form>
            </div>

            <!-- Social Links -->
            <div class="flex justify-center space-x-6">
                @if($config && $config->email)
                    <a href="mailto:{{ $config->email }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition transform hover:scale-105 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Email</span>
                    </a>
                @endif
                @if($config && $config->linkedin_url)
                    <a href="{{ $config->linkedin_url }}" target="_blank" class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition transform hover:scale-105 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        <span>LinkedIn</span>
                    </a>
                @endif
                @if($config && $config->github_url)
                    <a href="{{ $config->github_url }}" target="_blank" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition transform hover:scale-105 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        <span>GitHub</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-white py-8 text-center">
        <p>&copy; {{ date('Y') }} {{ $config->name ?? 'Portfolio' }}. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script>
        // Contact Form Manager
        function contactForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                },
                errors: {},
                loading: false,
                successMessage: '',
                errorMessage: '',
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    this.successMessage = '';
                    this.errorMessage = '';
                    
                    try {
                        const response = await fetch('{{ route("portfolio.contact") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.form)
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.successMessage = data.message;
                            this.form = { name: '', email: '', subject: '', message: '' };
                            setTimeout(() => {
                                this.successMessage = '';
                            }, 5000);
                        } else {
                            if (data.errors) {
                                this.errors = data.errors;
                            } else {
                                this.errorMessage = data.message || 'Hubo un error al enviar el mensaje.';
                            }
                        }
                    } catch (error) {
                        this.errorMessage = 'Error de conexión. Por favor, intenta nuevamente.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
        
        // Dark Mode Manager
        function darkModeManager() {
            const defaultDark = {{ ($config && $config->dark_mode_enabled) ? 'true' : 'false' }};
            const stored = localStorage.getItem('darkMode');
            const isDark = stored !== null ? stored === 'true' : defaultDark;
            
            // Aplicar clase inicial
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            return {
                darkMode: isDark,
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            };
        }
        
        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

