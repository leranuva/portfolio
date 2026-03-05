# Guía de Marketing para Captación de Leads

## Objetivo

Llevar tráfico cualificado a tu web → formulario de leads.

---

## 1. Estrategia de Contenidos en LinkedIn

### Temas sugeridos para publicaciones

1. **Cómo automatizar tu negocio en 2026**
   - Casos reales (sin revelar datos sensibles)
   - Herramientas que usas
   - CTA: "¿Quieres saber si tu proceso se puede automatizar? Escríbeme."

2. **Por qué Excel está frenando tu crecimiento**
   - Limitaciones de hojas de cálculo para operaciones complejas
   - Cuándo tiene sentido migrar a un sistema
   - CTA: enlace a tu web

3. **Casos reales de automatización**
   - Antes/después (tiempo ahorrado, errores reducidos)
   - Testimonios si los tienes
   - CTA: "Cuéntame tu caso en [tu-web]#contact"

4. **Qué esperar al contratar a un freelance**
   - Proceso de trabajo
   - Comunicación, plazos, entregables
   - CTA: formulario o Calendly

### Frecuencia recomendada

- 2–3 publicaciones por semana
- Incluir siempre enlace a tu web o formulario

---

## 2. Secuencia de Emails (configurar en Brevo)

Cuando un lead entra (vía Brevo), configura esta secuencia en el panel de Brevo:

| Día | Email | Contenido |
|-----|-------|-----------|
| 0 | Bienvenida | "Gracias por contactar. Así trabajo: proceso, plazos, siguiente paso." |
| 2 | Caso de éxito | Resumen de BlueDraft o Flat Rate Imports |
| 5 | Tips | "3 señales de que tu negocio necesita automatización" |
| 7 | CTA | "¿Hablamos 30 min? Agenda aquí: [Calendly]" |

### Cómo configurarla en Brevo

1. Automatización → Crear campaña → Automatización basada en lista
2. Trigger: contacto añadido a lista "Leads"
3. Añadir pasos: espera X días → enviar email
4. Usar plantillas con variables: `{{ contact.FIRSTNAME }}`

---

## 3. SEO — Artículos para el Blog

Publica artículos enfocados en:

1. **Automatización de negocios**
   - "Cómo automatizar la facturación de tu negocio"
   - "De Excel a sistema: guía para pymes"

2. **SaaS a medida**
   - "Plantilla vs software a medida: cuándo elegir cada uno"
   - "Qué incluir en un sistema de gestión interno"

3. **Procesos y eficiencia**
   - "5 procesos que deberías automatizar en 2026"
   - "Integración de APIs: qué es y por qué importa"

### Estructura recomendada

- Título con palabra clave principal
- Introducción que responda la búsqueda
- Secciones con H2/H3
- CTA final: "¿Tienes un proyecto en mente? [Formulario]"

---

## 4. Checklist de Acción

### Configuración Brevo (backend ya integrado)
- [ ] Crear lista "Leads" en Brevo
- [ ] Configurar secuencia de 4 emails en Brevo (ver sección 2)
- [ ] Añadir `BREVO_API_KEY` y `BREVO_LEADS_LIST_ID` al `.env`
- [x] (Opcional) Webhook genérico para Zapier/Make — implementado

### Marketing y seguimiento
- [ ] Publicar 1–2 artículos SEO en el blog
- [ ] Publicar en LinkedIn 2–3 veces por semana
- [ ] Revisar leads en `/admin/leads` y priorizar por score
