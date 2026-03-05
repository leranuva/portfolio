# Guía Completa de Implementación - Portfolio Devman con Laravel + Filament

## Análisis del Portfolio de Referencia (devman-react.vercel.app)

El template **Devman** es un portfolio personal one-page para desarrolladores y profesionales creativos. Incluye:

### Características Principales
- **Modo oscuro/claro** con toggle
- **Diseño responsive** para todos los dispositivos
- **Navegación sticky** con scroll suave entre secciones
- **Animaciones CSS3** modernas
- **SEO optimizado**
- **Layout single-page** con secciones ancladas

### Secciones del Portfolio (estado actual)

1. **Hero** - Introducción con nombre, título, foto y CTA
2. **Problema** - Copy de dolor/necesidad del cliente ("¿Pierdes tiempo con tareas repetitivas?")
3. **About** - Biografía, años de experiencia, contadores
4. **Casos de éxito** - BlueDraft, Flat Rate Imports (problema, solución, tecnologías)
5. **Ofertas** - 3 planes: Automatización, Sistema web a medida, Freelance flexible
6. **Skills** - Barras de progreso por habilidad (agrupadas por categoría)
7. **Services** - Servicios ofrecidos
8. **Portfolio/Proyectos** - Galería con filtros, modal con YouTube/Vimeo
9. **Blog** - Últimos artículos
10. **Contact** - Formulario de leads (nombre, email, tipo proyecto, presupuesto, urgencia, etc.)
11. **Calendly** - Embed de consulta (si está configurado)

---

## Stack Tecnológico Propuesto

| Componente | Tecnología |
|------------|------------|
| Backend | Laravel 12 |
| Admin Panel | Filament 5 |
| Frontend | Blade + Livewire |
| Estilos | Tailwind CSS v4 |
| Iconos | Heroicons / Lucide |
| Animaciones | Alpine.js + CSS |
| Base de datos | SQLite (dev) / MySQL (prod) |

---

# FASES DE IMPLEMENTACIÓN

---

## FASE 0: Preparación del Entorno (Día 1)

### 0.1 Requisitos Previos
- PHP 8.2+
- Composer
- Node.js 18+
- Base de datos (MySQL recomendado)

### 0.2 Crear Proyecto Laravel
```bash
composer create-project laravel/laravel portfolio_ramiro
cd portfolio_ramiro
```

### 0.3 Instalar Dependencias
```bash
# Filament
composer require filament/filament:"^3.2" -W

# Instalar panel de administración
php artisan filament:install --panels

# Tailwind (si no viene incluido)
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 0.4 Configurar Base de Datos
- Crear base de datos
- Configurar `.env` con credenciales
- Ejecutar `php artisan migrate`

---

## FASE 1: Estructura de Base de Datos y Modelos (Días 2-3)

### 1.1 Migraciones a Crear

```bash
php artisan make:migration create_portfolio_tables
```

**Estructura sugerida:**

| Tabla | Campos principales |
|-------|-------------------|
| `site_settings` | key, value (para hero, about, contact, CV) |
| `skills` | name, percentage, order, icon |
| `services` | title, description, icon, order |
| `projects` | title, slug, description, image, category, url, video_url, order, published_at |
| `project_categories` | name, slug |
| `blog_posts` | title, slug, excerpt, content, image, published_at (opcional) |
| `contact_messages` | name, email, subject, message, read_at |
| `counters` | label, value, icon, order (para About: proyectos, clientes, años) |

### 1.2 Modelos Eloquent
- `Skill`, `Service`, `Project`, `ProjectCategory`, `BlogPost`, `ContactMessage`, `Counter`
- `SiteSetting` (key-value para configuración global)

### 1.3 Relaciones
- `Project` belongsTo `ProjectCategory`
- `Project` hasMany `ProjectImage` (si necesitas galería)

---

## FASE 2: Panel de Administración con Filament (Días 4-6)

### 2.1 Crear Usuario Admin
```bash
php artisan make:filament-user
```

### 2.2 Recursos Filament por Sección

| Recurso | Gestión |
|---------|---------|
| **SiteSettingsResource** | Hero (nombre, título, subtítulo, imagen, CV), About (texto), Contact (email, redes) |
| **SkillResource** | CRUD de habilidades con barra de porcentaje |
| **ServiceResource** | CRUD de servicios |
| **ProjectResource** | CRUD con categorías, imágenes, URLs |
| **ProjectCategoryResource** | Categorías para filtrar proyectos |
| **CounterResource** | Contadores (años exp, proyectos, clientes) |
| **BlogPostResource** | (Opcional) Artículos |
| **ContactMessageResource** | Solo lectura para mensajes recibidos |

### 2.3 Personalización del Panel
- Logo personalizado
- Tema oscuro/claro del admin
- Dashboard con widgets: mensajes recientes, proyectos publicados, etc.

### 2.4 Formulario de Contacto - Backend
- Ruta API/Controller para recibir mensajes
- Validación y almacenamiento en `contact_messages`
- Notificación por email (opcional)

---

## FASE 3: Frontend - Layout y Navegación (Días 7-8)

### 3.1 Estructura de Vistas
```
resources/views/
├── layouts/
│   └── portfolio.blade.php    # Layout principal
├── components/
│   ├── nav.blade.php          # Navegación sticky
│   ├── hero.blade.php
│   ├── about.blade.php
│   ├── skills.blade.php
│   ├── services.blade.php
│   ├── portfolio.blade.php
│   ├── blog.blade.php
│   └── contact.blade.php
└── pages/
    └── home.blade.php         # Página principal que incluye todas las secciones
