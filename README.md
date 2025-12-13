# Portfolio Personal - Ramiro Núñez Valverde

Portfolio profesional desarrollado con **Laravel 12**, **Tailwind CSS** y **Alpine.js**. Sistema completo con diseño moderno, responsivo, modo oscuro, gestión de proyectos destacados, habilidades por categorías y sección de contacto integrada.

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

## 🛠️ Tecnologías

### Backend

* **Laravel 12** - Framework PHP
* **Laravel Breeze** - Sistema de autenticación
* **MySQL** - Base de datos
* **Eloquent ORM** - ORM para base de datos

### Frontend

* **Tailwind CSS 3.1** - Framework CSS utility-first
* **Alpine.js 3.4** - Framework JavaScript ligero
* **Vite 7.0** - Build tool y dev server
* **Blade Templates** - Motor de plantillas de Laravel

## 📋 Requisitos

* PHP >= 8.2
* Composer
* Node.js >= 18
* MySQL 8.0+
* XAMPP (para desarrollo local)

## ⚙️ Instalación

1. **Clonar el repositorio:**
```bash
git clone https://github.com/leranuva/portfolio.git
cd portfolio
```

2. **Instalar dependencias:**
```bash
composer install
npm install
```

3. **Configurar entorno:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos en `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_ram
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders:**
```bash
php artisan migrate
php artisan db:seed
```

6. **Crear enlace simbólico para storage:**
```bash
php artisan storage:link
```

7. **Compilar assets:**
```bash
npm run build
```

8. **Iniciar servidor:**
```bash
php artisan serve
```

## 🌐 Acceso

* **Portfolio público:** http://localhost:8000/
* **Dashboard (con autenticación):** http://localhost:8000/dashboard

## 📁 Estructura del Proyecto

```
portfolio_ram/
├── app/
│   ├── Http/Controllers/
│   │   ├── PortfolioController.php
│   │   └── Auth/ (Controladores de autenticación)
│   └── Models/
│       ├── Project.php
│       ├── Skill.php
│       └── PortfolioConfig.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── PortfolioSeeder.php
├── resources/
│   ├── views/
│   │   └── portfolio/
│   │       └── index.blade.php
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── public/
│   └── favicon.svg
└── routes/
    ├── web.php
    └── auth.php
```

## 📝 Configuración del Portfolio

### Agregar Foto de Perfil

1. Coloca tu foto en: `storage/app/public/profile/`
2. Nombres sugeridos: `profile.jpg`, `foto.jpg`, `photo.jpg`
3. Ejecuta: `php update_profile_image.php`

### Personalizar Información

Puedes personalizar tu información desde la base de datos o usando Tinker:

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

## 🎨 Características del Diseño

* Diseño limpio y minimalista
* Totalmente responsivo (móvil, tablet, desktop)
* Dark mode con toggle funcional
* Animaciones suaves (fade-in, slide-up)
* Navegación intuitiva con scroll suave
* Favicon personalizado
* Gradientes modernos

## 📊 Base de Datos

### Tablas Principales

* `portfolio_configs` - Configuración del portfolio
* `projects` - Proyectos destacados
* `skills` - Habilidades por categorías

## 🔒 Seguridad

* Autenticación con Laravel Breeze
* Middleware de protección de rutas
* Validación de datos en servidor
* Protección CSRF
* Sanitización de inputs

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👨‍💻 Autor

**Ramiro Núñez Valverde** - Full-Stack Web Developer

* GitHub: [@leranuva](https://github.com/leranuva)
* Portfolio: [leranuva.com](https://leranuva.com)

## 🚀 Próximas Mejoras

* Panel de administración completo
* Sistema de blog
* Formulario de contacto funcional
* Integración con APIs externas
* Sistema de comentarios

---

_Desarrollado con ❤️ usando Laravel, Tailwind CSS y Alpine.js_
