# 📸 Cómo Agregar tu Foto de Perfil

## ✅ Tu nombre ya está actualizado: **Ramiro Núñez Valverde**

## 📁 Dónde poner tu foto:

### Opción 1: Método Rápido (Recomendado)

1. **Coloca tu foto** en esta carpeta:
   ```
   storage/app/public/profile/
   ```

2. **Nombres sugeridos** para tu foto:
   - `profile.jpg`
   - `foto.jpg`
   - `photo.jpg`
   - `avatar.jpg`

3. **Formatos soportados:**
   - `.jpg` o `.jpeg`
   - `.png`
   - `.gif`

4. **Ejecuta el script:**
   ```bash
   php update_profile_image.php
   ```

### Opción 2: Actualización Manual en la Base de Datos

Si prefieres hacerlo manualmente:

1. Coloca tu foto en: `storage/app/public/profile/tu-foto.jpg`

2. Actualiza la base de datos:
   ```php
   // Desde tinker: php artisan tinker
   $config = App\Models\PortfolioConfig::first();
   $config->update(['profile_image' => 'profile/tu-foto.jpg']);
   ```

## 🖼️ Tamaño Recomendado

- **Tamaño ideal:** 400x400 píxeles o más
- **Formato:** Cuadrado funciona mejor (se mostrará como círculo)
- **Peso:** Menos de 2MB para mejor rendimiento

## ✅ Verificar

Después de agregar la foto, visita:
```
http://localhost:8000/
```

Tu foto debería aparecer en la sección de inicio (Hero/Landing).

## 🔗 Ruta de la Imagen

La imagen se almacena en:
- **Físicamente:** `storage/app/public/profile/`
- **Accesible desde web:** `public/storage/profile/` (enlace simbólico)
- **URL:** `http://localhost:8000/storage/profile/tu-foto.jpg`

## 📝 Nota Importante

Si ya ejecutaste `php artisan storage:link` (ya lo hicimos), el enlace simbólico está creado y las imágenes serán accesibles desde el navegador.


