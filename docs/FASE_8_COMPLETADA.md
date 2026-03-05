# Fase 8 Completada - SEO y Despliegue

## Resumen

Se ha implementado SEO (meta dinámicos, Open Graph, Sitemap, Schema.org) y documentación de despliegue.

## 8.1 SEO

### Meta title y description
- **Dinámicos**: Se generan desde hero (nombre + título) si no se configuran
- **Override en Filament**: Tab SEO en Configuración → meta_title, meta_description

### Open Graph
- `og:type`, `og:url`, `og:title`, `og:description`, `og:locale`
- `og:image` si hay imagen de perfil

### Sitemap
- Ruta: `/sitemap.xml`
- Incluye la URL principal

### Schema.org
- **Person**: nombre, jobTitle, description, url, image, email
- **WebSite**: name, description, url

### robots.txt
- Ruta: `/robots.txt`
- Permite todo y referencia al sitemap

## Archivos creados/modificados

| Archivo | Cambios |
|---------|---------|
| `app/View/Composers/PortfolioComposer.php` | metaTitle, metaDescription, ogImage |
| `resources/views/layouts/portfolio.blade.php` | Open Graph tags |
| `resources/views/portfolio/components/seo-schema.blade.php` | JSON-LD Person, WebSite |
| `app/Http/Controllers/SitemapController.php` | Genera sitemap.xml |
| `resources/views/sitemap.blade.php` | Plantilla XML |
| `routes/web.php` | Rutas sitemap, robots |
| `app/Filament/Pages/SiteSettings.php` | Tab SEO |

## 8.2 Despliegue

### Comandos para producción

```bash
# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Assets
npm run build

# Storage link (si no existe)
php artisan storage:link
```

### Variables de entorno

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` = URL de producción
- `MAIL_*` para envío de emails
- `DB_*` para base de datos
- `LEAD_WEBHOOK_URL` (opcional) — webhook para leads
- `BREVO_API_KEY`, `BREVO_LEADS_LIST_ID` (opcional) — email automation
- `QUEUE_CONNECTION=database` — requiere worker para jobs de leads

### Opciones de hosting

- **Laravel Forge**: Gestión de servidores
- **VPS**: Nginx + PHP-FPM + MySQL
- **PaaS**: Laravel Vapor, Ploi, etc.

### Checklist pre-deploy

- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `npm run build`
- [ ] `php artisan optimize`
- [ ] Configurar `.env` de producción
- [ ] Configurar SSL/HTTPS
- [ ] Configurar worker de cola (`php artisan queue:work` o supervisor) si usas leads
