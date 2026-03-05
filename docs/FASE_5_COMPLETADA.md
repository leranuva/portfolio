# Fase 5 Completada - Modo Oscuro/Claro

## Resumen

Se ha completado la implementación del modo oscuro/claro según la guía: variable CSS `--theme`, clase `dark` en `<html>`, toggle con Alpine.js, persistencia en localStorage y respeto a `prefers-color-scheme` del sistema.

## Implementación

### 5.1 Variable CSS y clase dark
- **Variable `--theme`**: Se actualiza dinámicamente (`light` / `dark`) en el elemento `<html>` vía Alpine
- **Clase `dark`**: Aplicada en `<html>` con `x-bind:class` para activar el dark mode de Tailwind
- **Paleta**: Light (#fafaf9), Dark (#0c0c0f), acentos indigo

### 5.2 Toggle con Alpine.js
- **Store `theme`**: `Alpine.store('theme')` con propiedad `dark` y método `toggle()`
- **Persistencia**: `localStorage.setItem('portfolio-dark', ...)` al cambiar
- **Ubicación**: Botón en navegación desktop y en menú móvil (con etiqueta "Modo oscuro" / "Modo claro")

### 5.3 prefers-color-scheme
- **Primera visita**: Si no hay preferencia guardada, se usa `window.matchMedia('(prefers-color-scheme: dark)').matches`
- **Visitas posteriores**: Se usa la preferencia guardada en localStorage

### 5.4 Evitar flash de tema incorrecto
- **Script en `<head>`**: Se ejecuta antes de que Alpine cargue y aplica la clase `dark` en `<html>` según localStorage o `prefers-color-scheme`
- Evita el parpadeo de tema incorrecto durante la carga inicial

## Archivos modificados

| Archivo | Cambios |
|---------|---------|
| `resources/views/layouts/portfolio.blade.php` | Script anti-flash en head, `x-bind:style` para `--theme` |
| `resources/views/portfolio/components/nav.blade.php` | Toggle dark mode en menú móvil |
| `resources/js/app.js` | Store theme con lógica `prefers-color-scheme` |
| `resources/css/portfolio.css` | Variable `--theme` en @theme |

## Flujo de funcionamiento

1. **Carga**: Script en head lee localStorage o `prefers-color-scheme` y aplica clase en `<html>`
2. **Alpine**: Store se inicializa con la misma lógica
3. **Toggle**: Al hacer clic, se invierte `dark`, se guarda en localStorage y se actualiza la clase
4. **Persistencia**: La preferencia se mantiene entre sesiones

## Cómo probar

```bash
php artisan serve
# Visitar http://localhost:8000
```

- **Desktop**: Clic en icono sol/luna en la barra de navegación
- **Móvil**: Abrir menú hamburguesa → "Modo oscuro" / "Modo claro"
- **Primera visita**: Borrar localStorage y recargar → debe respetar preferencia del sistema
- **Sin flash**: Recargar con tema oscuro activo → no debe parpadear a claro

## Próximo paso: Fase 6

Animaciones y polish: fade-in al scroll, hover en cards, responsive, performance.