```

### 3.2 Layout Base
- Meta tags SEO
- Tailwind CSS
- Alpine.js para interactividad
- Font (ej: Inter, Poppins, o similar a Devman)
- Variables CSS para tema oscuro/claro

### 3.3 Navegación Sticky
- Links con anclas: `#home`, `#about`, `#skills`, `#services`, `#portfolio`, `#contact`
- Scroll suave con `scroll-behavior: smooth` o JS
- Menú hamburguesa en móvil
- Toggle dark/light mode

---

## FASE 4: Secciones del Portfolio (Días 9-14)

### 4.1 Hero Section
- Imagen de perfil (circular o con forma distintiva)
- Nombre y título dinámicos (desde BD)
- Subtítulo o tagline
- Botones: "Contactar", "Ver CV"
- Animación de entrada (fade-in, slide)

### 4.2 About Section
- Texto biográfico editable
- Contadores animados (años, proyectos, clientes) con CountUp.js o similar
- Imagen secundaria (opcional)

### 4.3 Skills Section
- Grid de habilidades
- Barras de progreso animadas al hacer scroll (Intersection Observer)
- Iconos por habilidad
- Datos desde `skills` en BD

### 4.4 Services Section
- Cards con icono, título y descripción
- 3-6 servicios típicos: Web, Mobile, Desktop, Consultoría, etc.
- Hover effects

### 4.5 Portfolio/Projects Section
- Filtro por categoría (All, Web, Mobile, etc.)
- Grid de proyectos con imagen, título, categoría
- Modal o página detalle al hacer clic
- Soporte para enlaces externos y videos embebidos

### 4.6 Blog Section (Opcional)
- Listado de últimos 3-6 posts
- Card con imagen, título, excerpt, fecha
- Enlace a blog completo si lo implementas

### 4.7 Contact Section
- Formulario: nombre, email, asunto, mensaje
- Envío vía Livewire o AJAX
- Redes sociales (enlaces desde BD)
- Botón descarga CV

---

## FASE 5: Modo Oscuro/Claro (Día 15)

### 5.1 Implementación
- Variable CSS `--theme: dark` o `light`
- Clase `dark` en `<html>` (Tailwind dark mode)
- Toggle con Alpine.js: `x-data="{ dark: false }"`
- Persistir preferencia en `localStorage`
- Respetar `prefers-color-scheme` del sistema

### 5.2 Paleta de Colores (inspirada en Devman)
- **Dark**: fondo #0f0f0f o similar, acentos en tonos azul/cyan
- **Light**: fondo blanco/gris claro, acentos oscuros

---

## FASE 6: Animaciones y Polish (Días 16-17)

### 6.1 Animaciones
- Fade-in al hacer scroll (Intersection Observer + CSS transitions)
- Barras de skills que se llenan al entrar en viewport
- Contadores que incrementan (CountUp.js)
- Hover en cards y botones
- Transiciones suaves entre secciones

