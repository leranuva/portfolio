# Fase 9 Completada — Funnel de Captación de Leads

## Resumen

El portfolio se ha transformado en una **máquina de captación de leads** con landing orientada a conversión, formulario inteligente, lead scoring y automatización de emails.

## 9.1 Nuevas secciones del landing

| Sección | ID | Descripción |
|---------|-----|-------------|
| Problema | `#problem` | Copy de dolor/necesidad del cliente |
| Casos de éxito | `#case-studies` | BlueDraft, Flat Rate Imports |
| Ofertas | `#offers` | 3 planes con CTA |
| Calendly | `#calendly` | Embed (condicional) |

## 9.2 Formulario de leads

- **Campos**: nombre, email, tipo de proyecto, qué automatizar, presupuesto, urgencia, mensaje
- **Lead scoring**: clasificación automática (frío 0–4, medio 5–8, caliente 9+)
- **Almacenamiento**: tabla `leads` + `contact_messages` (backward compat)
- **Notificación**: email al admin con cada lead

## 9.3 Panel admin

- **Leads** (`/admin/leads`): listado, filtros por estado/calidad, vista detalle, edición de status
- **Perfil de usuario** (`/admin/profile`): cambio de contraseña
- **Site Settings**: campo Calendly URL en tab Contact

## 9.4 Email automation

- **Webhook**: `LEAD_WEBHOOK_URL` — POST a Zapier, Make, n8n, etc.
- **Brevo**: `BREVO_API_KEY`, `BREVO_LEADS_LIST_ID` — añade leads a lista
- **Job**: `SyncLeadToEmailProvider` se ejecuta en cola

## Archivos creados/modificados

| Archivo | Cambio |
|---------|--------|
| `database/migrations/*_create_leads_table.php` | Nueva tabla |
| `app/Models/Lead.php` | Modelo con scoring |
| `app/Services/LeadScoringService.php` | Cálculo de score |
| `app/Services/LeadAutomationService.php` | Webhook + Brevo |
| `app/Jobs/SyncLeadToEmailProvider.php` | Job asíncrono |
| `config/lead_automation.php` | Configuración |
| `resources/views/portfolio/components/problem.blade.php` | Nueva sección |
| `resources/views/portfolio/components/case-studies.blade.php` | Nueva sección |
| `resources/views/portfolio/components/offers.blade.php` | Nueva sección |
| `resources/views/portfolio/components/calendly.blade.php` | Nueva sección |
| `resources/views/components/⚡contact-form.blade.php` | Formulario de leads |
| `app/Filament/Resources/Leads/*` | Recurso Filament |
| `app/Filament/Pages/SiteSettings.php` | Calendly URL |
| `app/Providers/Filament/AdminPanelProvider.php` | `->profile()` |
| `app/Services/PortfolioDataService.php` | calendlyUrl |
| `app/View/Composers/PortfolioComposer.php` | Nav actualizado |

## Dependencias añadidas

- `getbrevo/brevo-php` — SDK de Brevo para email automation
