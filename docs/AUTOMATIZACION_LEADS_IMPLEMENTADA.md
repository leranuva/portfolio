# Automatización de Leads — Implementado

Documentación del sistema de captación y seguimiento automático de leads.

**Fecha:** Marzo 2026

---

## 1. Arquitectura del flujo

```
Visitante web
     ↓
Formulario (Livewire) — captura UTM de la URL
     ↓
LeadScoringService
     ↓
Guardar Lead en BD (source, utm_*, lead_events)
     ↓
AutomationService (sync)
     ↓
1️⃣ Email automático: score ≥ 10 → HighIntentLeadReceived | < 10 → LeadReceived
2️⃣ Guardar en CRM (Brevo)
3️⃣ Notificación al admin (ContactMessageReceived)
4️⃣ Webhook genérico (Zapier, Make, n8n)
5️⃣ Secuencia de follow-ups (+2, +5, +10 días)

Calendly webhook (invitee.created) → status = meeting_scheduled
```

---

## 2. Email automático al cliente

Cuando alguien envía el formulario, recibe inmediatamente un email de confirmación. El tipo de email depende del score:

| Score | Mailable | Contenido |
|-------|----------|-----------|
| ≥ 10 | `HighIntentLeadReceived` | CTA directo a Calendly |
| < 10 | `LeadReceived` | Mensaje genérico con CTA opcional |

### Archivos

| Archivo | Función |
|---------|---------|
| `app/Mail/LeadReceived.php` | Mailable genérico |
| `app/Mail/HighIntentLeadReceived.php` | Mailable para leads hot |
| `resources/views/emails/lead-received.blade.php` | Plantilla genérica |
| `resources/views/emails/high-intent-lead-received.blade.php` | Plantilla hot lead |

### Contenido del email

- Agradecimiento personalizado (nombre del lead)
- Confirmación de recepción
- Botón CTA: "Schedule a free call" (enlace a Calendly si está configurado)
- Firma con nombre del hero (Site Settings)

### Configuración

- Se envía en cola (`Mail::to($lead->email)->queue(...)`)
- Requiere que el worker de cola esté activo

---

## 3. Secuencia de follow-ups automáticos

El 80% de los clientes no responde al primer email. Se envían automáticamente 3 follow-ups adicionales.

### Archivos

| Archivo | Función |
|---------|---------|
| `app/Jobs/SendLeadFollowup.php` | Job que envía cada follow-up |
| `app/Mail/LeadFollowupMail.php` | Mailable con contenido por paso |
| `resources/views/emails/lead-followup.blade.php` | Plantilla con contenido dinámico |

### Calendario

| Día | Días desde lead | Contenido |
|-----|-----------------|-----------|
| 0 | Inmediato | LeadReceived (confirmación) |
| 2 | +2 días | Follow-up 1: "Just checking in" |
| 5 | +5 días | Follow-up 2: "3 signs your business needs automation" |
| 10 | +10 días | Follow-up 3: "One last thing" + CTA Calendly |

### Desactivar follow-ups

En `.env`:

```
LEAD_FOLLOWUP_EMAILS=false
```

O en `config/lead_automation.php`:

```php
'followup_emails_enabled' => false,
```

---

## 4. Pipeline de ventas (estados del lead)

El modelo `Lead` tiene un pipeline completo para gestionar el ciclo de ventas.

### Estados disponibles

| Estado | Valor BD | Descripción |
|-------|----------|-------------|
| New | `nuevo` | Lead recién creado |
| Contacted | `contacted` | Ya se ha contactado |
| Meeting scheduled | `meeting_scheduled` | Reunión agendada |
| Proposal sent | `proposal_sent` | Propuesta enviada |
| Won | `won` | Cliente ganado |
| Lost | `lost` | Oportunidad perdida |
| *(legacy)* In contact | `en_contacto` | |
| *(legacy)* Converted | `convertido` | |

### Actualización automática

El estado `meeting_scheduled` se actualiza automáticamente cuando alguien agenda en Calendly (webhook `POST /webhooks/calendly`).

### Uso en Filament

- **Formulario de edición**: Select con todos los estados, campos source y UTM
- **Filtros**: Filtrar por estado
- **Tabla**: Badge con color según estado (verde=won, amarillo=en progreso, rojo=lost), columna source

---

## 5. Dashboard de captación

El panel admin (`/admin`) incluye widgets de métricas de leads.

### LeadsStatsWidget

| Métrica | Descripción |
|---------|-------------|
| **Leads this month** | Cantidad de leads creados en el mes actual |
| **Hot leads (score ≥10)** | Leads con puntuación alta (alta intención) |
| **Conversion rate** | % de leads ganados (won/convertido) sobre el total |

### LatestLeadsWidget

- Tabla con los últimos 5 leads
- Columnas: nombre, email, proyecto, score, fecha
- Clic en fila → vista detalle del lead

### Archivos

| Archivo | Función |
|---------|---------|
| `app/Filament/Widgets/LeadsStatsWidget.php` | Stats overview |
| `app/Filament/Widgets/LatestLeadsWidget.php` | Tabla de últimos leads |

---

## 6. Página individual de blog

Cada post del blog tiene su propia URL para SEO y compartir.

### Ruta

```
GET /blog/{slug}
```

Ejemplo: `/blog/como-automatizar-tu-negocio`

### Archivos

