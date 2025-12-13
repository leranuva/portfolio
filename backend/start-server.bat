@echo off
echo ========================================
echo   INICIANDO SERVIDOR BACKEND LARAVEL
echo ========================================
echo.
echo El servidor se iniciara en: http://localhost:8000
echo.
echo MANTEN ESTA VENTANA ABIERTA
echo.
echo Presiona Ctrl+C para detener el servidor
echo.
echo ========================================
echo.

cd /d %~dp0
php artisan serve

pause

