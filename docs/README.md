# Documentación del Proyecto — Portfolio Ramiro

Índice de la documentación disponible.

---

## Guías principales

| Documento | Descripción |
|-----------|-------------|
| [GUIA_IMPLEMENTACION_PORTFOLIO.md](GUIA_IMPLEMENTACION_PORTFOLIO.md) | Guía completa: fases, stack, estructura, archivos a crear |
| [FUNNEL_CAPTACION_IMPLEMENTADO.md](FUNNEL_CAPTACION_IMPLEMENTADO.md) | Funnel de leads: secciones, scoring, panel admin, email automation |
| [GUIA_MARKETING_LEADS.md](GUIA_MARKETING_LEADS.md) | Estrategia de marketing: LinkedIn, secuencia de emails, SEO |

---

## Fases completadas

| Documento | Contenido |
|-----------|-----------|
| [FASE_0_COMPLETADA.md](FASE_0_COMPLETADA.md) | Preparación del entorno |
| [FASE_1_COMPLETADA.md](FASE_1_COMPLETADA.md) | Base de datos y modelos |
| [FASE_2_COMPLETADA.md](FASE_2_COMPLETADA.md) | Panel Filament |
| [FASE_3_COMPLETADA.md](FASE_3_COMPLETADA.md) | Layout y navegación |
| [FASE_4_COMPLETADA.md](FASE_4_COMPLETADA.md) | Secciones del portfolio |
| [FASE_5_COMPLETADA.md](FASE_5_COMPLETADA.md) | Modo oscuro/claro |
| [FASE_6_COMPLETADA.md](FASE_6_COMPLETADA.md) | Animaciones y polish |
| [FASE_7_COMPLETADA.md](FASE_7_COMPLETADA.md) | Formulario de contacto y CV |
| [FASE_8_COMPLETADA.md](FASE_8_COMPLETADA.md) | SEO y despliegue |
| [FASE_9_COMPLETADA.md](FASE_9_COMPLETADA.md) | Funnel de captación de leads |

---

## Evaluación

| Documento | Descripción |
|-----------|-------------|
| [EVALUACION_PROYECTO.md](EVALUACION_PROYECTO.md) | Estado del proyecto: qué está implementado y qué falta por hacer |

---

## Otros

| Documento | Descripción |
|-----------|-------------|
| [project_rules.md](project_rules.md) | Reglas de desarrollo: sin código inline, DRY, modular |

---

## Resumen del estado actual

El proyecto incluye:

- **Frontend**: Hero, Problema, About, Casos de éxito, Ofertas, Skills, Services, Portfolio, Blog, Contact, Calendly
- **Leads**: Formulario con scoring, tabla `leads`, recurso Filament, job de sincronización
- **Email automation**: Webhook genérico + Brevo
- **Admin**: Site Settings (perfil, SEO, contacto, Calendly), Leads, Projects, Skills, Services, Counters, Blog, Contact Messages
- **Perfil de usuario**: Cambio de contraseña en `/admin/profile`
