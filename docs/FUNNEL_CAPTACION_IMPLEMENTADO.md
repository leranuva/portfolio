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
| status | string | nuevo, en_contacto, convertido |
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
- **Filtros**: estado (nuevo/en contacto/convertido), calidad (frío/medio/caliente)
- **Vista detalle**: todos los campos
- **Edición**: solo el campo `status` (para mover leads por el funnel)

---

## 5. Configuración

### Site Settings → Contact
- **Calendly URL**: URL de tu evento (ej: `https://calendly.com/tu-usuario/30min`)
- Si está vacío, la sección Calendly no se muestra

---

## 6. Email Automation (implementado)

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
| `app/Filament/Pages/SiteSettings.php` | Campo Calendly URL |
| `app/Providers/Filament/AdminPanelProvider.php` | `->profile()` |
| `app/Services/PortfolioDataService.php` | calendlyUrl en contact |
| `app/View/Composers/PortfolioComposer.php` | Nav con nuevas secciones |
| `.env.example` | LEAD_WEBHOOK_URL, BREVO_* |
