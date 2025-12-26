# Guía de Instalación y Configuración Completa

## Requisitos Previos

- PHP >= 8.1
- Composer
- Node.js >= 18
- MySQL/PostgreSQL
- XAMPP (para desarrollo local)

## Backend (Laravel)

1. **Instalar dependencias:**
```bash
cd backend
composer install
```

2. **Configurar .env:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos en .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_db
DB_USERNAME=root
DB_PASSWORD=
```

4. **Ejecutar migraciones:**
```bash
php artisan migrate
```

5. **Crear usuario administrador:**
```bash
php artisan tinker
```
Luego ejecutar:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password')
]);
```

6. **Iniciar servidor:**
```bash
php artisan serve
```

El backend estará disponible en: `http://localhost:8000`

## Frontend (React)

1. **Instalar dependencias:**
```bash
cd frontend
npm install
```

2. **Iniciar servidor de desarrollo:**
```bash
npm run dev
```

El frontend estará disponible en: `http://localhost:3000`

## Estructura del Proyecto

```
portfolio/
├── backend/              # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   └── Models/
│   ├── database/migrations/
│   └── routes/api.php
├── frontend/            # React SPA
│   ├── src/
│   │   ├── components/
│   │   │   ├── Public/  # Componentes del portfolio público
│   │   │   └── Admin/   # Componentes del dashboard
│   │   ├── services/    # Servicios API
│   │   └── context/     # Context API
│   └── package.json
└── README.md
```

## Acceso

- **Portfolio público:** http://localhost:3000
- **Dashboard admin:** http://localhost:3000/admin/login
- **API Backend:** http://localhost:8000/api

## Credenciales por defecto

- **Email:** admin@example.com
- **Password:** password

**¡IMPORTANTE!** Cambia estas credenciales en producción.

## Funcionalidades

### Portfolio Público
- ✅ Diseño moderno y responsivo
- ✅ Dark mode
- ✅ Sección Hero con información personal
- ✅ Proyectos destacados con detalles completos
- ✅ Habilidades agrupadas por categorías
- ✅ Formulario de contacto que guarda en base de datos
- ✅ Animaciones suaves al hacer scroll

### Dashboard Administrativo
- ✅ Autenticación con Laravel Sanctum
- ✅ Panel de estadísticas
- ✅ CRUD completo de Clientes
- ✅ CRUD completo de Proyectos
- ✅ CRUD completo de Habilidades
- ✅ Configuración del portfolio (nombre, rol, descripción, enlaces)

## API Endpoints

### Públicos
- `GET /api/portfolio/public` - Obtener datos públicos del portfolio

### Autenticados (requieren token)
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/user` - Obtener usuario actual

### Clientes
- `GET /api/clients` - Listar todos
- `POST /api/clients` - Crear
- `GET /api/clients/{id}` - Obtener uno
- `PUT /api/clients/{id}` - Actualizar
- `DELETE /api/clients/{id}` - Eliminar

### Proyectos
- `GET /api/projects` - Listar todos
- `POST /api/projects` - Crear
- `GET /api/projects/{id}` - Obtener uno
- `PUT /api/projects/{id}` - Actualizar
- `DELETE /api/projects/{id}` - Eliminar

### Habilidades
- `GET /api/skills` - Listar todas
- `POST /api/skills` - Crear
- `GET /api/skills/{id}` - Obtener una
- `PUT /api/skills/{id}` - Actualizar
- `DELETE /api/skills/{id}` - Eliminar

### Configuración
- `GET /api/portfolio/settings` - Obtener configuración
- `PUT /api/portfolio/settings` - Actualizar configuración

## Próximos Pasos

1. Configurar base de datos MySQL
2. Ejecutar migraciones
3. Crear usuario administrador
4. Iniciar backend y frontend
5. Acceder al dashboard y comenzar a gestionar contenido
6. Personalizar la información en Configuración

