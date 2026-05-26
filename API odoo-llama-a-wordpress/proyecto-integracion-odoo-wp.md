# Plugin: API WORDPRESS-ODOO

## ¿Para qué sirve?

Este plugin permite que **Odoo llame a WordPress** para mantener sincronizadas las tiendas. WordPress actúa como **servidor** y expone endpoints REST que Odoo consume.

```
Odoo (cliente)  →  petición HTTP  →  WordPress (este plugin)
```

Es el complemento al plugin `API ODOO-WORDPRESS`, que hace el flujo inverso (WordPress consulta a Odoo).

---

## ¿Qué problema resuelve?

Que los cambios hechos en Odoo (alta de tienda, cambio de datos, confirmación de pedido, desactivar publicación en web, baja de contacto) se reflejen automáticamente en WordPress sin intervención humana.

---

## Endpoints expuestos

Todos los endpoints requieren el header `X-Odoo-Token` con el valor configurado en `wp_options` (clave `aow_odoo_token`).

### `POST /wp-json/odoo/v1/tiendas`

Crea o actualiza una tienda en WordPress.

**Body (JSON):**
```json
{
  "odoo_id": 1234,
  "nombre": "Tienda Ejemplo",
  "direccion": "Calle Mayor 1",
  "ciudad": "Madrid",
  "telefono": "600000000",
  "zona": "Madrid",
  "tipo": "",
  "fecha_ultimo_pedido": "20260520"
}
```

**Respuesta correcta:**
```json
{
  "status": "success",
  "wp_post_id": 4079,
  "post_title": "Tienda Ejemplo",
  "post_type": "tienda",
  "wpml_lang": "es",
  "zona": "Madrid",
  "tipo": "no enviado"
}
```

**Lógica:**
1. Valida `X-Odoo-Token`.
2. Busca un post `tienda` con `tienda_id = odoo_id` en el meta.
3. Si existe → `wp_update_post`. Si no → `wp_insert_post`.
4. Asigna el idioma por defecto vía WPML.
5. Guarda los campos ACF: `tienda_id`, `tienda_direccion`, `tienda_ciudad`, `tienda_telefono`.
6. Asigna taxonomías `zona` y `tipo-tienda`.

---

### `DELETE /wp-json/odoo/v1/tiendas`

Elimina definitivamente una tienda en WordPress.

**Body (JSON):**
```json
{ "odoo_id": 1234 }
```

**Respuesta:**
- `200 success` — tienda eliminada
- `404 not_found` — no existe en WordPress
- `400 error` — falta `odoo_id`

Internamente hace `wp_delete_post($id, true)` (sin papelera).

---

### `GET /wp-json/odoo/v1/diagnostico`

Endpoint de diagnóstico para inspeccionar el estado de WordPress: lista los `post_type` que existen, muestra ejemplos de posts que parecen tiendas y devuelve los `meta_keys` usados. Útil al integrar para confirmar nombres reales.

---

## Seguridad

- Autenticación por **token** en el header `X-Odoo-Token`.
- Token almacenado en `wp_options` bajo la clave `aow_odoo_token` (configurable sin tocar código).
- Por ahora hay un valor por defecto hardcodeado como fallback (`MI_SUPER_TOKEN_SECRETO_12345`). **Cambiar antes de producción.**
- Comunicación solo por HTTPS en producción (en local da igual).

Para cambiar el token desde WordPress:
```php
update_option('aow_odoo_token', 'tu-token-seguro-aqui');
```

---

## Estructura de archivos

```
API WORDPRESS-ODOO/
├── proyecto-integracion-odoo-wp.md    ← este archivo
├── conexion-api-wordpress-odoo/       ← junction NTFS al plugin activo
│   └── API WORDPRESS-ODOO.php         ← endpoints y lógica
└── pruebas/                           ← fuera del junction (no se despliega)
    └── test_endpoint.py
```

La carpeta `conexion-api-wordpress-odoo/` es un junction NTFS hacia:
```
C:\Users\usuario\Local Sites\locopolo-local\app\public\wp-content\plugins\API WORDPRESS-ODOO
```

Editar dentro del junction equivale a editar el plugin en vivo.

---

## Cómo lo llama Odoo

El módulo **`locopolo_wordpress_sync`** del lado Odoo (en `locopolo-development/`) es quien consume estos endpoints. Ver su documentación:
- `locopolo-development/locopolo_wordpress_sync/README.md`

Los eventos en Odoo que disparan llamadas a estos endpoints:

| Evento Odoo | Endpoint llamado |
|---|---|
| Crear contacto TIENDA con `mostrar_en_web = True` y pedido < 1 año | `POST /tiendas` |
| Modificar contacto TIENDA (nombre, dirección, teléfono, etc.) | `POST /tiendas` |
| `mostrar_en_web` cambia de False a True con pedido reciente | `POST /tiendas` |
| Confirmar presupuesto de un contacto TIENDA con `mostrar_en_web = True` | `POST /tiendas` |
| `mostrar_en_web` cambia de True a False | `DELETE /tiendas` |
| Eliminar contacto TIENDA en Odoo | `DELETE /tiendas` |

---

## Estado del desarrollo

- [x] Endpoint POST de crear/actualizar tienda
- [x] Endpoint DELETE de eliminar tienda
- [x] Endpoint GET de diagnóstico
- [x] Token configurable vía `wp_options`
- [x] Soporte WPML (asignación de idioma por defecto)
- [ ] Panel de administración para cambiar el token desde WordPress (no urgente; se puede usar `update_option`)
- [ ] Logging de llamadas recibidas en `wp_options` o tabla propia (no urgente)
