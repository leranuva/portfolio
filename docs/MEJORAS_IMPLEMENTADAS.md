# Mejoras Implementadas — Marzo 2026

Documentación de las mejoras aplicadas al sistema de captación de leads, basadas en [EVALUACION_SISTEMA_Y_MEJORAS.md](EVALUACION_SISTEMA_Y_MEJORAS.md).

---

## 1. Lead source tracking

### Descripción

Se captura el origen de cada lead mediante parámetros UTM y un campo `source` para clasificar el tipo de formulario.

### Implementación

**Migración:** `add_source_to_leads_table`

| Columna     | Tipo   | Descripción                          |
|------------|--------|--------------------------------------|
| `source`   | string | Origen del lead: `contact`, `lead_magnet` |
| `utm_source`  | string | Ej: `google`, `linkedin`             |
| `utm_medium`  | string | Ej: `cpc`, `email`                   |
| `utm_campaign`| string | Nombre de la campaña                 |

**Flujo**

- Al cargar el formulario, se leen `utm_source`, `utm_medium`, `utm_campaign` de la URL.
- Se guardan en sesión para mantenerlos en navegaciones posteriores.
- Al crear el lead, se guardan en la base de datos.

**Archivos**

| Archivo | Cambio |
|---------|--------|
| `database/migrations/*_add_source_to_leads_table.php` | Nuevas columnas |
| `app/Models/Lead.php` | + `SOURCE_CONTACT`, `SOURCE_LEAD_MAGNET`, fillable |
| `resources/views/components/⚡contact-form.blade.php` | + mount(), UTM en sesión |
| `app/Filament/Resources/Leads/Schemas/LeadForm.php` | Campos source, utm_* |
| `app/Filament/Resources/Leads/Tables/LeadsTable.php` | Columna source |

---

## 2. Lead events tracking

### Descripción

Historial de eventos del funnel para métricas de conversión y tiempo por etapa.

### Implementación

**Migración:** `create_lead_events_table`

| Columna     | Tipo        | Descripción |
|------------|-------------|-------------|
| `lead_id`  | foreignId   | FK a leads  |
| `event_type` | string    | Tipo de evento |
| `metadata` | json        | Datos extra (opcional) |
| `created_at` | timestamp | Momento del evento |

**Tipos de evento**

| Evento            | Descripción                    |
|-------------------|--------------------------------|
| `lead_created`    | Lead creado                    |
| `email_sent`      | Email enviado al cliente       |
| `followup_sent`   | Follow-up enviado              |
| `meeting_booked`  | Reunión agendada (Calendly)    |
| `proposal_sent`   | Propuesta enviada             |
| `won`             | Cliente ganado                 |

**Archivos**

| Archivo | Función |
|---------|---------|
| `app/Models/LeadEvent.php` | Modelo con constantes de evento |
| `app/Services/LeadEventService.php` | `record(Lead, string, array)` |
| `app/Models/Lead.php` | Relación `events()` |
| `resources/views/components/⚡contact-form.blade.php` | + lead_created, email_sent |
| `app/Jobs/SendLeadFollowup.php` | + followup_sent |
| `app/Http/Controllers/CalendlyWebhookController.php` | + meeting_booked |

---

## 3. Webhook de Calendly

### Descripción

Cuando alguien agenda una reunión en Calendly, el lead se actualiza automáticamente a `meeting_scheduled`.

### Implementación

**Ruta**

```
POST /webhooks/calendly
```

- Sin CSRF (webhook externo).
- Responde siempre 200 para evitar reintentos innecesarios.

**Flujo**

1. Calendly envía `event: invitee.created` con payload.
2. Se extrae el email de `payload.invitee.email` (o del payload).
3. Si no viene, se usa la API de Calendly con `CALENDLY_API_TOKEN`.
4. Se busca el lead por email y se actualiza `status = meeting_scheduled`.
5. Se registra `meeting_booked` en `lead_events`.

**Configuración en Calendly**

