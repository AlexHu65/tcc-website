# Home Ilse, bloques dinámicos y Eloquent (referencia)

Documento de referencia del flujo de contenido del home, el panel (CP) y la base de datos con Statamic 6 y el driver Eloquent.

## Resumen

- **Vista pública** ([`resources/views/pages/home.blade.php`](../resources/views/pages/home.blade.php)): recorre el replicator **`sections`**: bloques con `type` (`hero`, `ilse_enfoque`, `servicios_ilse`, `sobre_mi`, `contact_form`, etc.).
- **Shell (cabecera, pie, menú)**: campos raíz `ilse_*` leídos del entry `home` y pasados al layout vía [`app/Providers/AppServiceProvider.php`](../app/Providers/AppServiceProvider.php) (y la misma entrada alimenta el [layout](../resources/views/layout.blade.php) y [parciales Ilse](../resources/views/partials/layout/)).
- **Contenido en el CP** (edición en el sitio):
  1. Pestaña **Main** del entry home: título, plantilla, etc.
  2. Pestaña **Home Builder** : replicator **Sections** (Hero, Enfoque, Servicios Ilse, Sobre mí, formulario de contacto, etc.).
  3. Pestaña **Landing Ilse** : SEO, marca, logo, textos de navegación, pie (no el cuerpo de secciones que ya vive en *Home Builder*).

## Dónde editar qué (evitar duplicar)

| Contenido en la web | Dónde se edita en el CP |
|--------------------|-------------------------|
| Títulos/CTA del hero, enfoque, servicios (pills), sobre mí, formulario de contacto | **Home** → **Home Builder** → filas del replicator *Sections* |
| Texto de la barra superior, enlaces #inicio, #enfoque, pie, copyright, meta | **Home** → **Landing Ilse** (y en **Main** título/entry) |

No reintroduzcas en *Landing Ilse* bloques de cuerpo que ya modelaste en *Sections*; el front **no** mezcla esas claves antiguas (`ilse_enfoque_*` en raíz) con el replicator a menos que alguien vuelva a cablear la vista a mano.

## Archivos clave

| Ruta | Rol |
|------|-----|
| [`content/collections/pages/home.md`](../content/collections/pages/home.md) | Fuente en **archivo** (Stache) si usas entradas en disco. Con Eloquent, el dato canónico del home Suele ser la fila en `entries`. |
| [`resources/blueprints/collections/pages/pages.yaml`](../resources/blueprints/collections/pages/pages.yaml) | Definición del blueprint *pages* (pestañas, replicator, sets). |
| [`config/statamic/eloquent-driver.php`](../config/statamic/eloquent-driver.php) | Qué se guarda en **archivo** vs **Eloquent** (entradas, blueprints, fieldsets, etc.). |
| [`database/migrations/2026_04_21_200000_sync_home_entry_from_flat_file.php`](../database/migrations/2026_04_21_200000_sync_home_entry_from_flat_file.php) | Migra a la BD el front matter de `home.md` hacia el entry `home` (útil al alinear repo y base). |

## Driver Eloquent: blueprints en base de datos

Si `blueprints` usa **`driver` => `eloquent`** en [`eloquent-driver.php`](../config/statamic/eloquent-driver.php), el CP y Statamic cargan la **definición** del blueprint desde la tabla `blueprints`, **no** directamente desde `resources/blueprints/**/*.yaml`.

Los YAML del repo son la plantilla que debe **importarse** cuando cambias el esquema.

### Importar YAML → base de datos (obligatorio tras cambiar blueprints en git)

```bash
php please eloquent:import-blueprints --force --no-interaction
php artisan config:clear
php please stache:clear
```

Sin este paso, el CP puede seguir mostrando una versión vieja del replicator (por ejemplo sin los sets `ilse_enfoque`) y parecer que “no se pueden editar” los bloques.

### Exportar BD → archivos (opcional, para commit en git)

```bash
php please eloquent:export-blueprints
```

Comprueba en la documentación del paquete `statamic/eloquent-driver` qué rutas escribe exactamente la exportación.

## Entradas del home en BD vs `home.md`

Si las **entradas** están en Eloquent (`entries` → `driver` => `eloquent`), la ruta `/` en [`routes/web.php`](../routes/web.php) usa `Entry::find('home')` y los datos vienen del **JSON** en base de datos.

Cambiar solo `content/collections/pages/home.md` **no** actualiza automáticamente esa fila. Opciones:

1. Editar y guardar el entry **Home** en el CP.
2. Ejecutar la migración de sincronización del flat file (si sigue aplicable en tu entorno).
3. Usar `php please eloquent:import-entries` si tu flujo importa YAML a la BD (según configuración del proyecto).

## Comandos útiles (memoria rápida)

```bash
# Tras cambios en blueprints YAML
php please eloquent:import-blueprints --force --no-interaction

# Caché
php artisan config:clear
php please stache:clear

# Opcional: export blueprints desde BD a disco
php please eloquent:export-blueprints
```

## Anclas del CP (`#home_builder`)

La pestaña **Home Builder** suele coincidir con el handle `home_builder` en el blueprint. Si el CP no refleja cambios, suele ser **blueprint desactualizado en BD**: corrige con `eloquent:import-blueprints` como arriba.

## Front (recordatorio técnico)

- Layout: [`resources/views/layout.blade.php`](../resources/views/layout.blade.php) — Vite: `app.css`, `ilse.css`, `app.js` (formulario por pasos en `data-contact-form`).
- Variable en las vistas del home: **`$sections`** (array del replicator).
- Helper de imágenes Ilse: rutas bajo `public/images/ilse/` como nombres de archivo en los campos de texto del blueprint.

---

*Última revisión alineada con el estado del repo (home modular + Landing Ilse para shell + blueprints importables a Eloquent).*
