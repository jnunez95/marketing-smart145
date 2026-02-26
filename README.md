# CRM - Marketing Smart

CRM construido con Laravel 12 y Filament 4 para gestión de clientes, grupos, plantillas de email y campañas con tracking.

## Requisitos

- PHP 8.2+
- Composer
- Base de datos (MySQL, PostgreSQL o SQLite)
- Node.js y NPM (para assets)

## Instalación

1. Clonar y configurar entorno:
```bash
cp .env.example .env
php artisan key:generate
```

2. Configurar base de datos en `.env` (DB_CONNECTION, DB_DATABASE, etc.).

3. Migraciones:
```bash
php artisan migrate
```

4. Crear usuario admin para Filament:
```bash
php artisan make:filament-user
```

5. (Opcional) Configurar roles y permisos con Shield:
```bash
php artisan shield:setup
php artisan shield:generate --all
```

6. Para envío de campañas en segundo plano, ejecutar el worker de colas:
```bash
php artisan queue:work
```

7. Para campañas programadas, el scheduler debe ejecutarse cada minuto (cron o en desarrollo):
```bash
php artisan schedule:work
```

8. Panel de administración: `php artisan serve` y visitar `/admin`.

## Estructura principal

- **Stations**: CRUD con la estructura de tabla definida; importación/exportación CSV/Excel; asignación a grupos; filtros por grupo y país.
- **Grupos**: Grupos de stations con nombre, descripción y color; una station pertenece a un solo grupo.
- **Templates de email**: Editor WYSIWYG; variables `{agency_name}`, `{email}`, etc.; duplicar plantilla.
- **Campañas**: Crear campaña (template, grupo o todos, programación); botón "Enviar ahora"; tracking de aperturas y clicks.

## Tracking

- Pixel de apertura: `/track/open/{token}` (incluido en el HTML del email).
- Clicks: `/track/click/{token}?url=...` (enlazar URLs en el template usando esta ruta con el token del destinatario).

## Permisos (Shield)

Tras `shield:setup` y `shield:generate --all`, gestionar roles en **Admin > Shield > Roles**. El modelo User usa el trait `HasRoles` de Spatie Permission.