### 6.2 Responsive
- Breakpoints: mobile, tablet, desktop
- Menú móvil colapsable
- Imágenes responsive
- Tipografía escalable

### 6.3 Performance
- Lazy loading de imágenes
- Minificación de CSS/JS
- Cache de assets

---

## FASE 7: Formulario de Contacto y CV (Día 18)

### 7.1 Contacto
- Livewire component o Controller + ruta
- Validación
- Guardar en BD
- Email de notificación al admin
- Mensaje de éxito/error al usuario

### 7.2 Descarga de CV
- Archivo almacenado en `storage/app/public/cv/`
- Enlace público o signed URL
- Actualizable desde Filament (upload de PDF)

---

## FASE 8: SEO y Despliegue (Días 19-20)

### 8.1 SEO
- Meta title y description dinámicos
- Open Graph tags
- Sitemap
- Schema.org (Person, CreativeWork)

### 8.2 Despliegue
- Vercel, Laravel Forge, o servidor VPS
- Variables de entorno en producción
- `php artisan optimize`
- CDN para assets (opcional)

---

# Resumen de Archivos Clave (estado actual)

```
app/
├── Models/
│   ├── Skill.php
│   ├── Service.php
│   ├── Project.php
│   ├── ProjectCategory.php
│   ├── Counter.php
│   ├── ContactMessage.php
│   ├── BlogPost.php
│   ├── Lead.php
│   └── SiteSetting.php
├── Services/
│   ├── PortfolioDataService.php
│   ├── LeadScoringService.php
│   └── LeadAutomationService.php
├── Jobs/
│   └── SyncLeadToEmailProvider.php
├── Filament/
│   ├── Pages/SiteSettings.php
│   ├── Resources/Leads/
│   ├── Resources/Projects/
│   ├── Resources/Skills/
│   ├── Resources/Services/
│   ├── Resources/Counters/
│   ├── Resources/ContactMessages/
│   ├── Resources/BlogPosts/
│   └── Resources/ProjectCategories/
└── View/Composers/PortfolioComposer.php

config/
└── lead_automation.php

database/migrations/
├── create_leads_table.php
└── ... (resto de tablas)

resources/views/
├── layouts/portfolio.blade.php
├── portfolio/components/
│   ├── hero.blade.php
│   ├── problem.blade.php
│   ├── about.blade.php
│   ├── case-studies.blade.php
│   ├── offers.blade.php
│   ├── skills.blade.php
│   ├── services.blade.php
│   ├── portfolio.blade.php
│   ├── blog.blade.php
│   ├── contact.blade.php
│   └── calendly.blade.php
├── components/⚡contact-form.blade.php
└── pages/home.blade.php
```

---

# FASE 9: Funnel de Captación (implementado)

Ver [FUNNEL_CAPTACION_IMPLEMENTADO.md](FUNNEL_CAPTACION_IMPLEMENTADO.md) para detalles.

### Resumen
- **Secciones**: Problema, Casos de éxito, Ofertas
- **Formulario de leads**: tipo proyecto, presupuesto, urgencia, qué automatizar
- **Lead scoring**: clasificación automática (frío/medio/caliente)
- **Tabla `leads`**: almacenamiento separado de `contact_messages`
- **Panel admin**: recurso Leads en `/admin/leads`
- **Email automation**: webhook genérico + Brevo
- **Calendly**: embed configurable en Site Settings

---

# Orden de Ejecución Recomendado

1. **Semana 1**: Fases 0, 1, 2 (setup + BD + Filament)
2. **Semana 2**: Fases 3, 4 (frontend completo)
3. **Semana 3**: Fases 5, 6, 7, 8 (modo oscuro, animaciones, contacto, deploy)
4. **Fase 9**: Funnel de captación (leads, scoring, automation)

---

# Recursos Adicionales

- [Filament Docs](https://filamentphp.com/docs)
- [Laravel Docs](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)
- [Devman en Envato](https://elements.envato.com/devman-personal-portfolio-react-template-ER3GN9J) - Para referencia visual (requiere suscripción)

---

*Guía creada para el proyecto portfolio_ramiro - Replicación del template Devman con Laravel + Filament*
