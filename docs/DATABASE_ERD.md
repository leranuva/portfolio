# Diagrama Entidad-Relación (ERD) - Portfolio

## 📊 Diagrama Visual

```
┌─────────────────────────────────────────────────────────────┐
│                    portfolio_configs                        │
├─────────────────────────────────────────────────────────────┤
│ id (PK)              BIGINT UNSIGNED                        │
│ name                 VARCHAR(255)                            │
│ role                 VARCHAR(255)                            │
│ summary              TEXT                                    │
│ values_style         TEXT (nullable)                        │
│ email                VARCHAR(255)                            │
│ linkedin_url         VARCHAR(255) (nullable)                │
│ github_url           VARCHAR(255) (nullable)                │
│ profile_image        VARCHAR(255) (nullable)                │
│ dark_mode_enabled    BOOLEAN (default: true)                │
│ created_at           TIMESTAMP                               │
│ updated_at           TIMESTAMP                               │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ 1
                            │
                            │
        ┌───────────────────┴───────────────────┐
        │                                       │
        │ N                                    │ N
        │                                       │
        ▼                                       ▼
┌──────────────────────┐          ┌──────────────────────┐
│      projects        │          │       skills         │
├──────────────────────┤          ├──────────────────────┤
│ id (PK)              │          │ id (PK)              │
│ name                 │          │ name                 │
│ description          │          │ category             │
│ problem_solution     │          │ proficiency          │
│ role                 │          │ order                │
│ technologies (JSON)  │◄─────────┤ created_at           │
│ demo_url             │          │ updated_at           │
│ repository_url       │          └──────────────────────┘
│ live_url             │
│ results_learnings    │
│ image                │
│ order                │
│ featured             │
│ created_at           │
│ updated_at           │
└──────────────────────┘
```

## 🔗 Relaciones

### portfolio_configs (1) → projects (N)
- **Tipo:** Uno a Muchos (implícito)
- **Descripción:** Un portfolio puede tener múltiples proyectos destacados
- **Implementación:** Los proyectos se relacionan mediante el campo `featured = true`

### portfolio_configs (1) → skills (N)
- **Tipo:** Uno a Muchos (implícito)
- **Descripción:** Un portfolio puede tener múltiples habilidades
- **Implementación:** Las habilidades se agrupan por categoría

## 📋 Estructura de Campos JSON

### projects.technologies
Campo JSON que almacena un array de tecnologías utilizadas en el proyecto.

**Ejemplo:**
```json
[
  "Laravel 12",
  "Vue.js",
  "MySQL",
  "Tailwind CSS",
  "Alpine.js"
]
```

**Uso en Eloquent:**
```php
// En el modelo Project
protected $casts = [
    'technologies' => 'array',
];

// Uso
$project->technologies = ['Laravel', 'Vue.js'];
$project->save();
```

## 🗂️ Categorías de Habilidades

Las habilidades se agrupan por la columna `category`:

- `frontend` - Tecnologías del lado del cliente
- `backend` - Tecnologías del lado del servidor
- `database` - Sistemas de gestión de bases de datos
- `devops` - Herramientas de desarrollo y despliegue
- `design` - Herramientas de diseño y UX/UI

## 📊 Índices Recomendados

```sql
-- Para búsquedas rápidas de proyectos destacados
CREATE INDEX idx_projects_featured_order ON projects(featured, `order`);

-- Para agrupación de habilidades
CREATE INDEX idx_skills_category_order ON skills(category, `order`);
```

## 🔍 Consultas Comunes

### Obtener proyectos destacados ordenados
```php
Project::where('featured', true)
    ->orderBy('order')
    ->get();
```

### Obtener habilidades agrupadas por categoría
```php
Skill::orderBy('category')
    ->orderBy('order')
    ->get()
    ->groupBy('category');
```

### Buscar proyectos por tecnología
```php
Project::whereJsonContains('technologies', 'Laravel')
    ->get();
```

## 📝 Notas de Diseño

1. **JSON para tecnologías:** Se usa JSON en lugar de una tabla relacional para mantener la flexibilidad y simplicidad. Si en el futuro se necesita más estructura, se puede migrar a una tabla `technologies` con relaciones many-to-many.

2. **Ordenamiento:** Los campos `order` permiten controlar el orden de visualización sin depender de IDs o fechas.

3. **Featured Projects:** El campo booleano `featured` permite destacar proyectos específicos sin necesidad de una relación explícita.

4. **Imágenes:** Las imágenes se almacenan en `storage/app/public/` y se acceden mediante enlaces simbólicos a `public/storage/`.

