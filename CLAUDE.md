# CLAUDE.md — Proyecto WordPress Locopolo

## Configuración de Entorno
- **local:** true
- **API CLAUDE-WORDPRESS:** false
- **Ruta del acceso directo en el proyecto:** `./tema-local`
- **Ruta real del servidor local:** `C:\Users\usuario\Local Sites\locopolo-local\app\public\wp-content\themes\locopolo-understrap-child`

## Reglas de Entorno (OBLIGATORIO)
- Como `local` es `true`, debes realizar todas las modificaciones de código directamente dentro de la carpeta `./tema-local` de este espacio de trabajo. Cualquier archivo PHP, CSS o JS que crees o edites ahí dentro impactará directamente en la web local.

## Datos del sitio
- **URL Producción / API:** https://locopolostg.wpenginepowered.com/
- **Usuario API:** idoia
- **Contraseña de aplicación:** guardada en `wordpress-sites.json`
- **Tema activo:** locopolo-understrap-child
- **Constructor:** Gutenberg
- **API:** WordPress REST API (`/wp-json/wp/v2/`)

## Reglas de Entorno (OBLIGATORIO)
- **Si `local` es `true`:** Tienes prohibido usar la API REST. Debes realizar todas las modificaciones operando directamente sobre los archivos físicos del tema en el directorio actual (leyendo, creando y modificando archivos PHP, CSS, JS, etc.) usando comandos de terminal.
- **Si `API` es `true`:** Utiliza la WordPress REST API y la configuración de `wordpress-sites.json` para realizar los cambios remotamente.

---

## Arquitectura de integración Odoo ↔ WordPress

La integración usa **dos plugins independientes**, cada uno con una dirección de flujo distinta.

---

### Plugin 1 — `API odoo-llama-a-wordpress` (Odoo llama a WordPress)

**Flujo:** Odoo llama a WordPress. WordPress es el servidor que expone endpoints.

- **Carpeta del proyecto:** `./API odoo-llama-a-wordpress/`
- **Junction al plugin activo:** `./API odoo-llama-a-wordpress/conexion-api-wordpress-odoo/` → `wp-content/plugins/API WORDPRESS-ODOO`
- **Carpeta de pruebas:** `./API odoo-llama-a-wordpress/pruebas/`
- **Documentación:** `./API odoo-llama-a-wordpress/proyecto-integracion-odoo-wp.md`

Expone endpoints REST en WordPress para que Odoo pueda enviar o consultar datos.

> Nota: la carpeta del plugin activo en `wp-content/plugins/` y los nombres internos (clases, archivos PHP) conservan el nombre original `API WORDPRESS-ODOO`. Solo se ha renombrado la carpeta contenedora del proyecto para que el flujo quede claro al leerlo.

---

### Plugin 2 — `API wordpress-llama-a-odoo` (WordPress llama a Odoo)

**Flujo:** WordPress llama a Odoo. Odoo es el servidor que expone endpoints.

- **Carpeta del proyecto:** `./API wordpress-llama-a-odoo/`
- **Junction al plugin activo:** `./API wordpress-llama-a-odoo/conexion-api-odoo-wordpress/` → `wp-content/plugins/API ODOO-WORDPRESS`
- **Carpeta de pruebas:** `./API wordpress-llama-a-odoo/pruebas/`
- **Documentación:** `./API wordpress-llama-a-odoo/proyecto-integracion-wordpress-odoo.md`

WordPress actúa como cliente: hace peticiones a la API de Odoo y actúa en función de la respuesta. Autenticación por API Key compartida entre ambos sistemas.

> Nota: la carpeta del plugin activo en `wp-content/plugins/` y los nombres internos conservan el nombre original `API ODOO-WORDPRESS`. Solo se ha renombrado la carpeta contenedora del proyecto.

---

> Para trabajar en cualquiera de los dos plugins, editar únicamente dentro de su carpeta correspondiente. No tocar archivos del tema hijo para lógica de APIs.

---

## Reglas de documentación (OBLIGATORIO)
Solo documentar cuando se haya realizado un cambio real en el sitio WordPress o en la configuración del proyecto. No registrar preguntas genéricas ni dudas.

Cuando sí haya un cambio que documentar:
1. **Crear la carpeta `documentacion/`** en la raíz del proyecto si no existe.
2. **Crear un archivo `.txt` con la fecha del día** (formato `YYYY-MM-DD.txt`) dentro de `documentacion/`. Si ya existe el archivo de ese día, añadir al final sin borrar lo anterior.
3. **Cada entrada en el archivo debe tener esta estructura:**

========================================
1. FECHA Y HORA: YYYY-MM-DD HH:MM  ← fecha y hora exacta en que se ejecutó el cambio
2. PREGUNTA / ORDEN RECIBIDA:
   [texto exacto o resumen fiel de lo que pidió el usuario]
3. CAMBIOS REALIZADOS:
   [descripción detallada de cada cambio hecho en WordPress o en el código]
========================================

> La hora debe ser la hora real del momento en que se ejecuta el cambio, en la zona horaria de España (Europe/Madrid). Para obtenerla usar: `[System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')`

---

## Reglas de trabajo con WordPress (OBLIGATORIO)
1. **Consultar antes de actuar.** Antes de realizar cualquier cambio, explicar qué se va a hacer y esperar confirmación explícita del usuario.
2. **Alcance estricto.** No hacer ningún cambio que no haya sido pedido explícitamente.
3. **Cambios reversibles siempre que sea posible.** Preferir comentar código antes que borrarlo, mover widgets a inactivos, etc.
4. **Informar del resultado.** Tras cada cambio, confirmar que se ha aplicado correctamente y describir brevemente qué se modificó.
5. **No tocar plugins, temas padres ni ajustes globales** salvo que se pida expresamente (trabajar siempre en el Child Theme).
6. **Un cambio a la vez.** Si una orden implica varios cambios, listarlos todos primero y pedir confirmación antes de ejecutar cada uno o el conjunto.