# Script para preparar el proyecto para subir a Hostinger via File Manager
# Ejecutar desde la raíz del proyecto: .\prepare-hostinger.ps1

$ErrorActionPreference = "Stop"
$deployDir = "hostinger-deploy"

Write-Host "Preparando proyecto para Hostinger..." -ForegroundColor Cyan

# 1. Limpiar carpeta de despliegue anterior
if (Test-Path $deployDir) {
    Remove-Item -Recurse -Force $deployDir
}
New-Item -ItemType Directory -Path $deployDir | Out-Null

# 2. Composer install (producción)
Write-Host "Ejecutando composer install --no-dev..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction
if ($LASTEXITCODE -ne 0) { throw "Composer falló" }

# 3. NPM build
Write-Host "Ejecutando npm run build..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) { throw "NPM build falló" }

# 4. Copiar archivos
$copyItems = @(
    @{Src="app"; Dst="app"},
    @{Src="bootstrap"; Dst="bootstrap"},
    @{Src="config"; Dst="config"},
    @{Src="database"; Dst="database"},
    @{Src="resources"; Dst="resources"},
    @{Src="routes"; Dst="routes"},
    @{Src="storage"; Dst="storage"},
    @{Src="vendor"; Dst="vendor"},
    @{Src="public"; Dst="public"},
    @{Src="artisan"; Dst="artisan"},
    @{Src="composer.json"; Dst="composer.json"},
    @{Src="composer.lock"; Dst="composer.lock"}
)

foreach ($item in $copyItems) {
    $src = $item.Src
    $dst = Join-Path $deployDir $item.Dst
    if (Test-Path $src) {
        Write-Host "  Copiando $src..." -ForegroundColor Gray
        if (Test-Path $src -PathType Container) {
            Copy-Item -Path $src -Destination $dst -Recurse -Force
        } else {
            New-Item -ItemType Directory -Path (Split-Path $dst) -Force | Out-Null
            Copy-Item -Path $src -Destination $dst -Force
        }
    }
}

# 5. Crear carpetas vacías necesarias
$emptyDirs = @(
    "storage/app/public",
    "storage/framework/cache/data",
    "storage/framework/sessions",
    "storage/framework/views",
    "storage/logs",
    "bootstrap/cache"
)
foreach ($dir in $emptyDirs) {
    $path = Join-Path $deployDir $dir
    if (-not (Test-Path $path)) {
        New-Item -ItemType Directory -Path $path -Force | Out-Null
    }
}

# 6. Copiar .env.hostinger.example como .env
Copy-Item ".env.hostinger.example" (Join-Path $deployDir ".env.example")
Write-Host "  .env.example creado (renombrar a .env en el servidor)" -ForegroundColor Gray

# 7. Crear .htaccess en raíz si se usa estructura public_html raíz
$htaccessRoot = @"
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
"@
# No lo creamos por defecto - la guía explica las opciones

# 8. Limpiar archivos innecesarios dentro de deploy
$cleanPaths = @(
    (Join-Path $deployDir "storage/logs/*"),
    (Join-Path $deployDir "storage/framework/cache/data/*"),
    (Join-Path $deployDir "storage/framework/sessions/*"),
    (Join-Path $deployDir "storage/framework/views/*"),
    (Join-Path $deployDir "bootstrap/cache/*")
)
foreach ($pattern in $cleanPaths) {
    if (Test-Path (Split-Path $pattern)) {
        Get-ChildItem $pattern -ErrorAction SilentlyContinue | Remove-Item -Force -Recurse -ErrorAction SilentlyContinue
    }
}

Write-Host ""
Write-Host "Listo. Carpeta '$deployDir' preparada." -ForegroundColor Green
Write-Host "Siguiente: ver docs/DEPLOY_HOSTINGER.md" -ForegroundColor Cyan
