# Funnel de Captación de Clientes — Implementado

## Resumen

El portfolio se ha transformado en una **máquina de captación de leads** con:

- Landing enfocada en servicios y problemas del cliente
- Casos de éxito (BlueDraft, Flat Rate Imports)
- Ofertas claras (3 planes)
- Formulario inteligente con lead scoring
- Panel admin para gestionar leads
- Integración Calendly

---

## 1. Secciones del Landing

### Problema → Necesidad (`#problem`)
- Copy: "¿Estás perdiendo tiempo con tareas repetitivas?"
- "¿Tu negocio opera con Excel, papeles o sistemas antiguos?"
- CTA: "→ Puedo ayudarte"

### Casos de Éxito (`#case-studies`)
- **BlueDraft**: Problema, solución, tecnologías
- **Flat Rate Imports**: Sistema de cotización de importaciones para Ecuador con cálculo de impuestos, seguimiento de paquetes y panel admin

### Ofertas (`#offers`)
- **Plan 1 — Automatización de Procesos**: Auditoría, desarrollo, APIs, soporte
- **Plan 2 — Sistema Web a Medida**: Dashboard, usuarios, automatización, despliegue (destacado)
- **Plan 3 — Servicio Freelance Flexible**: Desarrollo continuo, retainer mensual

### Formulario de Captación (`#contact`)
- Nombre, Email
- Tipo de proyecto
- ¿Qué necesitas automatizar?
- Presupuesto estimado (bajo/medio/alto)
- Urgencia (flexible/pronto/inmediato)
- Mensaje adicional (opcional)

### Calendly (`#calendly`)
- Sección condicional: solo visible si hay URL configurada en Site Settings
- Embed de consulta gratuita de 30 min

---

## 2. Base de Datos — Tabla `leads`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | PK | |
| name | string | Nombre |
| email | string | Email |
| project_type | string | Tipo de proyecto |
| what_to_automate | text | Qué automatizar |
| budget_range | string | bajo, medio, alto |
| urgency | string | flexible, pronto, inmediato |
| message | text | Detalles adicionales |
| score | int | Lead score (0–15) |
| status | string | nuevo, contacted, meeting_scheduled, proposal_sent, won, lost |
| created_at, updated_at | timestamps | |

---

## 3. Lead Scoring

**Puntaje automático:**

| Criterio | Puntos |
|----------|--------|
| Presupuesto alto | +4 |
| Presupuesto medio | +2 |
| Presupuesto bajo | +1 |
| Urgencia inmediato | +3 |
| Urgencia pronto | +2 |
| Urgencia flexible | +1 |
| Proyecto: automatización | +5 |
| Proyecto: web/sistema | +3 |
| Proyecto: freelance | +2 |
| Menciona procesos/APIs/integraciones | +2 |

**Clasificación:**
- **0–4**: Frío
- **5–8**: Medio
- **9+**: Caliente

---

## 4. Panel Admin — Leads

- **Ruta**: `/admin/leads`
- **Listado**: nombre, email, tipo, presupuesto, urgencia, score, calidad, estado
- **Filtros**: estado (pipeline completo), calidad (frío/medio/caliente)
- **Vista detalle**: todos los campos
- **Edición**: solo el campo `status` (pipeline: new, contacted, meeting_scheduled, proposal_sent, won, lost)
- **Dashboard**: widgets de leads del mes, leads calientes, tasa de conversión, últimos 5 leads

---

## 5. Configuración

### Site Settings → Contact
- **Calendly URL**: URL de tu evento (ej: `https://calendly.com/tu-usuario/30min`)
- Si está vacío, la sección Calendly no se muestra

---

## 6. Email Automation (implementado)

### Email automático al cliente
- **LeadReceived**: Respuesta inmediata al enviar el formulario
- Incluye CTA a Calendly si está configurado

### Secuencia de follow-ups
- **SendLeadFollowup** (job): +2, +5, +10 días
- Contenido diferenciado por paso (recordatorio, tips, último CTA)
- Desactivar: `LEAD_FOLLOWUP_EMAILS=false` en `.env`

### Webhook genérico
- Variable `.env`: `LEAD_WEBHOOK_URL`
- Al crear un lead, se hace POST con los datos a la URL
- Compatible con Zapier, Make, n8n, etc.

### Brevo (ex-Sendinblue)
- Variables `.env`: `BREVO_API_KEY`, `BREVO_LEADS_LIST_ID`
- El lead se añade a la lista configurada
- Configura la secuencia de emails en el panel de Brevo

### Job asíncrono
- `SyncLeadToEmailProvider` se ejecuta en cola
- **Desarrollo**: `php artisan queue:work` en otra terminal, o `QUEUE_CONNECTION=sync` en `.env` para ejecución inmediata
- **Producción**: supervisor o similar para mantener el worker activo

> Ver documentación completa: [AUTOMATIZACION_LEADS_IMPLEMENTADA.md](AUTOMATIZACION_LEADS_IMPLEMENTADA.md)

---

## 7. Guía de Marketing
Ver `docs/GUIA_MARKETING_LEADS.md` para:
- Temas LinkedIn
- Secuencia de emails sugerida
- Ideas de artículos SEO

---

## Archivos creados/modificados

| Archivo | Cambio |
|---------|--------|
| `database/migrations/*_create_leads_table.php` | Nueva tabla |
| `app/Models/Lead.php` | Modelo con scoring, statusOptions(), pipeline |
| `app/Services/LeadScoringService.php` | Cálculo de score |
| `app/Services/LeadAutomationService.php` | Webhook + Brevo + scheduleFollowups |
| `app/Jobs/SyncLeadToEmailProvider.php` | Job asíncrono |
| `app/Jobs/SendLeadFollowup.php` | Job follow-ups |
| `app/Mail/LeadReceived.php` | Email automático al cliente |
| `app/Mail/LeadFollowupMail.php` | Email follow-ups |
| `config/lead_automation.php` | Configuración + followup_emails_enabled |
| `resources/views/portfolio/components/problem.blade.php` | Nueva sección |
| `resources/views/portfolio/components/case-studies.blade.php` | Nueva sección |
| `resources/views/portfolio/components/offers.blade.php` | Nueva sección |
| `resources/views/portfolio/components/calendly.blade.php` | Nueva sección |
| `resources/views/components/⚡contact-form.blade.php` | Formulario + LeadReceived |
| `app/Filament/Resources/Leads/*` | Recurso Filament + pipeline |
| `app/Filament/Widgets/LeadsStatsWidget.php` | Dashboard stats |
| `app/Filament/Widgets/LatestLeadsWidget.php` | Dashboard últimos leads |
| `app/Filament/Pages/SiteSettings.php` | Campo Calendly URL |
| `app/Http/Controllers/BlogController.php` | Página individual blog |
| `app/Http/Controllers/SitemapController.php` | + URLs blog |
| `app/View/Composers/PortfolioComposer.php` | Nav con nuevas secciones |
| `.env.example` | LEAD_WEBHOOK_URL, BREVO_*, LEAD_FOLLOWUP_EMAILS |
