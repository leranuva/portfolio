# Cómo Agregar Vista Previa de Proyectos

## 📸 Vista Previa del Sitio

Cada proyecto puede tener una imagen de vista previa que se muestra prominentemente en la tarjeta del proyecto.

## 📁 Ubicación de Imágenes

Coloca las imágenes de vista previa en:
```
storage/app/public/projects/
```

## 🖼️ Formatos Soportados

- `.jpg` o `.jpeg`
- `.png`
- `.gif`
- `.webp` (recomendado para mejor rendimiento)

## 📏 Tamaño Recomendado

- **Dimensiones:** 1200x800 píxeles o más
- **Aspecto:** 3:2 o 16:9 funciona mejor
- **Peso:** Menos de 500KB (optimiza antes de subir)

## 🛠️ Cómo Agregar una Vista Previa

### Opción 1: Usando Tinker

```php
php artisan tinker

$project = App\Models\Project::where('name', 'LIKE', '%Nombre del Proyecto%')->first();
$project->update([
    'image' => 'projects/nombre-proyecto-preview.jpg'
]);
```

### Opción 2: Desde la Base de Datos

1. Sube la imagen a `storage/app/public/projects/nombre-proyecto.jpg`
2. Actualiza el campo `image` en la tabla `projects`:
   ```sql
   UPDATE projects 
   SET image = 'projects/nombre-proyecto.jpg' 
   WHERE name = 'Nombre del Proyecto';
   ```

## 📸 Cómo Capturar una Vista Previa

### Herramientas Recomendadas

1. **Screenshot Tools:**
   - Lightshot (Windows/Mac/Linux)
   - ShareX (Windows)
   - Screenshot Tool nativo del SO

2. **Herramientas Online:**
   - [Screenshot.guru](https://screenshot.guru) - Captura sitios web completos
   - [BrowserStack](https://www.browserstack.com/screenshots) - Múltiples dispositivos
   - [Responsively App](https://responsively.app) - Vista responsive

3. **Extensiones de Navegador:**
   - Full Page Screen Capture (Chrome)
   - Awesome Screenshot (Chrome/Firefox)
   - Nimbus Screenshot (Chrome/Firefox)

### Pasos para Capturar

1. Abre el sitio web del proyecto
2. Asegúrate de que esté en el tamaño de escritorio (1920x1080 o similar)
3. Captura la sección principal/hero del sitio
4. Recorta si es necesario (mantén el aspecto 3:2 o 16:9)
5. Optimiza la imagen (usa TinyPNG o ImageOptim)
6. Guarda como: `nombre-proyecto-preview.jpg`

## 🎨 Mejores Prácticas

### Qué Capturar

✅ **Captura:**
- La sección Hero/Inicio del sitio
- Una vista que muestre el diseño principal
- Algo representativo de la funcionalidad

❌ **Evita:**
- Capturas de páginas de login/registro
- Páginas vacías o de error
- Imágenes con información sensible

### Optimización

1. **Comprime la imagen:**
   - Usa [TinyPNG](https://tinypng.com) o [Squoosh](https://squoosh.app)
   - Convierte a WebP si es posible

2. **Ajusta el tamaño:**
   - Máximo 1200px de ancho
   - Mantén buena calidad pero reduce el peso

3. **Nombres descriptivos:**
   - `flat-rate-imports-preview.jpg`
   - `ecommerce-platform-dashboard.jpg`
   - `task-manager-homepage.jpg`

## 🔗 Ejemplo Completo

```php
// 1. Subir imagen a storage/app/public/projects/
// 2. Actualizar proyecto

php artisan tinker

$project = App\Models\Project::where('name', 'Flat Rate Imports')->first();
$project->update([
    'image' => 'projects/flat-rate-imports-preview.jpg'
]);
```

## ✨ Características de la Vista Previa

La vista previa incluye:

- **Hover Effect:** Zoom suave al pasar el mouse
- **Overlay Informativo:** Muestra información al hacer hover
- **Badge "Live":** Indica si el sitio está en vivo
- **Clickable:** La imagen es clickeable y lleva al sitio
- **Responsive:** Se adapta a diferentes tamaños de pantalla
- **Lazy Loading:** Carga diferida para mejor rendimiento

## 🎯 Si No Tienes Imagen

Si no tienes una imagen de vista previa, se mostrará un placeholder atractivo con:
- Gradiente moderno
- Icono de navegador
- Nombre del proyecto
- Badge "Live" si tiene URL

## 💡 Tips Pro

1. **Usa herramientas de diseño:** Crea mockups profesionales con Figma o Canva
2. **Captura múltiples vistas:** Hero, Dashboard, Features
3. **Mantén consistencia:** Mismo estilo para todos los proyectos
4. **Actualiza regularmente:** Si el sitio cambia, actualiza la preview

