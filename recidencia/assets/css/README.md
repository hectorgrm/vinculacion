# Sistema CSS modular para Residencia

Esta carpeta sigue una estructura empresarial pensada para escalar el front-end sin perder orden ni rendimiento. Incluye capas claras (base, tema, layout, componentes, utilidades y módulos) y preserva los estilos existentes mediante empaquetadores en `modules/` y la carpeta `legacy/`.

## Estructura
```
assets/css/
├── base/            # Fundamentos y normalización
├── components/      # Componentes reutilizables (botones, alerts, tablas, etc.)
├── layout/          # Estructura y rejillas globales
├── modules/         # Enlaces a estilos específicos por módulo funcional
├── utilities/       # Clases atómicas de apoyo
├── legacy/          # Espacio para estilos heredados sin refactor
├── theme.css        # Paleta, radios, sombras y tipografías globales
└── dashboard.css    # Bundle de compatibilidad con el antiguo punto de entrada
```

## Orden de carga recomendado
Incluye las hojas en el `<head>` siguiendo este orden para garantizar la cascada correcta:
```html
<!-- Theme -->
<link rel="stylesheet" href="/assets/css/theme.css">
<!-- Base -->
<link rel="stylesheet" href="/assets/css/base/reset.css">
<link rel="stylesheet" href="/assets/css/base/base.css">
<!-- Layout -->
<link rel="stylesheet" href="/assets/css/layout/containers.css">
<link rel="stylesheet" href="/assets/css/layout/sidebar.css">
<link rel="stylesheet" href="/assets/css/layout/header.css">
<link rel="stylesheet" href="/assets/css/layout/grid.css">
<link rel="stylesheet" href="/assets/css/layout/responsive.css">
<!-- Components -->
<link rel="stylesheet" href="/assets/css/components/buttons.css">
<link rel="stylesheet" href="/assets/css/components/alerts.css">
<link rel="stylesheet" href="/assets/css/components/badges.css">
<link rel="stylesheet" href="/assets/css/components/cards.css">
<link rel="stylesheet" href="/assets/css/components/tables.css">
<link rel="stylesheet" href="/assets/css/components/forms.css">
<link rel="stylesheet" href="/assets/css/components/progress.css">
<link rel="stylesheet" href="/assets/css/components/modal.css">
<link rel="stylesheet" href="/assets/css/components/breadcrumbs.css">
<!-- Utilities -->
<link rel="stylesheet" href="/assets/css/utilities/utilities.css">
<!-- Módulos (ejemplo) -->
<link rel="stylesheet" href="/assets/css/modules/empresa.css">
<!-- Legacy (solo mientras migras) -->
<link rel="stylesheet" href="/assets/css/legacy/stylesrecidencia.css">
```

Si todavía consumías `dashboard.css`, seguirá funcionando porque ahora actúa como bundle que importa todo lo anterior.

## Qué va en cada carpeta
- **base/**: `reset.css` normaliza el navegador y `base.css` fija tipografía, colores de texto, contenedores y helpers simples.
- **theme.css**: centraliza variables de color, radios, sombras y tipografías. Ajusta aquí la identidad visual del proyecto.
- **layout/**: estructura de página (`app`, `main`, contenedores, header y grid). `responsive.css` concentra las media queries globales.
- **components/**: bloques reutilizables (botones, alerts, badges, tarjetas, tablas, formularios, barras de progreso, modales y breadcrumbs).
- **utilities/**: clases atómicas (`flex`, `items-center`, `mt-4`, etc.) para maquetar rápido sin crear nuevas reglas.
- **modules/**: hojas de estilo específicas por dominio (empresa, convenios, documentos, portal, etc.). Cada archivo importa los estilos existentes de su carpeta original para mantener compatibilidad.
- **legacy/**: espacio seguro para estilos que aún no se han migrado; permite retirarlos gradualmente.

## Cómo dividir los estilos existentes
- Mantén reglas genéricas (colores, tipografías, paddings base) en `theme.css` y `base/`.
- Mueve estructura (distribución de columnas, cabeceras, sidebar, grids) a `layout/`.
- Extrae patrones repetidos (botones, tarjetas, tablas, alerts) a `components/`.
- Deja las peculiaridades de cada pantalla o dominio en el archivo correspondiente de `modules/`.
- Si encuentras CSS antiguo mezclado, guárdalo temporalmente en `legacy/stylesrecidencia.css` y refactoriza después.

## Ejemplo de importación modular en una vista de Empresa
```html
<link rel="stylesheet" href="/assets/css/theme.css">
<link rel="stylesheet" href="/assets/css/base/reset.css">
<link rel="stylesheet" href="/assets/css/base/base.css">
<link rel="stylesheet" href="/assets/css/layout/containers.css">
<link rel="stylesheet" href="/assets/css/components/buttons.css">
<link rel="stylesheet" href="/assets/css/modules/empresa.css">
```

## Compatibilidad
- `dashboard.css` ahora funciona como punto de entrada único para páginas antiguas, importando todas las capas nuevas.
- Los archivos dentro de `modules/` solo referencian estilos de sus carpetas originales, por lo que no rompen rutas existentes mientras se actualiza el HTML.
