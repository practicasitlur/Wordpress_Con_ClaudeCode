# Plugin: API ODOO-WORDPRESS

## ¿Para qué sirve?

Este plugin permite que **WordPress llame a la API de Odoo** para sincronizar el estado de las tiendas.

El flujo es:
```
WordPress (este plugin)  →  petición HTTP  →  Odoo  →  respuesta con datos
```

WordPress es el cliente. Odoo es el servidor que responde.

---

## ¿Qué problema resuelve?

Mantener sincronizadas las tiendas de WordPress con los datos reales de Odoo: importar tiendas activas, actualizar su fecha de último pedido y eliminar las que llevan más de 1 año sin comprar.

---

## ¿Cómo funciona?

### Dos modos de operación

**1. Importación completa** (botón manual en el panel)

1. Conecta con Odoo usando las credenciales guardadas (JSON-RPC sobre HTTPS)
2. Obtiene todos los contactos de Odoo con la categoría **"TIENDA"** (`res.partner`)
3. Para cada tienda consulta el último pedido confirmado en `sale.order`
4. Decide qué hacer:
   - Sin pedidos → se omite
   - Último pedido hace más de 1 año → se omite
   - Último pedido hace menos de 1 año → se crea o actualiza en WordPress
5. Elimina definitivamente todas las tiendas de WordPress que no estén en la lista importada

**2. Revisión diaria** (cron automático de WordPress)

1. Recorre todas las tiendas de WordPress que tienen `tienda_id` relleno
2. Consulta Odoo por el último pedido de cada una
3. Decide:
   - Último pedido hace más de 1 año → elimina definitivamente (`wp_delete_post`)
   - Último pedido hace menos de 1 año → actualiza `fecha_ultimo_pedido`

---

## Mapeo de campos Odoo → WordPress

| Campo WordPress | Meta key | Origen Odoo |
|---|---|---|
| Título | `post_title` | `name` |
| ID Odoo | `tienda_id` | `id` (numérico) |
| Dirección | `tienda_direccion` | `street` |
| Ciudad | `tienda_ciudad` | `city` |
| Código postal | `tienda_codigo_postal` | `zip` |
| Teléfono | `tienda_telefono` | `phone` / `mobile` |
| Fecha último pedido | `fecha_ultimo_pedido` | Calculado desde `sale.order` (formato `Ymd`) |
| Zona (taxonomía) | `zona` | `state_id` (provincia, limpiando código de país) |

---

## Seguridad

- Comunicación por **JSON-RPC sobre HTTPS**
- Autenticación con **API Key** de Odoo (no contraseña de usuario)
- Credenciales guardadas en `wp_options` (nunca en código ni en GitHub)
- `.env` con credenciales excluido del repositorio mediante `.gitignore`

---

## Estructura de archivos

```
API ODOO-WORDPRESS/
├── API ODOO-WORDPRESS.php              ← Archivo principal: cron, menú admin
├── includes/
│   ├── class-odoo-client.php           ← Comunicación JSON-RPC con Odoo
│   └── class-tiendas-sync.php          ← Lógica de importación y revisión diaria
└── admin/
    └── settings-page.php               ← Panel de configuración en WordPress
```

---

## Panel de administración

Accesible desde el menú lateral de WordPress → **Sync Odoo**.

- **Guardar configuración** — guarda URL, base de datos, usuario y API Key de Odoo
- **Importar tiendas desde Odoo** — importación completa (reset + import)
- **Ejecutar revisión diaria** — revisión manual del cron

El panel muestra el resultado de la última ejecución con una tabla coloreada:
- 🟢 Verde → importada / actualizada
- 🟡 Amarillo → omitida / sin pedidos
- 🔴 Rojo → eliminada / error

---

## Datos de conexión Odoo

- **URL:** `https://development-erp-thelocopolo.odoo.com`
- **Base de datos:** `locopolo-development-29541185`
- **Usuario:** `BSistemas`
- **API Key:** guardada en `.env` (no en el repositorio)

---

## Estado del desarrollo

- [x] Conexión con Odoo verificada
- [x] Importación completa funcionando
- [x] Revisión diaria (cron) funcionando
- [x] Mapeo de campos Odoo → WordPress
- [x] Asignación de taxonomía `zona`
- [x] Panel de administración con log de resultados
- [ ] Actualización en tiempo real al crear/modificar pedido en Odoo (pendiente)
- [ ] Actualización en tiempo real al crear/eliminar tienda en Odoo (pendiente)
