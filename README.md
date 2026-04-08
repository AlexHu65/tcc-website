# TCC Website (Laravel + Statamic)

Sitio web de marketing/blog administrado con Statamic sobre Laravel, con Home 100% editable desde el Control Panel y formulario de contacto con almacenamiento seguro en base de datos cifrada.

## Stack

- PHP 8.2+
- Laravel 12
- Statamic 6
- MySQL
- Vite + Tailwind CSS

## Funcionalidades principales

- Home administrable desde Statamic mediante `Replicator` (secciones dinámicas).
- Blog y páginas administrables desde el CP.
- Formulario de contacto en Home usando Statamic Forms.
- Datos sensibles de contacto cifrados en base de datos (no se almacenan en plano).
- Vista interna en CP para consultar submissions sensibles.

## Estructura clave del proyecto

- `content/collections/pages/home.md`  
  Contenido del Home con secciones dinámicas (`sections`).

- `resources/blueprints/collections/pages/pages.yaml`  
  Blueprint de páginas, incluye `Replicator` para construir el Home.

- `resources/forms/contacto.yaml`  
  Configuración del formulario Statamic (`store: false`).

- `resources/blueprints/forms/contacto.yaml`  
  Blueprint de campos válidos del formulario (`nombre`, `email`, `telefono`, `mensaje`).

- `resources/views/pages/home.blade.php`  
  Render dinámico de bloques del Home y formulario en 2 pasos.

- `app/Listeners/StoreSensitiveFormSubmission.php`  
  Listener que intercepta `FormSubmitted` y guarda payload cifrado en DB.

- `database/migrations/*_create_sensitive_form_submissions_table.php`  
  Tabla para submissions sensibles cifrados.

- `app/Http/Controllers/CpSensitiveSubmissionController.php`  
  Listado/detalle de submissions cifrados en rutas protegidas de CP.

- `resources/views/cp/sensitive-submissions/*.blade.php`  
  Vistas para consultar submissions sensibles desde panel.

## Instalación local

1. Instalar dependencias:

```bash
composer install
npm install
```

2. Configurar entorno:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configurar base de datos en `.env` (`DB_*`).

4. Ejecutar migraciones:

```bash
php artisan migrate
```

5. Levantar proyecto:

```bash
php artisan serve
npm run dev
```

## Usuario admin (Statamic CP)

- CP URL: `http://localhost:8000/cp`
- Si necesitas crear admin:

```bash
php artisan statamic:make:user admin@tcc.local --super
```

## Flujo de formulario sensible

1. Usuario envía formulario en Home.
2. Statamic valida y procesa el submit.
3. Listener `StoreSensitiveFormSubmission`:
   - evita duplicados rápidos (dedupe temporal),
   - cifra payload con `Crypt::encryptString`,
   - guarda en tabla `sensitive_form_submissions`.
4. Datos se consultan en CP en ruta segura.

## Seguridad implementada

- `store: false` en `resources/forms/contacto.yaml` para no persistir YAML plano de submissions.
- Payload cifrado en DB (`encrypted_payload`).
- Hash de email (`email_hash`) para búsqueda sin exponer correo en claro.
- Vistas de consulta protegidas por autenticación y validación de usuario super admin.
- Prevención de doble envío:
  - frontend: bloqueo de submit y botón deshabilitado,
  - backend: deduplicación temporal por huella.

## Rutas importantes

- Sitio:
  - `/`
  - `/blog`

- Statamic CP:
  - `/cp`
  - `/cp/forms/contacto`

- Submissions sensibles (CP):
  - `/cp/forms/contacto/sensitive-submissions`
  - `/cp/forms/contacto/sensitive-submissions/{id}`

## Comandos útiles

```bash
# refrescar cache de contenido Statamic
php artisan statamic:stache:refresh

# limpiar caches de Laravel
php artisan optimize:clear

# build de frontend
npm run build
```

## Notas para GitHub

- No subir credenciales ni `.env`.
- Antes de abrir PR:
  - ejecutar `php artisan migrate` en entorno local,
  - ejecutar `npm run build` o `npm run dev`,
  - validar flujo de formulario y visualización en CP.

## Licencia

Proyecto privado para uso interno del equipo/cliente.
