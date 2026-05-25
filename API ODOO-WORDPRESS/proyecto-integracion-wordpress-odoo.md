# Plugin: API ODOO-WORDPRESS

## ¿Para qué sirve?

Este plugin permite que **WordPress llame a la API de Odoo** para consultar o sincronizar datos.

El flujo es:
```
WordPress (este plugin)  →  petición HTTP  →  Odoo  →  respuesta con datos
```

WordPress es el cliente. Odoo es el servidor que responde.

---

## ¿Qué problema resuelve?

Odoo necesita consultar datos de WordPress: tiendas, caterings, productos, etc.
Sin este plugin, esos datos no son accesibles de forma segura ni estructurada desde Odoo.

---

## ¿Cómo funciona?

1. Odoo hace una petición HTTP a un endpoint de WordPress (ej. `GET /wp-json/wp-odoo/v1/shops`)
2. El plugin valida que la petición lleva una **API Key** válida en el header `X-API-Key`
3. Si la clave es correcta, el plugin consulta la base de datos de WordPress y devuelve los datos en JSON
4. Si la clave es incorrecta o falta, devuelve un error `401`

---

## Seguridad

- Autenticación por **API Key** almacenada en `wp_options`
- Opción de **whitelist de IPs**: solo las IPs de Odoo pueden llamar a la API
- La API Key se puede regenerar desde el panel de administración de WordPress

---

## Endpoints previstos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/wp-json/wp-odoo/v1/shops` | Listar todas las tiendas |
| GET | `/wp-json/wp-odoo/v1/shops/{id}` | Obtener una tienda por ID |
| POST | `/wp-json/wp-odoo/v1/shops` | Crear una tienda |
| PUT | `/wp-json/wp-odoo/v1/shops/{id}` | Actualizar una tienda |
| DELETE | `/wp-json/wp-odoo/v1/shops/{id}` | Eliminar una tienda |

---

## Estructura de archivos

```
API ODOO-WORDPRESS/
├── API ODOO-WORDPRESS.php       ← Archivo principal del plugin
├── includes/
│   ├── class-api-endpoints.php  ← Registro y lógica de los endpoints
│   └── class-api-auth.php       ← Validación de API Key y whitelist de IPs
└── admin/
    └── settings-page.php        ← Página de configuración en el panel de WP
```

---

## Relación con el otro plugin

Este proyecto usa **dos plugins separados**:

| Plugin | Carpeta | Dirección | Función |
|--------|---------|-----------|---------|
| API ODOO-WORDPRESS | `./API ODOO-WORDPRESS` | Odoo consulta WordPress | Expone endpoints REST para que Odoo lea datos de WP |
| API WORDPRESS-ODOO | `./API WORDPRESS-ODOO` | WordPress recibe de Odoo | Recibe datos de Odoo y los guarda en WP |

Son independientes: se pueden activar, desactivar y actualizar por separado.

---

## Estado actual

- [ ] Estructura de archivos creada
- [ ] Endpoints implementados
- [ ] Autenticación implementada
- [ ] Página de administración
- [ ] Pruebas desde Odoo
