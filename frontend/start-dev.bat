@echo off
echo ========================================
echo   INICIANDO SERVIDOR FRONTEND
echo ========================================
echo.
echo El servidor se iniciara en: http://localhost:3000
echo.
echo MANTEN ESTA VENTANA ABIERTA
echo.
echo Presiona Ctrl+C para detener el servidor
echo.
echo ========================================
echo.

cd /d %~dp0
npm run dev

pause