1. Crear webhook en [Calendly Developer](https://developer.calendly.com/).
2. URL: `https://tudominio.com/webhooks/calendly`
3. Eventos: `invitee.created`

**Archivos**

| Archivo | Función |
|---------|---------|
| `app/Http/Controllers/CalendlyWebhookController.php` | Procesador del webhook |
| `config/lead_automation.php` | + `calendly_api_token` |
| `routes/web.php` | Ruta POST |

**Variables de entorno**

```env
# Opcional: si el payload no incluye email
CALENDLY_API_TOKEN=tu_token
```

---

## 4. Cualificación automática por score

### Descripción

Emails distintos según el score del lead.

- **Score ≥ 10 (hot):** Email con CTA directo a Calendly.
- **Score < 10:** Email genérico con info del portfolio.

### Implementación

| Score | Mailable | Contenido |
|------|----------|-----------|
| ≥ 10 | `HighIntentLeadReceived` | CTA directo a Calendly |
| < 10 | `LeadReceived` | Mensaje genérico |

**Archivos**

| Archivo | Función |
|---------|---------|
| `app/Mail/HighIntentLeadReceived.php` | Mailable para leads hot |
| `resources/views/emails/high-intent-lead-received.blade.php` | Plantilla |
| `resources/views/components/⚡contact-form.blade.php` | Lógica condicional |

---

## 5. Lead magnet

### Descripción

Formulario para descargar recurso gratuito (auditoría, checklist, etc.) a cambio de email.

### Implementación

**Ruta**

```
GET /recursos/auditoria
```

**Campos**

- Email (obligatorio)
- Website (opcional)

**Flujo**

- Se crea el lead con `source = lead_magnet`.
- Se registra `lead_created` con `metadata.resource = auditoria`.
- No se envía email automático ni follow-ups (solo captación).

**Archivos**

| Archivo | Función |
|---------|---------|
| `resources/views/components/⚡lead-magnet-form.blade.php` | Formulario Livewire |
| `resources/views/pages/lead-magnet.blade.php` | Página del recurso |
| `routes/web.php` | Ruta `lead-magnet.auditoria` |

---

## 6. Posts relacionados en el blog

### Descripción

Al final de cada artículo se muestran hasta 3 posts relacionados.

### Implementación

- Se excluye el post actual.
- Se ordenan por `published_at` descendente.
- Se muestran título, excerpt y enlace a cada post.

**Archivos**

| Archivo | Cambio |
|---------|--------|
| `app/Http/Controllers/BlogController.php` | + `$relatedPosts` |
| `resources/views/blog/show.blade.php` | Sección "Related articles" |

---

## 7. Página individual de proyectos

### Descripción

Cada proyecto tiene su propia URL para SEO y compartir.

### Implementación

**Ruta**

```
GET /projects/{slug}
```

**Características**

- Meta title y description dinámicos.
- Open Graph image (si el proyecto tiene imagen).
- Vista de video (si está configurado).
- Enlaces: "View live project" y "Get in touch".
- Las cards del portfolio enlazan a `/projects/{slug}` en lugar del modal.

**Archivos**

| Archivo | Función |
|---------|---------|
| `app/Http/Controllers/ProjectController.php` | Método `show($slug)` |
| `resources/views/projects/show.blade.php` | Vista del proyecto |
| `resources/views/portfolio/components/portfolio.blade.php` | Enlaces a proyectos |
| `app/Http/Controllers/SitemapController.php` | + URLs de proyectos |
| `routes/web.php` | Ruta `projects.show` |

---

## 8. Resumen de archivos creados/modificados

### Nuevos

| Archivo | Descripción |
|---------|-------------|
| `app/Models/LeadEvent.php` | Modelo de eventos |
| `app/Services/LeadEventService.php` | Servicio de registro de eventos |
| `app/Mail/HighIntentLeadReceived.php` | Mailable para leads hot |
| `app/Http/Controllers/CalendlyWebhookController.php` | Webhook Calendly |
| `app/Http/Controllers/ProjectController.php` | Vista de proyecto |
| `resources/views/emails/high-intent-lead-received.blade.php` | Plantilla email |
| `resources/views/components/⚡lead-magnet-form.blade.php` | Formulario lead magnet |
| `resources/views/pages/lead-magnet.blade.php` | Página lead magnet |
| `resources/views/projects/show.blade.php` | Vista del proyecto |
| `database/migrations/*_add_source_to_leads_table.php` | Columnas source |
| `database/migrations/*_create_lead_events_table.php` | Tabla lead_events |

### Modificados

| Archivo | Cambio |
|---------|--------|
| `app/Models/Lead.php` | + source, utm_*, events(), constantes |
| `resources/views/components/⚡contact-form.blade.php` | UTM, source, eventos, cualificación |
| `app/Jobs/SendLeadFollowup.php` | + followup_sent |
| `app/Filament/Resources/Leads/Schemas/LeadForm.php` | Campos source, utm_* |
| `app/Filament/Resources/Leads/Tables/LeadsTable.php` | Columna source |
| `app/Http/Controllers/BlogController.php` | + relatedPosts |
| `resources/views/blog/show.blade.php` | Sección "Related articles" |
| `resources/views/portfolio/components/portfolio.blade.php` | Enlaces a proyectos |
| `app/Http/Controllers/SitemapController.php` | + proyectos, lead magnet |
| `config/lead_automation.php` | + calendly_api_token |
| `routes/web.php` | + projects, lead-magnet, webhooks/calendly |

---

## 9. Configuración de variables

### `.env` actualizado

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

---

## 10. Rutas nuevas

| Método | Ruta | Nombre |
|--------|------|--------|
| GET | `/projects/{slug}` | `projects.show` |
| GET | `/recursos/auditoria` | `lead-magnet.auditoria` |
| POST | `/webhooks/calendly` | `webhooks.calendly` |
