# Guía de diseño y modularización del portal de empresa

Este portal ya cuenta con una separación de estilos por **capas** (base, layout, componentes, módulos y utilidades)**.** Esta guía resume cómo está organizado el CSS y ofrece criterios para mantenerlo consistente y profesional.

## Estructura actual
- `assets/css/base/reset.css`: limpia márgenes/espaciados, normaliza `box-sizing` y estados de foco accesibles.
- `assets/css/base/base.css`: aplica tipografía, colores globales y tamaños base importando el tema.
- `assets/css/base/portalempresa.css`: ensambla la base + componentes core para las vistas generales del portal.
- `assets/css/themes/portal_styles.css`: tokens de tema (colores, tipografías, radios, sombras, transiciones).
- `assets/css/layout/`: piezas estructurales reutilizables (`containers.css`, `grid.css`, `sidebar.css`, `responsive.css`, `portal_header.css`, `header.css`).
- `assets/css/components/`: elementos atómicos y moleculares (botones, badges, tablas, formularios, alertas, utilidades, etc.).
- `assets/css/utilities/`: helpers transversales (`utilities.css`, `responsive.css`).
- `assets/css/modules/`: hojas por pantalla/flujo (`portal_login.css`, `portal_dashboard.css`, `machoteview.css`, etc.) que deberían apoyarse únicamente en tema + componentes + layout.

## Convenciones recomendadas
1. **Orden de imports** en cada módulo: primero reseteo (`base/reset.css`) y base (`base/base.css`), luego el tema si el archivo no lo trae, seguido de layout y componentes, y al final utilidades. Evita reordenarlos para que la cascada sea predecible.
2. **Alcance reducido**: los módulos (`modules/*.css`) solo deben definir reglas específicas de su vista. Si un selector se repite en dos módulos, promuévelo a `components/` o `layout/`.
3. **Nomenclatura**: prioriza nombres descriptivos en inglés con prefijos de contexto (`.portal-`, `.card-`, `.form-`). Para variantes usa modificadores (`.btn--primary`, `.card--elevated`).
4. **Variables primero**: cualquier color o radio nuevo debe declararse en `themes/portal_styles.css` para mantener coherencia cromática.
5. **Spacing consistente**: reutiliza las mismas unidades (px o rem) y apóyate en los `--radius*` y `--shadow*` existentes.
6. **Accesibilidad**: verifica contraste al usar `--primary` sobre `--panel` y añade `:focus` visibles en inputs/botones.

## Checklist rápida antes de agregar estilos
- [ ] ¿El componente ya existe en `components/`? Reutilízalo o extiéndelo con un modificador.
- [ ] ¿El patrón se repite en más de una vista? Muévelo a `components/` o `layout/`.
- [ ] ¿Se usan únicamente variables definidas en `themes/`? Añade nuevas si es necesario.
- [ ] ¿El archivo del módulo importa el tema y utilidades en el orden recomendado?
- [ ] ¿El selector está suficientemente acotado para no afectar otras pantallas?

## Ejemplos de uso
- Para una nueva pantalla, crea `assets/css/modules/<pantalla>.css` que empiece con:
  ```css
  @import "../base/reset.css";
  @import "../base/base.css";
  /* Si el módulo no importa la base del portal, añade aquí el tema */
  @import "../themes/portal_styles.css";
  @import "../components/buttons.css";
  @import "../layout/containers.css";
  @import "../layout/grid.css";
  @import "../components/cards.css";
  @import "../utilities/utilities.css";
  @import "../layout/responsive.css";
  ```
- Si necesitas un encabezado reutilizable, colócalo en `assets/css/layout/` y no en un módulo puntual.

Siguiendo estas pautas se mantiene una jerarquía clara (tema → layout → componentes → módulos) que facilita el mantenimiento y la evolución del portal.
