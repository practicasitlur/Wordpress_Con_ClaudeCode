# CLAUDE.md — Proyecto WordPress Locopolo

## Configuración de Entorno
- **local:** true
- **API:** false
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