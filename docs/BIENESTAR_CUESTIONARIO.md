# Landing Bienestar + check-in emocional (`/bienestar`)

Referencia de implementacion (mayo 2026). Es una **pagina aparte del home**: no sustituye la entrada `home` ni el blueprint Ilse del replicator.

## Objetivo

- URL publica fija: **`/bienestar`** (slug acordado).
- Contenido **editable en Statamic** (coleccion `pages`, blueprint dedicado).
- Incluye hero, bloque “Sobre mi” (bard), areas en pills, **check-in emocional** con JS (puntuacion y alerta de categoria `risk`), CTA final.
- Especificacion inicial en la carpeta [`cuestionario/`](../cuestionario/) (`home.yaml`, `home.antlers.html`, `instrucciones.md`); la implementacion en produccion vive bajo `resources/` y la app.

## Archivos principales

| Rol | Ruta |
|-----|------|
| Blueprint | [`resources/blueprints/collections/pages/cuestionario.yaml`](../resources/blueprints/collections/pages/cuestionario.yaml) |
| Entrada (git / flat file) | [`content/collections/pages/bienestar.md`](../content/collections/pages/bienestar.md) |
| Arbol de paginas (CP) | [`content/trees/collections/pages.yaml`](../content/trees/collections/pages.yaml) (entrada `bienestar`) |
| Ruta Laravel | [`routes/web.php`](../routes/web.php) — `GET /bienestar` → `BienestarController@show`, nombre `bienestar` |
| Controlador | [`app/Http/Controllers/BienestarController.php`](../app/Http/Controllers/BienestarController.php) |
| Vista Blade | [`resources/views/pages/cuestionario.blade.php`](../resources/views/pages/cuestionario.blade.php) |
| Estilos (scope `.cuestionario-page`) | [`resources/css/cuestionario.css`](../resources/css/cuestionario.css) |
| JS del check-in | [`resources/js/cuestionario-checkin.js`](../resources/js/cuestionario-checkin.js) |
| Vite | [`vite.config.js`](../vite.config.js) — entradas `cuestionario.css` y `cuestionario-checkin.js` |
| Layout (stacks) | [`resources/views/layout.blade.php`](../resources/views/layout.blade.php) — `@stack('styles')`, `@stack('scripts')` |
| Imagen hero (Glide) | [`app/Support/CuestionarioHeroImage.php`](../app/Support/CuestionarioHeroImage.php) |
| Bard `about_body` sin `augmentedValue` de la entrada | [`app/Support/CuestionarioAboutBody.php`](../app/Support/CuestionarioAboutBody.php) |
| Menu Ilse (etiqueta + enlace) | Campo `ilse_nav_bienestar` en blueprint [`pages`](../resources/blueprints/collections/pages/pages.yaml) y contenido del home; [`ilse-header` / `ilse-footer`](../resources/views/partials/layout/) usan `route('bienestar')` |

## Comportamiento tecnico

1. **Patron de ruta** igual que `/blog`: ruta explicita en Laravel, no solo ruta Statamic del arbol, para URL estable.
2. **Vista sin objeto `Entry`**: el controlador pasa **`$page = $entry->data()->all()`** para evitar que herramientas de depuracion o dumps disparen `blueprint()` cuando el blueprint no esta sincronizado en Eloquent.
3. **`about_body` (bard)**: se renderiza con el `Augmentor` de Statamic y un `Field` definido en codigo; si falla, hay **fallback** HTML simple desde la estructura ProseMirror (parrafos/listas).
4. **Eloquent y CP**: los blueprints del proyecto viven en YAML pero, con `blueprints` en driver eloquent, **tienen que existir en la base de datos** para que el CP liste/edite bien. Sin importar el blueprint `cuestionario`, pueden aparecer errores tipo `Blueprint [] not found`.

## Comandos de sincronizacion (Eloquent)

Tras anadir o cambiar blueprints en YAML:

```bash
php please eloquent:import-blueprints --force --no-interaction
```

Importante: sin **`--force`**, usar solo **`--no-interaction`** puede **no importar** blueprints (el comando pide confirmacion).

Tras anadir o cambiar entradas en `content/collections/**/*.md` cuando `entries` es eloquent:

```bash
php please eloquent:import-entries --no-interaction
```

## Verificacion rapida

- Front: `GET /bienestar` muestra la landing y el check-in funciona (incl. alerta si hay respuesta en pregunta de `risk`).
- CP: **Colecciones → Pages → Bienestar** abre con blueprint **Landing psicoterapia + check-in** (`cuestionario`).
- BD (opcional): tabla `blueprints`, `namespace = collections.pages`, `handle = cuestionario`; entrada `bienestar` con columna `blueprint = cuestionario` cuando aplique el driver.

## Ver tambien

- [STATAMIC.md](../STATAMIC.md) — seccion 7 (checklist) y arquitectura Eloquent.
- [GLIDE_CACHE_FIX.md](./GLIDE_CACHE_FIX.md) — si hay temas con imagenes Glide en otros contextos.
