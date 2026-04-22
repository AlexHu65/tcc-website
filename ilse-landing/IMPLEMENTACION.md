# IMPLEMENTACION.md

## Resumen general
Este directorio contiene una base inicial para comenzar la creación de la landing page de **Ilse Méndez | Psicoterapia Cognitivo Conductual**. La intención es partir de un estilo visual **cálido, limpio, elegante y sereno**, con una experiencia de marca que se sienta profesional pero cercana.

## Archivos incluidos
- `index.html`: archivo base de la landing page.
- `assets/`: carpeta con las imágenes generadas y referencias visuales del logo y mockups.

## Templates
La landing se construyó con una estructura simple y fácil de extender por agentes o desarrolladores:

1. **Header / navegación**
   - Logo a la izquierda
   - Menú central o lateral
   - Botón principal de cita

2. **Hero principal**
   - Titular emocional y claro
   - Texto corto con propuesta de valor
   - CTA principal y secundario
   - Mockup visual de apoyo

3. **Bloque de enfoque / beneficios**
   - Tarjetas con beneficios clave
   - Mensajes breves: evidencia, personalización, empatía, crecimiento

4. **Servicios o áreas de acompañamiento**
   - Grid de temas principales
   - Posibilidad de convertir cada tarjeta en enlace a una página interna

5. **Sobre mí**
   - Espacio para biografía cálida y personal
   - Imagen, logo o futura foto profesional

6. **CTA final**
   - Bloque directo para agendar cita
   - Integrable con WhatsApp, Calendly, Google Calendar o formulario

7. **Footer**
   - Logo
   - Navegación secundaria
   - Datos de contacto

## Colores
Paleta sugerida basada en los mockups y logos generados:

- `#f5efe9` → fondo principal cálido
- `#efe7df` → fondo suave alterno
- `#fbf7f3` → superficie clara
- `#2c241f` → texto principal
- `#6e655d` → texto secundario
- `#d8ccc0` → líneas y bordes suaves
- `#8d8d70` → verde oliva principal
- `#6f7157` → verde oliva oscuro
- `#b89473` → acento cálido / taupe

## Fuentes
Se definieron dos familias tipográficas para lograr equilibrio entre elegancia y claridad:

### Principal para títulos
- **Cormorant Garamond**
- Uso: titulares, nombre de marca, frases editoriales
- Sensación: sofisticada, humana, elegante

### Secundaria para texto
- **Inter**
- Uso: párrafos, navegación, botones, bloques informativos
- Sensación: limpia, moderna, legible

## Uso esperado por agentes con skills de Cursor
Esta base está pensada para que un agente pueda:
- convertir el HTML en componentes reutilizables
- separar estilos a un archivo CSS o sistema de diseño
- migrar la implementación a React / Next.js / Astro / Webflow
- sustituir mockups por imágenes finales
- conectar formularios, analítica y CTA reales
- transformar secciones estáticas en CMS o contenido editable

## Notas de implementación
- El archivo actual usa **CSS embebido** para acelerar el arranque.
- Más adelante conviene separar a:
  - `styles.css`
  - `assets/`
  - componentes o partials
- Los textos actuales funcionan como base editable.
- El correo y los datos de contacto están colocados como placeholder.

## Assets
La carpeta `assets` incluye:
- versiones de logo generadas
- exploraciones de integración del cerebro
- mockups de landing page
- referencias visuales de estilo

## Recomendación siguiente paso
1. Definir versión final del logo.
2. Sustituir mockups por fotos o renders finales.
3. Ajustar copy definitivo de hero, sobre mí y servicios.
4. Conectar CTA a WhatsApp o sistema de agenda.
5. Migrar esta base a framework si se desea escalar.
