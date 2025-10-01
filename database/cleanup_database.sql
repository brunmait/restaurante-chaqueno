-- Script para limpiar base de datos bd_chaqueno
-- Eliminar tablas no utilizadas

-- Desactivar verificación de foreign keys temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas no utilizadas
DROP TABLE IF EXISTS app_settings;
DROP TABLE IF EXISTS display_queue;
DROP TABLE IF EXISTS failed_jobs;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS personal_access_tokens;
DROP TABLE IF EXISTS stock;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS ventas;

-- Reactivar verificación de foreign keys
SET FOREIGN_KEY_CHECKS = 1;

-- Verificar tablas restantes (las que SÍ necesitas)
SHOW TABLES;

-- Las tablas que deben quedar son:
-- - users (con role_id)
-- - roles
-- - categorias
-- - productos (nueva con categoria_id)
-- - proveedores
-- - compras
-- - compra_items
-- - stock_costillas
-- - ventas_costillas
-- - pedidos_online
-- - migrations