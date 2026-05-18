# CLAUDE.md — Proyecto WordPress Locopolo

## Datos del sitio

- **URL:** https://locopolostg.wpenginepowered.com/
- **Usuario:** idoia
- **Contraseña de aplicación:** guardada en `wordpress-sites.json`
- **Tema activo:** locopolo-understrap-child
- **Constructor:** Gutenberg
- **API:** WordPress REST API (`/wp-json/wp/v2/`)

---

## Reglas de documentación (OBLIGATORIO)

Solo documentar cuando se haya realizado un cambio real en el sitio WordPress. No registrar preguntas genéricas, dudas, consultas informativas ni conversaciones que no hayan modificado nada en WordPress.

Cuando sí haya un cambio que documentar:

1. **Crear la carpeta `documentacion/`** en la raíz del proyecto si no existe.
2. **Crear un archivo `.txt` con la fecha del día** (formato `YYYY-MM-DD.txt`) dentro de `documentacion/`. Si ya existe el archivo de ese día, añadir al final sin borrar lo anterior.
3. **Cada entrada en el archivo debe tener esta estructura:**

```
========================================
1. FECHA Y HORA: YYYY-MM-DD HH:MM  ← fecha y hora exacta en que se ejecutó el cambio
2. PREGUNTA / ORDEN RECIBIDA:
   [texto exacto o resumen fiel de lo que pidió el usuario]
3. CAMBIOS REALIZADOS:
   [descripción detallada de cada cambio hecho en WordPress]
========================================
```

> La hora debe ser la hora real del momento en que se ejecuta el cambio, en la zona horaria de España (Europe/Madrid). Para obtenerla usar: `[System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')`

---

## Reglas de trabajo con WordPress (OBLIGATORIO)

1. **Consultar antes de actuar.** Antes de realizar cualquier cambio en el sitio WordPress, explicar qué se va a hacer y esperar confirmación explícita del usuario.
2. **Alcance estricto.** No hacer ningún cambio que no haya sido pedido explícitamente. Si al investigar se detecta otro problema o mejora posible, mencionarlo pero no tocarlo sin permiso.
3. **Cambios reversibles siempre que sea posible.** Preferir mover widgets a inactivos antes que borrar, desactivar antes que eliminar, etc.
4. **Informar del resultado.** Tras cada cambio, confirmar que se ha aplicado correctamente y describir brevemente qué se modificó.
5. **No tocar plugins, temas ni ajustes globales** salvo que se pida expresamente.
6. **Un cambio a la vez.** Si una orden implica varios cambios, listarlos todos primero y pedir confirmación antes de ejecutar cada uno o el conjunto.
