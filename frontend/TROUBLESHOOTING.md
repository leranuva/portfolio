# Solución de Problemas - Página en Blanco

## Pasos para diagnosticar:

1. **Abre la consola del navegador (F12)**
   - Ve a la pestaña "Console"
   - Busca errores en rojo
   - Copia cualquier error que veas

2. **Verifica que ambos servidores estén corriendo:**
   - Backend: http://localhost:8000/api/portfolio/public (debe devolver JSON)
   - Frontend: http://localhost:3000 (debe mostrar el portfolio)

3. **Verifica la pestaña "Network" en las herramientas de desarrollador:**
   - Busca la petición a `/api/portfolio/public`
   - Verifica que el status sea 200 (OK)
   - Si hay error CORS, verás un error en rojo

4. **Reinicia ambos servidores:**
   ```bash
   # Terminal 1 - Backend
   cd backend
   php artisan serve
   
   # Terminal 2 - Frontend
   cd frontend
   npm run dev
   ```

5. **Limpia la caché del navegador:**
   - Presiona Ctrl+Shift+R (o Cmd+Shift+R en Mac)
   - O ve a Configuración > Limpiar datos de navegación

## Errores comunes:

### Error: "Failed to fetch" o "Network Error"
- **Causa**: El backend no está corriendo o no es accesible
- **Solución**: Verifica que `php artisan serve` esté ejecutándose en el puerto 8000

### Error: CORS
- **Causa**: Problema de configuración CORS
- **Solución**: Ya está configurado en Laravel, pero verifica que el backend esté en el puerto 8000

### Página completamente en blanco
- **Causa**: Error de JavaScript que bloquea el renderizado
- **Solución**: 
  1. Abre la consola (F12)
  2. Busca errores
  3. Verifica que todos los archivos se estén cargando correctamente

### Solo muestra "Cargando..."
- **Causa**: La API no responde o tarda mucho
- **Solución**: 
  1. Verifica que el backend esté corriendo
  2. Prueba acceder directamente a: http://localhost:8000/api/portfolio/public
  3. Si no responde, reinicia el servidor backend

