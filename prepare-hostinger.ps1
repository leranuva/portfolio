# Script para preparar el proyecto para subir a Hostinger via File Manager
# Ejecutar desde la raíz del proyecto: .\prepare-hostinger.ps1

$ErrorActionPreference = "Stop"
Set-Location -LiteralPath $PSScriptRoot
$deployDir = Join-Path $PSScriptRoot "hostinger-deploy"

Write-Host "Preparando proyecto para Hostinger..." -ForegroundColor Cyan

# 1. Evitar mezclar un despliegue nuevo con archivos antiguos
if (Test-Path $deployDir) {
    throw "Mueve o elimina el paquete anterior antes de continuar: $deployDir"
}

# 2. Composer install (producción)
Write-Host "Ejecutando composer install --no-dev..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction
if ($LASTEXITCODE -ne 0) { throw "Composer falló" }

# 3. NPM build
Write-Host "Instalando dependencias npm del lockfile..." -ForegroundColor Yellow
npm ci
if ($LASTEXITCODE -ne 0) { throw "npm ci falló" }
Write-Host "Ejecutando npm run build..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) { throw "NPM build falló" }

# 4. Copiar archivos
New-Item -ItemType Directory -Path $deployDir | Out-Null
$copyItems = @(
    @{Src="app"; Dst="app"},
    @{Src="bootstrap/app.php"; Dst="bootstrap/app.php"},
    @{Src="bootstrap/providers.php"; Dst="bootstrap/providers.php"},
    @{Src="config"; Dst="config"},
    @{Src="database/migrations"; Dst="database/migrations"},
    @{Src="database/seeders"; Dst="database/seeders"},
    @{Src="resources"; Dst="resources"},
    @{Src="routes"; Dst="routes"},
    @{Src="vendor"; Dst="vendor"},
    @{Src="artisan"; Dst="artisan"},
    @{Src="composer.json"; Dst="composer.json"},
    @{Src="composer.lock"; Dst="composer.lock"}
)

foreach ($item in $copyItems) {
    $src = $item.Src
    $dst = Join-Path $deployDir $item.Dst
    if (Test-Path $src) {
        Write-Host "  Copiando $src..." -ForegroundColor Gray
        New-Item -ItemType Directory -Path (Split-Path $dst) -Force | Out-Null
        if (Test-Path $src -PathType Container) {
            Copy-Item -Path $src -Destination $dst -Recurse -Force
        } else {
            Copy-Item -Path $src -Destination $dst -Force
        }
    }
}

$publicDeploy = Join-Path $deployDir "public"
New-Item -ItemType Directory -Path $publicDeploy | Out-Null
Get-ChildItem -LiteralPath "public" -Force |
    Where-Object { $_.Name -notin @('hot', 'storage') -and $_.Name -notlike '.env*' } |
    ForEach-Object { Copy-Item -LiteralPath $_.FullName -Destination $publicDeploy -Recurse -Force }

# 5. Crear carpetas vacías necesarias
$emptyDirs = @(
    "storage/app/private",
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

# 6. Copiar solo la plantilla de configuración
Copy-Item ".env.hostinger.example" (Join-Path $deployDir ".env.example")
Write-Host "  .env.example creado (renombrar a .env en el servidor)" -ForegroundColor Gray

Write-Host ""
Write-Host "Listo. Carpeta '$deployDir' preparada." -ForegroundColor Green
Write-Host "Siguiente: ver docs/DEPLOY_HOSTINGER.md" -ForegroundColor Cyan