| Archivo | Función |
|---------|---------|
| `app/Http/Controllers/BlogController.php` | Método `show($slug)` |
| `resources/views/blog/show.blade.php` | Vista del artículo |
| `routes/web.php` | Ruta `blog.show` |

### Características

- Meta title y description dinámicos (desde el post)
- Open Graph image (si el post tiene imagen)
- Contenido en Markdown (conversión a HTML)
- Enlace "Back to blog" → `/#blog`
- CTA "Get in touch" al final
- Enlaces desde las cards del blog en la home

### Sitemap

Los posts publicados se incluyen automáticamente en `/sitemap.xml` con:

- `loc`: URL del post
- `lastmod`: fecha de actualización
- `changefreq`: monthly
- `priority`: 0.8

---

## 7. Configuración de variables

### `.env`

```env
# Email automation (opcional)
LEAD_WEBHOOK_URL=https://hooks.zapier.com/...
BREVO_API_KEY=xkeysib-xxx
BREVO_LEADS_LIST_ID=2

# Follow-up emails (true por defecto)
LEAD_FOLLOWUP_EMAILS=true

# Calendly webhook (opcional: si el payload no incluye email)
CALENDLY_API_TOKEN=tu_token
```

### Cola de jobs

Para que los emails y follow-ups se envíen:

**Desarrollo:**
```bash
php artisan queue:work
```

O usar `QUEUE_CONNECTION=sync` para ejecución inmediata (sin worker).

**Producción:**
- Usar supervisor o systemd para mantener `php artisan queue:work` activo
- O configurar un cron que ejecute el worker

---

## 8. Resumen de archivos creados/modificados

| Archivo | Cambio |
|---------|--------|
| `app/Mail/LeadReceived.php` | Mailable confirmación al cliente |
| `app/Mail/HighIntentLeadReceived.php` | Mailable para leads hot (score ≥ 10) |
| `app/Mail/LeadFollowupMail.php` | Mailable follow-ups |
| `app/Jobs/SendLeadFollowup.php` | Job follow-ups + evento followup_sent |
| `app/Services/LeadAutomationService.php` | + scheduleFollowups() |
| `app/Services/LeadEventService.php` | Registro de eventos del funnel |
| `app/Models/Lead.php` | + statusOptions(), source, utm_*, events() |
| `app/Models/LeadEvent.php` | Modelo de eventos |
| `app/Http/Controllers/CalendlyWebhookController.php` | Webhook Calendly |
| `app/Http/Controllers/ProjectController.php` | Vista de proyecto |
| `resources/views/emails/lead-received.blade.php` | Plantilla |
| `resources/views/emails/high-intent-lead-received.blade.php` | Plantilla hot lead |
| `resources/views/emails/lead-followup.blade.php` | Plantilla |
| `resources/views/components/⚡contact-form.blade.php` | UTM, source, cualificación |
| `resources/views/components/⚡lead-magnet-form.blade.php` | Formulario lead magnet |
| `resources/views/pages/lead-magnet.blade.php` | Página lead magnet |
| `resources/views/projects/show.blade.php` | Vista proyecto individual |
| `resources/views/blog/show.blade.php` | + posts relacionados |
| `resources/views/portfolio/components/portfolio.blade.php` | Enlaces a /projects/{slug} |
| `app/Filament/Resources/Leads/Schemas/LeadForm.php` | Select status, source, utm_* |
| `app/Filament/Resources/Leads/Tables/LeadsTable.php` | Filtros, badges, source |
| `app/Http/Controllers/BlogController.php` | show + relatedPosts |
| `app/Http/Controllers/SitemapController.php` | + URLs blog, proyectos, lead magnet |
| `routes/web.php` | + blog, projects, lead-magnet, webhooks/calendly |
| `config/lead_automation.php` | + followup_emails_enabled, calendly_api_token |

---

## 9. Mejoras adicionales (Marzo 2026)

Ver [MEJORAS_IMPLEMENTADAS.md](MEJORAS_IMPLEMENTADAS.md) para el detalle completo.

| Mejora | Descripción |
|--------|-------------|
| **Lead source tracking** | Columnas `source`, `utm_source`, `utm_medium`, `utm_campaign` |
| **Lead events** | Tabla `lead_events` para historial del funnel |
| **Webhook Calendly** | `POST /webhooks/calendly` → `status = meeting_scheduled` |
| **Cualificación por score** | Score ≥ 10 → `HighIntentLeadReceived`; &lt; 10 → `LeadReceived` |
| **Lead magnet** | `/recursos/auditoria` — formulario email + website |
| **Posts relacionados** | 3 artículos relacionados al final de cada post |
| **Página de proyectos** | `GET /projects/{slug}` — cada proyecto con URL propia |

---

## 10. Resultado

El portfolio pasa de ser un **portfolio estático** a una **máquina de generación de leads** con:

- Respuesta automática inmediata al cliente
- Cualificación automática por score (emails distintos para hot/cold)
- Secuencia de follow-ups sin intervención manual
- Pipeline de ventas en el panel
- Webhook Calendly para actualizar `meeting_scheduled` automáticamente
- Tracking de fuente (UTM) y eventos del funnel
- Lead magnet para captación SEO
- Dashboard con métricas de captación
- Blog con páginas individuales y posts relacionados
- Proyectos con páginas individuales para SEO
