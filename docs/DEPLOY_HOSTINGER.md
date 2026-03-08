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

Hostinger usa `public_html` como raíz web. Tienes dos opciones:

### Opción A: Cambiar Document Root (recomendado)

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

### Opción B: Todo en public_html (sin cambiar Document Root)

Si no puedes cambiar el Document Root:

1. Sube el contenido de `hostinger-deploy/public/` a `public_html/`
2. Sube el resto (app, bootstrap, config, etc.) a `public_html/` también
3. Edita `public_html/index.php` y cambia las rutas:

```php
// Antes:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Después:
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

---

## Paso 3: Subir archivos con File Manager

1. Entra a **hPanel** → **Archivos** → **Administrador de archivos**
2. Navega a `public_html/portfolio/` (o `public_html/` si usas Opción B)
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

# MySQL (datos en hPanel → Bases de datos)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456789_portfolio
DB_USERNAME=u123456789_portfolio
DB_PASSWORD=tu_password

# Mail (hPanel → Correo electrónico)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=tu_email@tudominio.com
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@tudominio.com"
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

php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si no tienes Terminal, genera la key localmente y añádela al .env:

```bash
php artisan key:generate --show
```

Copia el resultado y pégalo en `APP_KEY=` del `.env`.

---

## Paso 7: Permisos

En File Manager, permisos recomendados:

| Carpeta | Permisos |
|---------|----------|
| `storage/` | 775 (recursivo) |
| `bootstrap/cache/` | 775 |

Clic derecho → Permisos → 775 (rwxrwxr-x).

---

## Paso 8: Cola de jobs (emails, follow-ups)

Los emails y follow-ups usan la cola. En Hostinger shared no hay worker permanente, pero puedes:

**Opción 1 – Cron (recomendado)**

En hPanel → **Cron Jobs**, añade:

```
* * * * * cd /home/u123456789/domains/tudominio.com/public_html/portfolio && php artisan schedule:run >> /dev/null 2>&1
```

Y en `app/Console/Kernel.php` (o `routes/console.php` en Laravel 11):

```php
Schedule::command('queue:work --stop-when-empty')->everyMinute();
```

**Opción 2 – Sin cola**

En `.env`:

```
QUEUE_CONNECTION=sync
```

Los emails se envían en la misma petición (más lento pero funciona sin cron).

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
- `APP_DEBUG=true` temporalmente para ver el error
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
