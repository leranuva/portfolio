# Reglas del Proyecto - Portfolio Ramiro

## Principios Fundamentales de Desarrollo

Las siguientes reglas son **obligatorias** y deben aplicarse en todo el código del proyecto. No existen excepciones.

---

### 1. Prohibición de Código Embebido e Inline

**Bajo ningún concepto** se permite desarrollar con:

- **Código embebido**: Estilos, scripts o lógica mezclados directamente en el markup (HTML/Blade).
- **Código inline**: Atributos `style=""`, `onclick=""`, handlers inline, o expresiones JavaScript/CSS dentro de las vistas.
- **Strings hardcodeados** en el código cuando deban provenir de configuración, traducciones o base de datos.

**Ejemplos prohibidos:**
```html
<!-- ❌ PROHIBIDO -->
<div style="color: red; font-size: 16px;">Texto</div>
<button onclick="submitForm()">Enviar</button>
```

**Enfoque correcto:**
- Estilos en archivos CSS/SCSS o clases de Tailwind.
- Eventos y lógica en componentes Livewire, Alpine.js (con `x-data` declarativo) o archivos JS modulares.
- Contenido dinámico desde controladores, componentes o base de datos.

---

### 2. Prohibición de Duplicación de Código (DRY)

**No se permite** duplicar código. Cada pieza de lógica, estilo o estructura debe existir en **un único lugar**.

- **Lógica**: Extraer a métodos, traits, servicios o clases reutilizables.
- **Vistas**: Usar componentes Blade, partials o layouts.
- **Estilos**: Clases compartidas, componentes de diseño, variables CSS.
- **Validación y reglas**: Centralizar en Form Requests, Policies o clases dedicadas.

Si el mismo código aparece en más de un archivo, debe refactorizarse en un módulo o componente común.

---

### 3. Proyecto Completamente Modular

El proyecto debe ser **totalmente modular**:

- **Componentes**: Cada pieza de UI reutilizable debe ser un componente independiente (Blade, Livewire o ambos).
- **Servicios**: Lógica de negocio en clases de servicio, no en controladores.
- **Módulos por dominio**: Organizar por features (Auth, Portfolio, Contact, etc.) cuando el tamaño lo justifique.
- **Dependencias explícitas**: Inyección de dependencias, sin acoplamiento directo a implementaciones concretas.
- **Configuración externa**: Parámetros, URLs y constantes en archivos de configuración o variables de entorno, no en el código.

**Estructura objetivo:**
```
app/
├── Http/Controllers/     # Delgados, delegan en servicios
├── Services/            # Lógica de negocio
├── View/Components/     # Componentes Blade reutilizables
├── Livewire/           # Componentes interactivos
resources/views/
├── components/         # Componentes de UI
├── layouts/            # Layouts base
└── ...
```

---

## Resumen

| Regla | Descripción |
|-------|-------------|
| **Sin código embebido/inline** | Estilos, scripts y lógica en archivos separados y componentes declarativos. |
| **Sin duplicación (DRY)** | Una sola fuente de verdad para cada pieza de código. |
| **Modular** | Componentes, servicios y módulos independientes y reutilizables. |

---

*Estas reglas aplican a todo el equipo y a cualquier contribución al proyecto.*
