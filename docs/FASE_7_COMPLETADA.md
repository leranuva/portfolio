# Fase 7 Completada - Formulario de Contacto y CV

## Resumen

Se ha completado la Fase 7: notificación por email al admin al recibir mensajes de contacto, y descarga de CV con upload desde Filament.

## 7.1 Contacto

### Ya implementado
- Livewire component con validación
- Guardar en BD (`contact_messages`)
- Mensaje de éxito/error al usuario

### Añadido
- **Email de notificación al admin**: Se envía un correo al configurar `contact_email` en SiteSettings cuando alguien envía un mensaje
- Mailable `ContactMessageReceived` con markdown
- Reply-To al email del remitente para responder directamente

## 7.2 Descarga de CV

- **Archivo en** `storage/app/public/cv/`
- **Upload desde Filament**: Tab Hero → campo "CV (PDF)"
- **Solo PDF**, máx. 5 MB
- **URL pública**: `Storage::url()` genera el enlace
- **Compatibilidad**: Si `hero_cv_url` es una URL externa (http...), se usa tal cual

## Archivos creados/modificados

| Archivo | Cambios |
|---------|---------|
| `app/Mail/ContactMessageReceived.php` | Mailable para notificación |
| `resources/views/emails/contact-message-received.blade.php` | Plantilla del email |
| `resources/views/components/⚡contact-form.blade.php` | Envío de email tras crear mensaje |
| `app/Filament/Pages/SiteSettings.php` | FileUpload para CV, tab SEO |
| `app/Services/PortfolioDataService.php` | cvUrl con Storage::url() para paths |

## Configuración de email

En `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME="${APP_NAME}"
```

En desarrollo: `MAIL_MAILER=log` para guardar emails en `storage/logs/laravel.log`.

## Cómo probar

1. **Email**: Enviar mensaje desde el formulario → revisar log o bandeja
2. **CV**: Subir PDF en Filament → Configuración → Hero → CV (PDF)
3. **Enlace**: Ejecutar `php artisan storage:link` si no existe
