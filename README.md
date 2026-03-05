# Portfolio Ramiro

Portfolio personal con funnel de captación de leads. Desarrollado con Laravel 12, Filament 5, Blade, Livewire, Tailwind CSS y Alpine.js.

## Características

- **Landing orientada a conversión**: secciones Problema, Casos de éxito, Ofertas
- **Formulario de leads** con scoring automático (frío/medio/caliente)
- **Panel admin** (Filament): leads, proyectos, skills, servicios, blog, configuración
- **Email automation**: webhook genérico + integración Brevo
- **Calendly** embebido para consultas
- **SEO**: meta dinámicos, Open Graph, Sitemap, Schema.org
- **Modo oscuro/claro** con `prefers-color-scheme`

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (dev) / MySQL (prod)

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan storage:link
```

## Desarrollo

```bash
php artisan serve
# En otra terminal (para cola de leads):
php artisan queue:work
```

O usar `composer dev` si está configurado.

## Configuración

### Variables de entorno relevantes

| Variable | Descripción |
|---------|-------------|
| `LEAD_WEBHOOK_URL` | Webhook para enviar leads (Zapier, Make, etc.) |
| `BREVO_API_KEY` | API key de Brevo para email automation |
| `BREVO_LEADS_LIST_ID` | ID de lista en Brevo |
| `QUEUE_CONNECTION` | `sync` (inmediato) o `database` (requiere worker) |

### Admin

- URL: `/admin`
- Crear usuario: `php artisan make:filament-user`
- Configuración: Site Settings (perfil, SEO, contacto, Calendly)

## Documentación

| Documento | Contenido |
|-----------|-----------|
| [docs/README.md](docs/README.md) | Índice de documentación |
| [docs/GUIA_IMPLEMENTACION_PORTFOLIO.md](docs/GUIA_IMPLEMENTACION_PORTFOLIO.md) | Guía completa del proyecto |
| [docs/FUNNEL_CAPTACION_IMPLEMENTADO.md](docs/FUNNEL_CAPTACION_IMPLEMENTADO.md) | Funnel de leads, scoring, automation |
| [docs/GUIA_MARKETING_LEADS.md](docs/GUIA_MARKETING_LEADS.md) | Estrategia LinkedIn, emails, SEO |

## Estructura del proyecto

```
app/
├── Filament/           # Panel admin (Resources, Pages)
├── Http/Controllers/
├── Jobs/               # SyncLeadToEmailProvider
├── Models/
├── Services/           # PortfolioDataService, LeadScoringService, LeadAutomationService
resources/views/
├── portfolio/components/  # Hero, About, Skills, Services, Portfolio, Blog, Contact
├── portfolio/components/  # Problem, Case-studies, Offers, Calendly
└── components/         # Contact form (Volt/Livewire)
```

## Licencia

MIT
