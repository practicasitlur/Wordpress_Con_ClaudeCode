# Proyecto: Integración API Odoo → WordPress

## 1. Contexto del Proyecto

Este documento describe la arquitectura y el plan de desarrollo para sincronizar datos desde **Odoo** hacia **WordPress (Locopolo)** a través de la WordPress REST API.

El objetivo es que, cuando se actualice un registro en Odoo (p. ej. una tienda cliente), se envíe automáticamente a WordPress y se guarde como un Custom Post Type con sus campos ACF.

---

## 2. Estructura de Archivos

```
wordpress/
└── API WORDPRESS-ODOO/                    ← carpeta raíz de esta integración
    ├── proyecto-integracion-odoo-wp.md    ← este archivo
    ├── conexion-api-odoo-wordpress/       ← junction NTFS al plugin activo (NO tocar la carpeta en sí)
    │   └── integracion-oddo.php          ← lógica del endpoint (editar aquí)
    └── pruebas/                           ← scripts de prueba, FUERA del junction
        └── test_endpoint.py
```

**Ruta real del plugin en el servidor local:**
```
C:\Users\usuario\Local Sites\locopolo-local\app\public\wp-content\plugins\integracion-oddo\
```

> `conexion-api-odoo-wordpress` es un junction NTFS que apunta directamente a esa carpeta.
> Editar los archivos dentro del junction equivale a editar el plugin en vivo.
> La carpeta `pruebas` está **fuera** del junction a propósito: los scripts de test
> no deben formar parte del plugin desplegado.

---

## 3. Endpoint Registrado

| Campo       | Valor                              |
|-------------|------------------------------------|
| Método      | `POST`                             |
| URL         | `/wp-json/odoo/v1/tiendas`         |
| Header auth | `X-Odoo-Token: <token_secreto>`    |
| Body        | JSON con `odoo_id`, `nombre`, `direccion`, `telefono` |

**Lógica del endpoint (`integracion-oddo.php`):**
1. Valida el token de cabecera `X-Odoo-Token`.
2. Busca si ya existe un post de tipo `tiendas` con el `id_odoo_tienda` recibido.
3. Si existe → `wp_update_post`. Si no → `wp_insert_post`.
4. Guarda campos ACF: `id_odoo_tienda`, `direccion_tienda`, `telefono_tienda`.
5. Devuelve `{"status": "success", "wp_post_id": <id>}` o un error 500.

---

## 4. Plan de Desarrollo en 5 Pasos

### Paso 1 — Plugin activo en WordPress local
- El plugin `integracion-oddo` ya existe en el servidor local.
- El archivo principal del plugin debe registrar el endpoint con `add_action('rest_api_init', ...)`.
- Verificar que esté **activado** desde el panel de WordPress (`/wp-admin/plugins.php`).

### Paso 2 — Aislamiento: probar el endpoint de WordPress
Simular llamadas como si fuera Odoo, usando Python o Postman:
```python
import requests

url = "http://locopolo-local.local/wp-json/odoo/v1/tiendas"
headers = {"X-Odoo-Token": "MI_SUPER_TOKEN_SECRETO_12345", "Content-Type": "application/json"}
payload = {"odoo_id": "TEST-001", "nombre": "Tienda Prueba", "direccion": "Calle Mayor 1", "telefono": "600000000"}

r = requests.post(url, json=payload, headers=headers)
print(r.status_code, r.json())
```
Probar también con un token incorrecto para verificar que devuelve `403 Forbidden`.

### Paso 3 — Aislamiento: configurar el emisor en Odoo
- Crear el trigger/script en Python dentro de la instancia de Odoo (copia de base de datos).
- El script debe construir el payload y llamar al endpoint de WordPress cuando cambie un registro.

### Paso 4 — Despliegue a Staging
- Subir el plugin `integracion-oddo` al WordPress de staging en WP Engine.
- URL de staging: `https://locopolostg.wpenginepowered.com/`
- Verificar que el endpoint responde igual que en local.

### Paso 5 — Prueba E2E
- Lanzar la sincronización desde la copia de Odoo apuntando al WordPress de staging.
- Confirmar que los posts de tipo `tiendas` se crean/actualizan correctamente con sus campos ACF.

---

## 5. Instrucciones para Claude Code

- **Editar únicamente** dentro del plugin `integracion-oddo` (accesible vía `conexion-api-odoo-wordpress/`). No tocar archivos del tema hijo para lógica de APIs.
- **Sanitizar siempre** las entradas con `sanitize_text_field()` antes de usarlas.
- **Comprobar errores** de `wp_insert_post` / `wp_update_post` con `is_wp_error()` antes de llamar a `update_field()`.
- **Sin librerías externas**: usar únicamente funciones nativas de WordPress y PHP (arrays nativos para mapeo JSON, no frameworks de serialización).
- El token secreto `MI_SUPER_TOKEN_SECRETO_12345` es provisional. Sustituirlo por una constante definida en `wp-config.php` antes de subir a producción.
