# Configuración del Restaurante El Chaqueño

## Problema de Redirección a phpMyAdmin

Si al hacer clic en "Inicio" te redirige a phpMyAdmin, es porque estás accediendo incorrectamente al proyecto.

### Solución 1: Acceso Correcto (Recomendado)
Accede al proyecto usando la carpeta `public`:
```
http://localhost/App_restaurante/public
```

### Solución 2: Virtual Host (Profesional)
1. Edita el archivo `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Agrega al final:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/laravel/App_restaurante/public"
    ServerName restaurante.local
    <Directory "C:/xampp/htdocs/laravel/App_restaurante/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Edita el archivo `C:\Windows\System32\drivers\etc\hosts` (como administrador)
4. Agrega la línea:
```
127.0.0.1 restaurante.local
```

5. Reinicia Apache
6. Accede desde: `http://restaurante.local`

## Colores Actualizados
- El proyecto ahora usa verde oscuro (#15803d) en lugar de rojo
- Todos los archivos han sido actualizados con el nuevo esquema de colores

## Rutas Principales
- Inicio: `/` (public.home)
- Menú: `/menu` (public.menu)
- Pedidos: `/pedido` (public.order)
- Admin: `/login` (login)
- Panel Admin: `/admin` (admin.dashboard)