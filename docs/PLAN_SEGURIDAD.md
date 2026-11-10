# Plan de corrección de seguridad

Base de la auditoría: `670c0ff366c02b88b543584d94e32881ccf1e04a`.

## Correcciones aplicadas en el código

| Prioridad | Área | Resultado |
| --- | --- | --- |
| P0 | Abuso de SMTP | El formulario conserva el lead y el mensaje en una transacción, sin enviar al visitante ni lanzar automatizaciones. La notificación interna es opcional, con texto y encabezados fijos y sin `Reply-To` del visitante. Los trabajos antiguos comprueban que la dirección fue verificada antes de enviar o sincronizar. Esta versión no marca direcciones como verificadas. |
| P0 | Webhook Calendly | La ruta requiere una clave de firma, verifica el cuerpo original con HMAC SHA-256 y tolerancia de 180 segundos y registra una sola vez cada invitación. El servidor dejó de hacer consultas a URLs del payload. |
| P1 | Formularios y validación | Los POST de contacto comparten límites de 5 solicitudes/minuto por IP, 30/minuto globales y 3/hora por destinatario. El formulario Livewire de recursos tiene límites independientes con los mismos umbrales. La deduplicación de solicitudes idénticas dura 10 minutos y requiere una caché compartida con soporte de bloqueos. El formulario principal incluye honeypot y validación opcional de Turnstile. Se validan emails y UTM, y se limita el asunto almacenado. |
| P1 | Dependencias | Se actualizaron los dos archivos de bloqueo, se fijó la plataforma Composer en PHP 8.2 y se republicaron los assets de Livewire y Filament. |
| P1 | Admin y despliegue | Filament exige que la dirección aparezca en `ADMIN_EMAILS`. El seeder deja de crear el usuario de prueba. El paquete de despliegue excluye el `.env` real, la base SQLite local, los caches generados, el directorio de almacenamiento local y `public/hot`. La guía exige servir solo `public/`. |

## Aplicación en Hostinger

1. **Contener y preservar.** Mantén suspendido SMTP mientras investigas. Exporta los registros de acceso y entrega de Hostinger para `info@ramironuva.com`, los registros HTTP disponibles y las marcas de tiempo de los leads y eventos. Conserva una copia de la base y de la configuración privada. Pausa los workers y el cron que aún ejecuten la versión anterior. Identifica los trabajos de seguimiento y sincronización pendientes, incluidos los fallidos, antes de descartar o reanudar tareas.
2. **Preparar la configuración.** Confirma que el document root y los aliases sirven solo `public/`. Conserva `APP_KEY` salvo que se requiera rotar una clave expuesta. Fija `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `CACHE_STORE=database` y el hostname canónico en `APP_URL`. Introduce en `ADMIN_EMAILS` la cuenta administrativa existente y deshabilita cualquier `admin@example.com` de prueba. Si hay proxy, verifica que la app registra la IP real y confía solo en los proxies autorizados.
3. **Desplegar con envío pausado.** Usa los lockfiles y assets de esta versión. Deja `CONTACT_NOTIFY_ADMIN=false` y `LEAD_FOLLOWUP_EMAILS=false`. Ejecuta `php artisan migrate --force`, vuelve a generar las cachés de configuración, rutas y vistas y reinicia procesos persistentes. Comprueba el panel, una captura de prueba y la respuesta `429` al superar el límite. Verifica que `/.env`, `/.git/config`, las copias de seguridad y los archivos internos quedan fuera del alcance HTTP.
4. **Configurar integraciones.** Para Turnstile crea el widget para el hostname canónico y configura `TURNSTILE_SITE_KEY` y `TURNSTILE_SECRET_KEY` juntas. Para Calendly, crea o actualiza la suscripción con la misma clave que `CALENDLY_WEBHOOK_SIGNING_KEY`, comprueba un evento firmado y retira el antiguo `CALENDLY_API_TOKEN`. Revoca ese token si se utilizó en la versión anterior. Deja ambas integraciones sin claves cuando estén fuera de uso.
5. **Recuperar SMTP.** Cruza destinatarios y horas de envío con los POST y eventos de la app, y compara la IP de acceso SMTP con la salida del hosting. Rota la contraseña del buzón y revoca contraseñas de aplicación desconocidas antes de reactivar el envío. Si necesitas alertas internas, valida el destinatario del panel y habilita `CONTACT_NOTIFY_ADMIN=true`. Usa un destinatario controlado para la prueba y supervisa los rechazos. Para reintroducir correo externo o Brevo, primero implementa verificación de titularidad con cuota propia e idempotencia de los envíos.

La causa de la suspensión requiere esos registros. Las operaciones de este apartado se ejecutan en el entorno de Hostinger y requieren acceso a hPanel y a la aplicación desplegada.
