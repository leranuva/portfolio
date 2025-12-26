# Portfolio Personal - Ramiro Núñez Valverde

<div align="center">

## 🚀 [Ver Demo en Vivo](https://ramironuva.com)

[![Portfolio](https://img.shields.io/badge/Portfolio-Live-brightgreen)](https://ramironuva.com)
[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.1-blue)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-green)](https://alpinejs.dev)

Portfolio profesional desarrollado con **Laravel 12**, **Tailwind CSS** y **Alpine.js**. Sistema completo con diseño moderno, responsivo, modo oscuro, gestión de proyectos destacados, habilidades por categorías y sección de contacto integrada.

</div>

---

## 📸 Visuales

> **Nota:** Agrega screenshots o GIFs animados aquí para mostrar el diseño en modo claro/oscuro y la responsividad.

### Modo Claro
<!-- Agregar screenshot: portfolio-light-mode.png -->

### Modo Oscuro
<!-- Agregar screenshot: portfolio-dark-mode.png -->

### Diseño Responsivo
<!-- Agregar GIF animado: portfolio-responsive.gif -->

### Características Visuales
- ✨ Animaciones suaves al hacer scroll
- 🌓 Toggle de modo oscuro/claro
- 📱 Diseño Mobile-First completamente responsivo
- 🎨 Gradientes modernos y efectos glassmorphism

---

## 🏗️ Arquitectura del Proyecto

### Diagrama de Flujo MVC

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENTE (Browser)                     │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              Routes (web.php, auth.php)                  │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│         Controllers (PortfolioController, etc.)          │
└────────────────────┬────────────────────────────────────┘
                     │
         ┌───────────┴───────────┐
         │                       │
         ▼                       ▼
┌─────────────────┐    ┌─────────────────┐
│    Models       │    │     Views        │
│  (Eloquent)     │    │   (Blade)        │
└────────┬────────┘    └─────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────┐
│              MySQL Database                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ portfolio_   │  │   projects   │  │    skills    │  │
│  │   configs    │  │              │  │              │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### Relación de Tablas

```
portfolio_configs (1)
    │
    ├── projects (N) ────► technologies (JSON)
    │
    └── skills (N) ────► category, proficiency
```

---

## 🚀 Características

### Portfolio Público

* ✅ Diseño moderno y responsivo (Mobile-First)
* ✅ Dark mode con persistencia en localStorage
* ✅ Sección Hero personalizable con foto de perfil
* ✅ Sección "Sobre mí" con valores y estilo
* ✅ Proyectos destacados con detalles completos:
  - Descripción del proyecto
  - Problema/Resolución
  - Rol y responsabilidades
  - Tecnologías utilizadas
  - Enlaces a demo y repositorio
  - Resultados y aprendizajes
* ✅ Habilidades agrupadas por categorías (Frontend, Backend, Database, DevOps, Design)
* ✅ Barras de progreso para nivel de competencia
* ✅ Sección de contacto con enlaces a GitHub, LinkedIn y Email
* ✅ Animaciones suaves al hacer scroll
* ✅ Navegación fluida con scroll suave
* ✅ Favicon personalizado con inicial "R"
* ✅ SEO optimizado con meta tags
* ✅ Open Graph tags para compartir en redes sociales

### Sistema de Autenticación

* ✅ Autenticación completa con Laravel Breeze
* ✅ Registro de usuarios
* ✅ Recuperación de contraseña
* ✅ Verificación de email
* ✅ Gestión de perfil de usuario

---

## 🛠️ Stack Tecnológico

### Backend

* **Laravel 12** - Framework PHP moderno y robusto
* **Laravel Breeze** - Sistema de autenticación
* **MySQL 8.0+** - Base de datos relacional
* **Eloquent ORM** - ORM elegante y potente
* **Composer** - Gestor de dependencias PHP

### Frontend

* **Tailwind CSS 3.1** - Framework CSS utility-first
* **Alpine.js 3.4** - Framework JavaScript ligero y reactivo
* **Vite 7.0** - Build tool ultra-rápido
* **Blade Templates** - Motor de plantillas de Laravel
* **PostCSS** - Procesador CSS

### Herramientas de Desarrollo

* **Git** - Control de versiones
* **NPM** - Gestor de paquetes Node.js
* **XAMPP** - Entorno de desarrollo local

---

## 📋 Requisitos

* PHP >= 8.2
* Composer >= 2.0
* Node.js >= 18
* MySQL 8.0+
* XAMPP (para desarrollo local) o entorno equivalente

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/leranuva/portfolio.git
cd portfolio
```

### 2. Instalar dependencias

```bash
# Dependencias PHP
composer install

# Dependencias Node.js
npm install
```

### 3. Configurar entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar base de datos

Edita el archivo `.env` con tus credenciales:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_ram
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar migraciones y seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (carga datos de ejemplo)
php artisan db:seed

# O ejecutar un seeder específico
php artisan db:seed --class=PortfolioSeeder
```

### 6. Configurar storage

```bash
# Crear enlace simbólico para archivos públicos
php artisan storage:link
```

### 7. Compilar assets

```bash
# Desarrollo (con hot reload)
npm run dev

# Producción (optimizado)
npm run build
```

### 8. Iniciar servidor

```bash
php artisan serve
```

El portfolio estará disponible en: **http://localhost:8000**

---

## 🌐 Acceso

### URLs Locales

* **Portfolio público:** http://localhost:8000/
* **Dashboard (con autenticación):** http://localhost:8000/dashboard
* **Login:** http://localhost:8000/login
* **Registro:** http://localhost:8000/register

### Demo en Vivo

* **Portfolio:** [ramironuva.com](https://ramironuva.com)

---

## 📁 Estructura del Proyecto

```
portfolio_ram/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── PortfolioController.php
│   │       ├── Auth/ (Controladores de autenticación)
│   │       └── Admin/ (Controladores de administración)
│   ├── Models/
│   │   ├── Project.php
│   │   ├── Skill.php
│   │   ├── PortfolioConfig.php
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── create_projects_table.php
│   │   ├── create_skills_table.php
│   │   └── create_portfolio_configs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── PortfolioSeeder.php
├── resources/
│   ├── views/
│   │   ├── portfolio/
│   │   │   └── index.blade.php
│   │   ├── auth/ (Vistas de autenticación)
│   │   └── components/ (Componentes reutilizables)
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── public/
│   ├── favicon.svg
│   └── storage/ (Enlace simbólico)
├── routes/
│   ├── web.php
│   └── auth.php
└── storage/
    └── app/public/ (Archivos subidos)
```

---

## 📝 Configuración del Portfolio

### Agregar Foto de Perfil

1. Coloca tu foto en: `storage/app/public/profile/`
2. Nombres sugeridos: `profile.jpg`, `foto.jpg`, `photo.jpg`
3. Ejecuta: `php update_profile_image.php`

### Personalizar Información

#### Opción 1: Usando Tinker (Rápido)

```php
php artisan tinker

$config = App\Models\PortfolioConfig::first();
$config->update([
    'name' => 'Tu Nombre',
    'role' => 'Tu Rol',
    'summary' => 'Tu descripción',
    'email' => 'tu-email@ejemplo.com',
    'github_url' => 'https://github.com/tu-usuario',
    'linkedin_url' => 'https://linkedin.com/in/tu-perfil',
]);
```

#### Opción 2: Panel de Administración (Próximamente)

Un panel de administración completo está en desarrollo para gestionar todo desde la interfaz web sin necesidad de usar la terminal.

---

## 🎨 Características del Diseño

* **Diseño limpio y minimalista** - Menos es más
* **Totalmente responsivo** - Mobile, tablet y desktop
* **Dark mode** - Toggle funcional con persistencia
* **Animaciones suaves** - Fade-in, slide-up, transiciones
* **Navegación intuitiva** - Scroll suave entre secciones
* **Favicon personalizado** - Inicial "R" con gradiente
* **Gradientes modernos** - Azul a púrpura
* **Glassmorphism** - Efectos de vidrio esmerilado

---

## 📊 Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `portfolio_configs` | Configuración general del portfolio (nombre, rol, descripción, enlaces) |
| `projects` | Proyectos destacados con detalles completos |
| `skills` | Habilidades agrupadas por categorías con nivel de competencia |
| `users` | Usuarios del sistema (autenticación) |

### Relaciones

- Un portfolio tiene muchos proyectos
- Un portfolio tiene muchas habilidades
- Los proyectos tienen tecnologías almacenadas en JSON

---

## 🔒 Seguridad

* ✅ Autenticación con Laravel Breeze
* ✅ Middleware de protección de rutas
* ✅ Validación de datos en servidor
* ✅ Protección CSRF en todos los formularios
* ✅ Sanitización de inputs
* ✅ Hash de contraseñas con bcrypt
* ✅ Tokens de sesión seguros

---

## 🚀 Próximas Mejoras

### En Desarrollo

- [ ] **Panel de Administración Completo** - CRUD desde interfaz web
- [ ] **Formulario de Contacto Funcional** - Envío de emails con validación
- [ ] **Sistema de Blog** - Artículos técnicos y proyectos
- [ ] **Traducciones (i18n)** - Español e Inglés
- [ ] **Sistema de Comentarios** - Para proyectos y blog

### Planificadas

- [ ] **Integración con APIs externas** - GitHub, LinkedIn, etc.
- [ ] **Sistema de notificaciones** - Email y push
- [ ] **Analytics integrado** - Google Analytics o Plausible
- [ ] **Sistema de búsqueda** - Para proyectos y habilidades
- [ ] **Exportación de datos** - PDF del portfolio

---

## ✅ Checklist de Pulido Final

### Performance

- [ ] Pasar test de Lighthouse (objetivo: 90+ en todas las métricas)
- [ ] Optimizar imágenes (convertir a WebP)
- [ ] Implementar lazy loading para imágenes
- [ ] Minificar CSS y JavaScript en producción
- [ ] Implementar caché de consultas de base de datos

### Accesibilidad

- [ ] Revisar contrastes en modo oscuro (WCAG AA mínimo)
- [ ] Agregar `aria-labels` a todos los botones
- [ ] Implementar navegación por teclado
- [ ] Agregar `alt` descriptivos a todas las imágenes
- [ ] Probar con lectores de pantalla

### SEO

- [x] Meta tags básicos implementados
- [x] Open Graph tags para redes sociales
- [ ] Sitemap.xml generado
- [ ] Robots.txt configurado
- [ ] Schema.org markup para datos estructurados

### Seguridad

- [x] Autenticación implementada
- [x] Protección CSRF
- [ ] Rate limiting en formularios
- [ ] Validación de archivos subidos
- [ ] Headers de seguridad (CSP, HSTS)

### Deployment

- [ ] Dockerfile creado
- [ ] Instrucciones de despliegue documentadas
- [ ] Variables de entorno documentadas
- [ ] Scripts de deployment automatizados
- [ ] Backup de base de datos configurado

---

## 🐳 Docker (Próximamente)

```dockerfile
# Dockerfile en desarrollo
FROM php:8.2-fpm
# ... configuración pendiente
```

---

## 📚 Documentación Adicional

- [Guía de Instalación Detallada](docs/SETUP.md)
- [Instrucciones para Agregar Foto de Perfil](docs/INSTRUCCIONES_FOTO.md)
- [Guía para Agregar Screenshots](docs/AGREGAR_SCREENSHOTS.md)
- [Actualizar Descripción en GitHub](docs/ACTUALIZAR_DESCRIPCION_GITHUB.md)
- [API Documentation](#) (Próximamente)
- [Contributing Guidelines](#) (Próximamente)

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Autor

**Ramiro Núñez Valverde** - Full-Stack Web Developer

* 🌐 Portfolio: [ramironuva.com](https://ramironuva.com)
* 💼 GitHub: [@leranuva](https://github.com/leranuva)
* 📧 Email: Disponible en el portfolio

---

## 🙏 Agradecimientos

* [Laravel](https://laravel.com) - Por el increíble framework
* [Tailwind CSS](https://tailwindcss.com) - Por el sistema de diseño
* [Alpine.js](https://alpinejs.dev) - Por la simplicidad y potencia

---

<div align="center">

_Desarrollado y diseñado por Ramiro Nunez usando Laravel, Tailwind CSS y Alpine.js_

Si te gusta este proyecto, ¡dale una estrella en GitHub!

</div>
