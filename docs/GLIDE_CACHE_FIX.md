# Solución: Error 500 en League\Flysystem\UnableToReadFile - Glide Cache

**Fecha:** 22 de abril de 2026  
**Estado:** ✅ Resuelto

## Síntomas

```
League\Flysystem\UnableToReadFile: Unable to read file from location: 
containers/assets/lupa-y-una-nota-con-la-palabra-ejemplos-ejemplo-de-concepto-marketing-empresarial-formacion-paro-plano-222957926.jpg/078b6e64c90ea8b67ccea36285afb298/lupa-y-una-nota-con-la-palabra-ejemplos-ejemplo-de-concepto-marketing-empresarial-formacion-paro-plano-222957926.jpg
```

**URL afectada:** `/img/asset/YXNzZXRzL2x1cGEteS11bmEtbm90YS1jb24tbGEtcGFsYWJyYS1lamVtcGxvcy1lamVtcGxvLWRlLWNvbmNlcHRvLW1hcmtldGluZy1lbXByZXNhcmlhbC1mb3JtYWNpb24tcGFyby1wbGFuby0yMjI5NTc5MjYuanBn/lupa-...jpg?w=720&h=450&fit=crop_focal&q=82`

**Stack trace origen:** 
- `vendor/statamic/cms/src/Http/Controllers/GlideController.php:103`
- `vendor/league/flysystem/src/Filesystem.php:81`
- `vendor/league/flysystem-local/LocalFilesystemAdapter.php:312`

## Análisis de la causa raíz

### El problema
Statamic Glide mantiene dos capas de cache para manipulaciones de imagen:

1. **Cache en Base de Datos/Store** (`storage/framework/cache/glide/`)
   - Almacena referencias de claves de manipulación (w=720, h=450, fit=crop_focal, etc.)
   
2. **Cache de archivos físicos** (`storage/statamic/glide/containers/assets/...`)
   - Almacena los archivos JPEG manipulados generados por Glide

### Lo que pasó
Existía una **inconsistencia entre capas de cache**:
- La BD/store contenía una clave de manipulación registrada para el asset
- **PERO** el archivo físico correspondiente NO existía en `storage/statamic/glide/`
- Cuando se intentaba acceder a la imagen, Glide buscaba leer desde cache
- Flysystem fallaba con `UnableToReadFile` porque el archivo estaba ausente

### Por qué ocurrió
Posibles causas:
1. Limpieza parcial de storage durante desarrollo
2. Sincronización incompleta entre BD y filesystem
3. Diferencia entre CLI y HTTP en rutas de cache
4. Recompilación de config que invalidó referencias antiguas

## Solución aplicada

Se ejecutó limpieza en cascada para sincronizar todas las capas:

### 1. Limpiar cache de aplicación global
```bash
php artisan cache:clear
```
Limpia `storage/framework/cache/` (incluyendo entradas stale del glide store)

### 2. Limpiar Stache de Statamic
```bash
php please stache:clear
```
Regenera índices internos de Statamic (assets, entries, etc.)

### 3. Eliminar registros de manipulaciones cacheadas
```bash
php please glide:clear
```
- Elimina referencias en `storage/framework/cache/glide/` (manifest keys)
- Elimina archivos en `storage/statamic/glide/` (cached images)
- Limpia índices de cache de Glide

### 4. Purgar cualquier archivo residual
```bash
rm -rf storage/statamic/glide/*
```
Asegura que no queden archivos huérfanos

## Verificación del fix

**Antes (Error 500):**
```bash
$ curl -s -w '%{http_code}\n' 'http://localhost:8001/img/asset/...jpg?w=720&h=450...'
500
```

**Después (Success):**
```bash
$ curl -s -w '%{http_code}\n' 'http://localhost:8001/img/asset/...jpg?w=720&h=450...'
200
# JPEG image data, JFIF standard 1.01, 720x399 components 3
```

El archivo se regenera desde cero sin errores:
```
$ find storage/statamic/glide -type f | head -n 3
storage/statamic/glide/containers/assets/lupa-y-una-nota-.../0ebc8727.../lupa-...jpg
```

## Prevención futura

### 1. Monitorear inconsistencias de cache
```bash
# Diagnóstico de Glide
php please glide:clear --verbose

# Diagnóstico de Stache
php please stache:doctor
```

### 2. Política de limpieza ordenada
Nunca eliminar directorios directamente. En su lugar:
```bash
# ✅ Correcto - respeta integridad
php artisan cache:clear
php please glide:clear

# ❌ Incorrecto - puede dejar estado inconsistente
rm -rf storage/statamic/glide/*
rm -rf storage/framework/cache/*
```

### 3. En producción
- Implementar monitoreo de diskspace en `storage/statamic/glide/`
- Hacer deploy con `php artisan optimize:clear` antes de servir assets
- Calentar cache de Glide para assets críticos:
  ```bash
  php please generate:glide-thumbs
  ```

### 4. En desarrollo
- Limpiar cache frecuentemente cuando modifiques assets/blueprints:
  ```bash
  php artisan cache:clear && php please glide:clear && php please stache:clear
  ```
- Usar `.gitignore` para evitar commitear archivos cacheados:
  ```gitignore
  storage/statamic/glide/**
  storage/framework/cache/glide/**
  ```

## Afectado por el bug

**Ubicaciones donde aparecía el 500:**
- Página de blog: carga de imagen destacada (featured_image)
- Card preview en index de blog
- Hero image en show view de post

**Asset afectado:**
- `lupa-y-una-nota-con-la-palabra-ejemplos-ejemplo-de-concepto-marketing-empresarial-formacion-paro-plano-222957926.jpg`
- Almacenado en: `public/assets/`
- Manipulaciones: Glide con crop focal, 720x450px, quality 82

## Archivos involucrados

```
config/filesystems.php                    # Configuración del disco 'assets'
config/statamic/assets.php                # Configuración de Glide
app/Support/BlogImage.php                 # Generador de URLs de imágenes del blog
resources/views/blog/index.blade.php      # Vista que consume BlogImage::cardUrl()
resources/views/blog/show.blade.php       # Vista que consume BlogImage::heroUrl()
vendor/statamic/cms/src/Http/Controllers/GlideController.php  # Generador HTTP
vendor/statamic/cms/src/Imaging/ImageGenerator.php           # Lógica Glide
vendor/statamic/cms/src/Facades/Glide.php                    # Facade
```

## Referencias

- [Statamic Glide Documentation](https://statamic.dev/image-manipulation)
- [League Flysystem](https://flysystem.thephpleague.com/)
- [Laravel Caching](https://laravel.com/docs/cache)
