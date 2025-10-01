# Cambios de Colores - Restaurante El Chaqueño

## Resumen de Cambios
Se ha actualizado completamente la paleta de colores del proyecto, cambiando de tonos verdes a una elegante combinación de café y dorado que refleja mejor la identidad de un restaurante.

## Nueva Paleta de Colores

### Colores Principales (Chaqueno)
- **50**: `#fdf8f6` - Café muy claro (fondos)
- **100**: `#f2e8e5` - Café claro (bordes suaves)
- **200**: `#eaddd7` - Café claro medio
- **300**: `#e0cfc5` - Café medio claro
- **400**: `#d2bab0` - Café medio
- **500**: `#a0522d` - Café principal (botones, enlaces)
- **600**: `#8b4513` - Café oscuro (hover, activos)
- **700**: `#6f3609` - Café muy oscuro (texto, bordes)
- **800**: `#5d2f02` - Café profundo
- **900**: `#3e1f01` - Café más oscuro

### Colores Complementarios (Golden)
- **50**: `#fffbeb` - Dorado muy claro
- **100**: `#fef3c7` - Dorado claro
- **200**: `#fde68a` - Dorado claro medio
- **300**: `#fcd34d` - Dorado medio
- **400**: `#fbbf24` - Dorado medio intenso
- **500**: `#f59e0b` - Dorado principal
- **600**: `#d97706` - Dorado oscuro
- **700**: `#b45309` - Dorado muy oscuro
- **800**: `#92400e` - Dorado profundo
- **900**: `#78350f` - Dorado más oscuro

## Archivos Modificados

### 1. Configuración de Tailwind
- `tailwind.config.js` - Actualizada la paleta de colores completa

### 2. Estilos CSS
- `resources/css/app.css` - Nuevas clases de componentes con colores café y dorado

### 3. Layouts
- `resources/views/layouts/admin.blade.php` - Panel administrativo
- `resources/views/layouts/cajero.blade.php` - Panel de cajero
- `resources/views/layouts/public.blade.php` - Sitio público

## Nuevas Clases CSS Disponibles

### Botones
```css
.btn-chaqueno - Botón principal café
.btn-golden - Botón dorado complementario
```

### Enlaces de Sidebar
```css
.sidebar-link - Enlaces con hover café
.sidebar-link.active - Estado activo con gradiente café
```

### Tarjetas
```css
.card-chaqueno - Tarjetas con bordes café suaves
```

## Combinaciones de Colores Recomendadas

### Para Elementos Principales
- Fondo: `chaqueno-600` o `chaqueno-700`
- Texto: `white` o `chaqueno-50`
- Hover: `chaqueno-700` o `chaqueno-800`

### Para Elementos Secundarios
- Fondo: `golden-500` o `golden-600`
- Texto: `white`
- Hover: `golden-600` o `golden-700`

### Para Elementos de Información
- Fondo: `chaqueno-50` o `chaqueno-100`
- Texto: `chaqueno-700` o `chaqueno-800`
- Bordes: `chaqueno-200` o `chaqueno-300`

## Iconos por Sección
Se mantuvieron los iconos pero se actualizaron sus colores:
- Dashboard: `chaqueno-600`
- Usuarios: `golden-600`
- Productos: `golden-600`
- Inventario: `chaqueno-600`
- Ventas: `golden-600`
- Herramientas: Alternando entre `chaqueno-600` y `golden-600`

## Compilación
Los estilos han sido compilados con:
```bash
npm run build
```

## Resultado
El nuevo diseño presenta una apariencia más cálida y elegante, perfecta para un restaurante, con:
- Colores café que evocan calidez y tradición
- Acentos dorados que añaden elegancia
- Mejor contraste y legibilidad
- Consistencia visual en todo el sistema