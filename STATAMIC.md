# Guia de arquitectura y comandos basicos de Statamic

## 1) Arquitectura del proyecto

Este proyecto usa Laravel 12 como base de aplicacion y Statamic 6 como CMS.

### Capas principales

1. Aplicacion (Laravel)
- `app/Http/Controllers/` controla endpoints personalizados.
- `app/Listeners/StoreSensitiveFormSubmission.php` intercepta eventos de formularios para persistencia cifrada.
- `app/Models/User.php` maneja autenticacion y acceso al CP.

2. Contenido (Statamic)
- `content/collections/` contiene entradas de colecciones (por ejemplo blog y paginas).
- `content/navigation/` define menus.
- `content/globals/` almacena configuraciones globales de sitio.
- `content/taxonomies/` administra terminos y clasificaciones.

3. Modelado editorial
- `resources/blueprints/` define estructura de campos para colecciones, formularios y secciones editables.
- `resources/forms/` define formularios de Statamic.

4. Presentacion
- `resources/views/` contiene plantillas Blade y vistas del CP personalizadas.
- `resources/css/` y `resources/js/` manejan frontend compilado con Vite.

5. Infraestructura y configuracion
- `config/statamic/*.php` contiene configuracion del CMS.
- `database/migrations/` define estructura SQL.
- `.env` controla credenciales y entorno.

## 2) Flujo de contenido

1. El editor entra al Control Panel (`/cp`).
2. Crea o edita contenido en colecciones (`content/collections/...`).
3. Statamic aplica blueprints para validar y estructurar campos.
4. Las vistas en `resources/views/` renderizan el contenido en frontend.
5. Si hay formulario de contacto, el listener guarda datos sensibles cifrados en DB.

## 3) Rutas y areas clave

- Sitio publico:
  - `/`
  - `/blog`

- Control Panel de Statamic:
  - `/cp`
  - `/cp/collections/pages`
  - `/cp/collections/blog`
  - `/cp/forms/contacto`

## 4) Comandos basicos de Statamic

> En este proyecto el ejecutable recomendado para Statamic es `php please ...`.

### Instalacion y setup

```bash
php please install
php please install:eloquent-driver
```

### Usuarios y acceso al CP

```bash
# crear usuario normal
php please make:user usuario@dominio.com

# crear super admin
php please make:user admin@dominio.com --super
```

Nota: si la tabla `users` exige `name`, usa modo interactivo para capturar nombre, email y password.

### Contenido y cache

```bash
# refrescar cache de contenido (stache)
php artisan statamic:stache:refresh

# limpiar cache general de Laravel
php artisan optimize:clear
```

### Utilidades de Statamic

```bash
# ver version/comandos disponibles
php please list

# habilitar Pro en .env (si aplica)
php please pro:enable

# configurar licencia en .env
php please license:set
```

## 5) Comandos de desarrollo diario

```bash
# backend local
php artisan serve

# frontend local
npm run dev

# build produccion
npm run build
```

## 6) Sprint abril 2026: Blog en base de datos (implementado)

En este sprint se movio la persistencia del blog a Eloquent Driver para asegurar que entradas, imagen destacada y configuracion editorial queden en SQL.

### Repositorios cambiados a `eloquent`

- `entries`
- `assets`
- `asset_containers`
- `collections`
- `collection_trees`
- `blueprints`
- `fieldsets`

Archivo de control:

- `config/statamic/eloquent-driver.php`

### Comando aplicado

```bash
php please install:eloquent-driver --repositories=entries,assets,asset_containers,collections,collection_trees,blueprints,fieldsets --import --no-interaction
php artisan migrate
php please stache:clear
php please stache:warm
php please eloquent:sync-assets
```

### Verificacion minima (BD)

```bash
php artisan tinker --execute="dump(DB::table('entries')->where('collection','blog')->select('id','collection','slug','data')->first()); dump(DB::table('assets_meta')->select('container','path')->first());"
```

Esperado:

- Registro en `entries` para `collection = blog`.
- En `data` debe aparecer `featured_image`.
- Registro relacionado en `assets_meta` con `container = assets`.

### Rollback rapido a archivos (si se requiere)

```bash
php please eloquent:export-entries
php please eloquent:export-assets
php please eloquent:export-collections
php please eloquent:export-blueprints
```

Luego actualizar `config/statamic/eloquent-driver.php` de regreso a `driver: file` para los repositorios necesarios y refrescar stache.

## 7) Checklist rapido para nuevos cambios en contenido

1. Confirmar blueprint en `resources/blueprints/...`.
2. Crear/editar entrada desde CP o en `content/collections/...`.
3. Verificar render en vistas de `resources/views/...`.
4. Refrescar stache si hay inconsistencia: `php artisan statamic:stache:refresh`.
5. Validar permisos de usuario CP (normal vs super admin).
