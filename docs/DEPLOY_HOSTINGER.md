# Despliegue en Hostinger (File Manager)

Guía para subir el proyecto a Hostinger usando solo el **File Manager** del panel (sin FTP, sin ZIP).

---

## Requisitos previos

- Cuenta Hostinger con hosting (PHP 8.2+, MySQL)
- Composer y Node.js instalados en tu PC (para preparar el proyecto)

---

## Paso 1: Preparar el proyecto en tu PC

En la raíz del proyecto, ejecuta:

```powershell
.\prepare-hostinger.ps1
```

Esto crea la carpeta `hostinger-deploy/` con:

- Código fuente (sin node_modules, .git, tests)
- `vendor/` (composer install --no-dev)
- `public/build/` (assets compilados)
- `.env.example` (plantilla para producción)

---

## Paso 2: Estructura en Hostinger

El document root del dominio debe apuntar exclusivamente al directorio `public/` de Laravel.

### Cambiar Document Root

1. En hPanel → **Dominios** → tu dominio → **Configuración avanzada**
2. Cambia **Document Root** a: `public_html/portfolio/public`
3. Crea la carpeta `public_html/portfolio/`
4. Sube **todo el contenido** de `hostinger-deploy/` dentro de `portfolio/`

Estructura final:

```
public_html/
  portfolio/
    app/
    bootstrap/
    config/
    database/
    public/          ← Document Root apunta aquí
      index.php
      .htaccess
      build/
    resources/
    routes/
    storage/
    vendor/
    .env
    artisan
    composer.json
```

Si tu plan fija `public_html` como document root, conserva el proyecto y el `.env` fuera de ese directorio. Coloca en `public_html` solo el contenido de `public/` y ajusta `index.php` para que las rutas de mantenimiento, autoload y bootstrap apunten al proyecto privado. Comprueba que ningún dominio adicional sirve ese directorio privado.

---

## Paso 3: Subir archivos con File Manager

1. Entra a **hPanel** → **Archivos** → **Administrador de archivos**
2. Navega al directorio del proyecto fuera del alcance HTTP
3. Crea las carpetas si no existen
4. **Subir carpeta por carpeta**: selecciona la carpeta local, arrastra o usa "Subir archivos"
   - Hostinger permite subir múltiples archivos; para carpetas, sube el contenido de cada una

**Orden sugerido** (las más pesadas al final):

1. `app/`
2. `bootstrap/`
3. `config/`
4. `database/`
5. `resources/`
6. `routes/`
7. `storage/` (solo la estructura; vacía)
8. `public/`
9. `vendor/` (puede tardar; son muchos archivos)
10. Archivos raíz: `artisan`, `composer.json`, `composer.lock`

---

## Paso 4: Configurar .env

1. En File Manager, renombra `.env.example` a `.env`
2. Edita `.env` con los datos de Hostinger:

```env
APP_NAME="Portfolio"
APP_ENV=production
APP_KEY=                    # Se genera en el paso 6
APP_DEBUG=false
APP_URL=https://tudominio.com
ADMIN_EMAILS=administrador@tudominio.com
SESSION_SECURE_COOKIE=true
CACHE_STORE=database

# MySQL (datos en hPanel → Bases de datos)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456789_portfolio
DB_USERNAME=u123456789_portfolio
DB_PASSWORD=tu_password

# SMTP opcional para notificaciones internas
CONTACT_NOTIFY_ADMIN=false
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=tu_email@tudominio.com
MAIL_PASSWORD=tu_password
MAIL_FROM_ADDRESS="noreply@tudominio.com"

TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
CALENDLY_WEBHOOK_SIGNING_KEY=
LEAD_FOLLOWUP_EMAILS=false
```

---

## Paso 5: Crear base de datos MySQL

1. hPanel → **Bases de datos** → **MySQL**
2. Crear base de datos (ej: `u123456789_portfolio`)
3. Crear usuario y asignarlo a la base de datos
4. Anota nombre, usuario y contraseña para el `.env`

---

## Paso 6: Ejecutar comandos (Terminal)

Hostinger incluye **Terminal** en el panel. Entra y ejecuta:

```bash
cd ~/public_html/portfolio
# o cd ~/domains/tudominio.com/public_html/portfolio

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

En una instalación nueva, genera `APP_KEY` antes de ejecutar los comandos. En una actualización conserva la clave existente. Si la clave estuvo expuesta, planifica su rotación e identifica los datos cifrados que deban migrarse antes de cambiarla.

```bash
php artisan key:generate --show
```

Copia el resultado y pégalo en `APP_KEY=` del `.env` privado. Comprueba que el usuario administrador existe y que su email aparece en `ADMIN_EMAILS`. Para crear uno nuevo usa `php artisan make:filament-user` con una contraseña única. Revisa y deshabilita las cuentas de prueba anteriores.

---

## Paso 7: Permisos

En File Manager, permisos recomendados:

| Carpeta | Permisos |
|---------|----------|
| `storage/` | Escritura solo para el usuario de PHP o su grupo de aplicación |
| `bootstrap/cache/` | Escritura solo para el usuario de PHP o su grupo de aplicación |
| `.env` | Lectura para el propietario y, si es necesario, el grupo de aplicación |

---

## Paso 8: Recuperación del correo y verificación

Sigue el [plan de seguridad](PLAN_SEGURIDAD.md) para preservar los registros, pausar workers anteriores, desplegar las migraciones y verificar el formulario antes de reactivar SMTP. La captura funciona con el envío deshabilitado. Habilita `CONTACT_NOTIFY_ADMIN=true` solo para notificaciones internas y después de comprobar el destinatario configurado en el panel.

---

## Resumen de carpetas a subir

| Incluir | Excluir |
|---------|---------|
| app, bootstrap, config, database | node_modules |
| resources, routes, storage | .git |
| public (con build/) | tests |
| vendor | .env (local) |
| artisan, composer.json, composer.lock | *.log |

---

## Solución de problemas

**Error 500**
- Revisa permisos de `storage/` y `bootstrap/cache/`
- Revisa `storage/logs/laravel.log`

**Página en blanco**
- Mantén `APP_DEBUG=false` en el dominio público y consulta los registros privados
- Comprueba que `APP_KEY` está definido

**CSS/JS no cargan**
- Verifica que `public/build/` se subió correctamente
- Revisa `APP_URL` en .env

**Base de datos**
- Comprueba que el usuario MySQL tiene permisos sobre la BD
- En Hostinger, `DB_HOST` suele ser `localhost`

**Imágenes del blog/proyectos no cargan**
- Verifica que existe el enlace simbólico: `public/storage` → `storage/app/public`
- Si `storage:link` falla, crea manualmente la carpeta `public/storage` y copia el contenido de `storage/app/public` dentro
- Comprueba que `APP_URL` en `.env` coincide con tu dominio (ej: `https://ramironuva.com`)
- Revisa permisos 775 en `storage/` y `storage/app/public/`

**No recibo emails del formulario de contacto**
1. Comprueba `CONTACT_NOTIFY_ADMIN` y configura SMTP en el `.env` privado. Para el puerto 465 usa `MAIL_SCHEME=smtps`.
2. **Configura contact_email** en Admin → Settings (Filament). Es el email donde recibes las notificaciones.
3. Si MAIL_MAILER=log (por defecto), los emails se guardan en `storage/logs/laravel.log` y no se envían.
4. Revisa `storage/logs/laravel.log` para ver errores de envío.
