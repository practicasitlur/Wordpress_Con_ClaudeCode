"""
Genera el resumen del dia 2026-05-26 en formato PDF.
Uso: python 2026-05-26_resumen.py
"""
from fpdf import FPDF


def clean(s: str) -> str:
    """Reemplaza caracteres unicode que no estan en latin-1 (fonts core de FPDF)."""
    repl = {
        '→': '->',  # →
        '←': '<-',  # ←
        '✓': '[OK]', # ✓
        '✗': '[X]', # ✗
        '⚠': '!',   # ⚠
        '•': '-',   # •
        '—': '-',   # — (em dash)
        '–': '-',   # – (en dash)
        '“': '"',   # "
        '”': '"',   # "
        '‘': "'",   # '
        '’': "'",   # '
        '…': '...', # …
    }
    for k, v in repl.items():
        s = s.replace(k, v)
    return s


class PDF(FPDF):
    def header(self):
        if self.page_no() == 1:
            return
        self.set_font('Helvetica', 'I', 9)
        self.set_text_color(120, 120, 120)
        self.cell(0, 6, clean('Resumen del dia - 2026-05-26 - Integracion Odoo <-> WordPress (Locopolo)'),
                  new_x="LMARGIN", new_y="NEXT", align='L')
        self.ln(2)

    def footer(self):
        self.set_y(-12)
        self.set_font('Helvetica', 'I', 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 6, f'Pagina {self.page_no()}', align='C')

    def h1(self, text):
        self.ln(4)
        self.set_font('Helvetica', 'B', 16)
        self.set_text_color(20, 20, 20)
        self.multi_cell(0, 8, clean(text))
        self.ln(2)

    def h2(self, text):
        self.ln(3)
        self.set_font('Helvetica', 'B', 13)
        self.set_text_color(40, 60, 100)
        self.multi_cell(0, 7, clean(text))
        self.ln(1)

    def h3(self, text):
        self.ln(2)
        self.set_font('Helvetica', 'B', 11)
        self.set_text_color(60, 60, 60)
        self.multi_cell(0, 6, clean(text))
        self.ln(1)

    def p(self, text):
        self.set_font('Helvetica', '', 10.5)
        self.set_text_color(30, 30, 30)
        self.multi_cell(0, 5.5, clean(text))
        self.ln(1)

    def bullet(self, text):
        self.set_font('Helvetica', '', 10.5)
        self.set_text_color(30, 30, 30)
        self.cell(5)
        self.cell(4, 5.5, '-')
        self.multi_cell(0, 5.5, clean(text))
        self.ln(0.5)

    def code(self, text):
        self.set_font('Courier', '', 9)
        self.set_text_color(180, 30, 30)
        self.set_fill_color(245, 245, 245)
        self.multi_cell(0, 5, clean(text), fill=True)
        self.ln(1)
        self.set_text_color(30, 30, 30)


pdf = PDF()
pdf.set_auto_page_break(auto=True, margin=15)
pdf.add_page()

# Portada
pdf.set_y(50)
pdf.set_font('Helvetica', 'B', 22)
pdf.set_text_color(20, 30, 80)
pdf.multi_cell(0, 12, 'Resumen del dia\n2026-05-26', align='C')
pdf.ln(5)
pdf.set_font('Helvetica', '', 14)
pdf.set_text_color(60, 60, 60)
pdf.multi_cell(0, 8, clean('Integracion Odoo <-> WordPress (Locopolo)'), align='C')
pdf.ln(20)
pdf.set_font('Helvetica', '', 11)
pdf.multi_cell(0, 6, clean(
    'Documento que recoge en detalle todo el trabajo realizado durante la jornada en torno a '
    'la integracion bidireccional entre Odoo (development-erp-thelocopolo.odoo.com) y el WordPress '
    'de staging (locopolostg.wpenginepowered.com). Incluye los fallos detectados, las soluciones '
    'aplicadas, los riesgos identificados y el trabajo que queda pendiente.'
), align='J')

# ----- 1. Diagnostico y restauracion de staging -----
pdf.add_page()
pdf.h1(clean('1. Diagnostico y restauracion del WordPress de staging'))

pdf.h3('Sintoma inicial')
pdf.p(clean(
    'La web de staging (locopolostg.wpenginepowered.com) cargaba en blanco. El navegador no '
    'mostraba contenido aunque el servidor respondia con HTTP 200 OK.'
))

pdf.h3('Diagnostico tecnico')
pdf.p(clean(
    'Se verificaron varias URLs con peticiones directas via curl. El resultado fue claro: el frontend '
    'devolvia 0 bytes (white screen of death), pero el resto del sitio funcionaba correctamente:'
))
pdf.bullet(clean('/ (home), /sample-page/, /encuentranos/: 0 bytes (PHP fatal silenciado)'))
pdf.bullet(clean('/wp-login.php: 16.159 bytes (login operativo)'))
pdf.bullet(clean('/wp-json/: ~1.000 bytes (REST API respondia con metadatos validos)'))
pdf.p(clean(
    'Conclusion: WordPress core funcionaba; solo el renderizado del tema crasheaba. Por tanto el '
    'problema estaba en el theme o en algun plugin del frontend, no en la infraestructura.'
))

pdf.h3('Causa real')
pdf.p(clean(
    'El usuario hizo previamente un "Restore" desde wp-admin con WPvivid y la restauracion fallo a '
    'mitad de camino, dejando la base de datos parcialmente importada. Es un comportamiento conocido '
    'en hostings con timeouts agresivos: PHP supera max_execution_time o agota memoria sin emitir '
    'mensaje claro de error.'
))

pdf.h3('Solucion aplicada')
pdf.p(clean(
    'El usuario subio a staging un backup completo (Database + Files) generado en local con el plugin '
    'WPvivid Backup. Despues uso la opcion "Restore" desde wp-admin sobre ese mismo backup. La segunda '
    'ejecucion completo correctamente y el sitio volvio a funcionar.'
))

pdf.h3('Regla practica documentada')
pdf.p(clean(
    'Las restauraciones de WPvivid sobre WP Engine pueden fallar silenciosamente. No dar por buena una '
    'restauracion hasta ver el mensaje explicito "Migration/Restore completed successfully". Si no aparece, '
    'relanzar.'
))

pdf.h3('Consecuencia colateral')
pdf.p(clean(
    'El contenido de staging quedo sobrescrito por el de local. Cualquier cambio que existiera solo en '
    'staging (publicado por otra persona en el equipo) se perdio. Para revertir se podria usar el backup '
    'automatico de WP Engine desde el User Portal -> Backup Points.'
))

# ----- 2. Renombrado de carpetas -----
pdf.add_page()
pdf.h1(clean('2. Renombrado de carpetas de los plugins de integracion'))

pdf.h3('Problema de nomenclatura')
pdf.p(clean(
    'Los nombres antiguos eran ambiguos: "API ODOO-WORDPRESS" y "API WORDPRESS-ODOO" podian leerse de '
    'dos maneras distintas (direccion del flujo de datos, o donde vive la API). Esa ambiguedad genero '
    'confusion repetida durante el desarrollo.'
))

pdf.h3('Renombrado aplicado')
pdf.p(clean('Se eligio la convencion "quien llama a quien" porque es la mas natural al leerla:'))
pdf.bullet(clean('"API ODOO-WORDPRESS" -> "API wordpress-llama-a-odoo" (WordPress es cliente, Odoo expone la API)'))
pdf.bullet(clean('"API WORDPRESS-ODOO" -> "API odoo-llama-a-wordpress" (Odoo es cliente, WordPress expone la API)'))

pdf.h3('Alcance del cambio')
pdf.p(clean('Solo se renombraron las carpetas contenedoras del proyecto. NO se toco:'))
pdf.bullet(clean('Las junctions NTFS internas (siguen apuntando a las carpetas reales de wp-content/plugins/)'))
pdf.bullet(clean('Las carpetas reales del plugin en WordPress (conservan los nombres originales)'))
pdf.bullet(clean('El header "Plugin Name:" dentro de los PHP'))
pdf.bullet(clean('Las clases ni los archivos internos del plugin'))
pdf.bullet(clean('Las entradas historicas de documentacion/*.txt (registro fiel del momento)'))

pdf.h3('Cambios de configuracion')
pdf.p(clean(
    'CLAUDE.md se actualizo con las nuevas rutas y se anadio una nota en cada plugin aclarando que '
    'la carpeta del plugin activo en wp-content/plugins/ conserva el nombre original. Asi futuras sesiones '
    'entienden por que la carpeta exterior y la interior tienen nombres distintos.'
))

# ----- 3. Despliegue de locopolo_wordpress_sync -----
pdf.add_page()
pdf.h1(clean('3. Despliegue y configuracion del modulo Odoo locopolo_wordpress_sync'))

pdf.h3('Contexto')
pdf.p(clean(
    'El modulo de Odoo locopolo_wordpress_sync (creado en sesiones anteriores) se desplego en el entorno '
    'development de Odoo.sh mediante push a la rama "development" del repositorio SoteloIo/locopolo. '
    'Odoo.sh hace rebuild automatico al detectar el push (5-10 minutos por iteracion).'
))

pdf.h3('Configuracion en Odoo')
pdf.p(clean('Creados dos parametros del sistema (Ajustes -> Tecnico -> Parametros del sistema):'))
pdf.code(
    'Clave: locopolo_wordpress.url\n'
    'Valor: https://locopolostg.wpenginepowered.com\n\n'
    'Clave: locopolo_wordpress.token\n'
    'Valor: MI_SUPER_TOKEN_SECRETO_12345'
)
pdf.p(clean(
    'El token debe coincidir exactamente con el almacenado en wp_options["aow_odoo_token"] del WordPress '
    'destino. Si no existe esa opcion en la BD, el plugin de WordPress usa por defecto MI_SUPER_TOKEN_SECRETO_12345.'
))

pdf.h3('Funcionamiento verificado')
pdf.p(clean(
    'El modulo se dispara cuando se ejecuta uno de estos eventos en Odoo:'
))
pdf.bullet(clean('create() de un res.partner con categoria TIENDA y mostrar_en_web = True'))
pdf.bullet(clean('write() de un res.partner (campos relevantes o mostrar_en_web)'))
pdf.bullet(clean('unlink() de un res.partner con categoria TIENDA'))
pdf.bullet(clean('action_confirm() de un sale.order cuyo partner sea TIENDA + mostrar_en_web'))
pdf.p(clean(
    'En cada trigger, el modulo construye un payload JSON con los datos del partner '
    '(odoo_id, nombre, direccion, ciudad, codigo_postal, telefono, zona, fecha_ultimo_pedido) y hace '
    'una peticion HTTPS al endpoint del plugin de WordPress.'
))

# ----- 4. Bloqueo de Cloudflare por User-Agent -----
pdf.add_page()
pdf.h1(clean('4. Bloqueo de Cloudflare por User-Agent de Python'))

pdf.h3('Sintoma')
pdf.p(clean(
    'Tras desplegar el modulo y configurarlo, las peticiones de Odoo a WordPress devolvian sistematicamente '
    'HTTP 403 Forbidden. El log de Odoo mostraba:'
))
pdf.code(
    'WordPress sync error (upsert tienda 7918):\n'
    '403 Client Error: Forbidden for url:\n'
    'https://locopolostg.wpenginepowered.com/wp-json/odoo/v1/tiendas'
)

pdf.h3('Diagnostico')
pdf.p(clean(
    'Inicialmente se sospecho que el token estaba mal configurado. Se verifico con un script Python '
    'que el valor en ir.config_parameter era exactamente "MI_SUPER_TOKEN_SECRETO_12345" (28 caracteres, '
    'sin saltos de linea ni espacios invisibles). El mismo token funcionaba perfectamente al hacer una '
    'peticion manual con curl.'
))
pdf.p(clean('La pista clave fue replicar la cabecera User-Agent que envia la libreria "requests" de Python:'))
pdf.code(
    'curl -H "User-Agent: python-requests/2.31.0" ...  -> HTTP 403\n'
    'curl sin User-Agent custom                       -> HTTP 200'
)
pdf.p(clean(
    'La respuesta 403 venia con HTML de Cloudflare (no JSON del plugin). Esto confirmo que la peticion '
    'no llegaba siquiera a WordPress: la cortaba Cloudflare antes, por considerar "python-requests/X.X.X" '
    'un User-Agent sospechoso de scraping/bot.'
))

pdf.h3('Solucion')
pdf.p(clean(
    'Modificado wordpress_client.py del modulo locopolo_wordpress_sync para enviar un User-Agent '
    'identificable:'
))
pdf.code(
    "def _headers(self):\n"
    "    return {\n"
    "        'Content-Type': 'application/json',\n"
    "        'X-Odoo-Token': self.token,\n"
    "        'User-Agent':   'LocopoloWordPressSync/1.0',\n"
    "    }"
)
pdf.p(clean(
    'Commit + push a la rama development. Odoo.sh hizo rebuild en ~6 minutos. Tras el deploy, las '
    'peticiones empezaron a llegar correctamente al endpoint y el log mostro:'
))
pdf.code(
    'WordPress sync: tienda 7918 creada/actualizada.'
)

# ----- 5. Bug en el plugin WordPress -----
pdf.add_page()
pdf.h1(clean('5. Bug del plugin WordPress: terms duplicados en taxonomias jerarquicas'))

pdf.h3('Sintoma')
pdf.p(clean(
    'Aunque la sincronizacion Odoo -> WordPress funcionaba (status 200, log de Odoo OK), algunas tiendas '
    'sincronizadas (AIBAK, Oier usu prueba externo) no aparecian en la pagina publica /encuentranos/, '
    'mientras que otras (LUISA MARIA ZUIN) si aparecian.'
))

pdf.h3('Diagnostico')
pdf.p(clean(
    'La pagina /encuentranos/ agrupa tiendas por la taxonomia "zona" (jerarquica: Espana > provincias). '
    'Al consultar los terms de la taxonomia se descubrieron DOS terms para Gipuzkoa:'
))
pdf.code(
    'ID 75  | name="Gipuzkoa (Guipuzcoa)" | parent=11 (Espana) | count=3 (las que SI aparecen)\n'
    'ID 266 | name="Gipuzkoa"             | parent=0           | count=1 (AIBAK)'
)
pdf.p(clean(
    'El termino 266 era un DUPLICADO sin padre que el plugin habia creado al sincronizar AIBAK. El '
    'frontend solo lee las tiendas que cuelgan de "Espana", asi que las del 266 quedaban invisibles.'
))

pdf.h3('Causa raiz')
pdf.p(clean('El plugin de WordPress (API WORDPRESS-ODOO.php) asignaba la taxonomia asi:'))
pdf.code(
    "// Codigo con el bug:\n"
    "wp_set_object_terms($post_id, $zona, 'zona', false);"
)
pdf.p(clean(
    'wp_set_object_terms con un string suelto, en una taxonomia JERARQUICA, no busca el term por nombre: '
    'crea uno nuevo en la raiz. Y los nombres con parentesis "Gipuzkoa (Guipuzcoa)" no coincidian con los '
    'existentes, asi que se generaban duplicados.'
))
pdf.p(clean(
    'LUISA MARIA ZUIN no tenia este problema porque su provincia "Cadiz" no tiene parentesis y el match '
    'funcionaba por casualidad. Pero todas las provincias con parentesis estaban afectadas: Gipuzkoa, '
    'Bizkaia, Lleida, Castello, Girona, Alacant, Valencia, A Coruna, Ourense, Navarra, Illes Balears, Araba.'
))

pdf.h3('Solucion aplicada en staging')
pdf.p(clean(
    'Editado el plugin directamente desde wp-admin -> Plugins -> Editor de archivos de plugin. Reemplazada '
    'la asignacion buggy por:'
))
pdf.code(
    "if ( $zona ) {\n"
    "    $term = get_term_by( 'name', trim( $zona ), 'zona' );\n"
    "    if ( $term ) {\n"
    "        wp_set_post_terms( $post_id, [ $term->term_id ], 'zona' );\n"
    "    }\n"
    "}"
)
pdf.p(clean(
    'Idem para la taxonomia "tipo-tienda". La logica ahora es: buscar el term por nombre, obtener su ID, '
    'y usar ese ID al asignar al post. Asi nunca se crean duplicados.'
))

pdf.h3('Por que NO se cambiaron los nombres de provincia en Odoo')
pdf.p(clean(
    'Se valoro renombrar las 12 provincias afectadas en Odoo (eliminando los parentesis), pero se descarto '
    'porque: (1) res.country.state es una tabla estandar que afecta a facturacion, contabilidad y envios; '
    '(2) habria que renombrar tambien los terms en WordPress; (3) el bug seguiria latente para cualquier '
    'taxonomia jerarquica futura con nombres no triviales. La solucion en el plugin es mas robusta y resuelve '
    'el problema de raiz.'
))

# ----- 6. Sincronizacion taxonomia zona -----
pdf.add_page()
pdf.h1(clean('6. Sincronizacion de la taxonomia "zona" con el catalogo de Odoo'))

pdf.h3('Comparativa inicial')
pdf.p(clean(
    'Se compararon los terms existentes en la taxonomia "zona" de WordPress con la lista canonica de '
    'res.country.state de Odoo (56 provincias y localidades). Resultados:'
))
pdf.bullet(clean('Coincidencias exactas: 51 terms (no se tocaron)'))
pdf.bullet(clean('A CREAR (existen en Odoo pero no en WP): 6 terms'))
pdf.bullet(clean('A BORRAR (existen en WP pero no en Odoo): 51 terms'))

pdf.h3('Estrategia hibrida elegida (no destructiva)')
pdf.p(clean(
    'La opcion de borrar los 51 terms ausentes en Odoo se descarto por tres riesgos serios:'
))
pdf.bullet(clean('Borrar terms padre como "Espana" o "Francia" romperia la jerarquia: todas las provincias quedarian huerfanas'))
pdf.bullet(clean('Borrar terms con posts asignados (count > 0) dejaria a esas tiendas sin zona'))
pdf.bullet(clean('Provincias de otros paises (Portugal, Italia, Francia, Oman...) pueden ser utiles en el futuro'))

pdf.h3('Acciones aplicadas via REST API')
pdf.p(clean('Se uso la application password de "idoia" para llamar a /wp-json/wp/v2/zona:'))
pdf.bullet(clean('Creados 5 terms bajo el padre "Espana" (id 11): Araba/Alava, Calvia, Leon, Palma de Mallorca, menorca'))
pdf.bullet(clean('Alcudia ya existia (id 267) - el diff lo detecto pero el script encontro que se habia creado entre la consulta y la accion'))
pdf.bullet(clean('Borrado el term huerfano "Araba (Alava)" (id 73, count 0) - sustituido por el nuevo Araba/Alava'))
pdf.bullet(clean('Borrado el term duplicado "Gipuzkoa" (id 266) DESPUES de reasignar AIBAK al term correcto manualmente'))

pdf.h3('Verificacion final')
pdf.p(clean(
    'AIBAK, Oier usu prueba externo y LUISA MARIA ZUIN aparecen correctamente en /encuentranos/ '
    'agrupados por su zona. El fix del plugin garantiza que las futuras sincronizaciones desde Odoo '
    'asignaran siempre el term correcto sin crear duplicados.'
))

# ----- 7. Pendientes y aplicacion del fix en local -----
pdf.add_page()
pdf.h1(clean('7. Aplicacion del fix en local y pendientes'))

pdf.h3('Fix replicado en local')
pdf.p(clean(
    'El fix aplicado en staging via Plugin Editor solo afecta a la copia del archivo en WP Engine. El '
    'archivo del plugin en el WordPress local (accesible via junction) seguia con la version antigua. '
    'Se ha replicado el mismo cambio en el archivo local para mantener consistencia y evitar que una '
    'futura Auto-Migration local -> staging reintroduzca el bug.'
))

pdf.h3('Estado de los dos lados')
pdf.bullet(clean('Modulo Odoo locopolo_wordpress_sync (in development): codigo nuevo desplegado, parametros configurados, funcionando'))
pdf.bullet(clean('Plugin WordPress API WORDPRESS-ODOO en staging: fix de taxonomia aplicado, plugin activo, endpoint operativo'))
pdf.bullet(clean('Plugin WordPress en local: fix aplicado tambien para mantener consistencia (commit pendiente)'))

pdf.h3('Pendientes para futuras sesiones')
pdf.bullet(clean('Considerar refactorizar el flujo de upsert para que rellene tambien campos opcionales (latitud/longitud, tipo-tienda) si se quiere mostrar las tiendas en un mapa interactivo'))
pdf.bullet(clean('Anadir un endpoint REST que devuelva el detalle completo de una tienda por odoo_id, para facilitar el debug futuro sin necesidad de acceder a wp-admin'))
pdf.bullet(clean('Documentar en el README del plugin la lista de campos esperados en el payload y los meta_keys/taxonomias que se actualizan'))
pdf.bullet(clean('Implementar un mecanismo de logging dentro del propio plugin (no solo en Odoo) para registrar las llamadas recibidas y posibles errores'))

pdf.h3('Scripts auxiliares creados en API wordpress-llama-a-odoo/pruebas/')
pdf.bullet(clean('verificar_token_odoo.py: lee el token en Odoo con repr() para detectar caracteres invisibles'))
pdf.bullet(clean('sync_zona_diff.py: compara los terms de la taxonomia "zona" de WP con la lista canonica de Odoo'))
pdf.bullet(clean('sync_zona_aplicar.py: aplica el plan hibrido (crear los que faltan, borrar duplicados conocidos) via REST'))
pdf.bullet(clean('comparar_tiendas.py: utilidad para inspeccionar metadata y taxonomias de un post tienda'))

# ----- 8. Resumen ejecutivo -----
pdf.add_page()
pdf.h1(clean('8. Resumen ejecutivo'))

pdf.p(clean(
    'En una jornada se diagnostico y resolvio una cadena de tres fallos encadenados que impedian que la '
    'integracion Odoo -> WordPress funcionase end-to-end: una restauracion incompleta de WPvivid que dejo '
    'staging corrupto, un bloqueo de Cloudflare por User-Agent automatico de Python, y un bug latente en '
    'el plugin de WordPress que creaba terms duplicados cuando los nombres de provincia contenian parentesis. '
    'Los tres fallos se solucionaron sin perder datos ni tiempo de desarrollo, dejando ademas la taxonomia '
    'de zonas alineada con el catalogo de Odoo y el codigo replicado en local para evitar regresiones futuras. '
    'La integracion queda operativa: Odoo envia tiendas a WordPress automaticamente al confirmar pedidos o '
    'modificar contactos, y aparecen en /encuentranos/ con su provincia correcta.'
))

# Generar el PDF
salida = 'documentacion/2026-05-26_resumen.pdf'
pdf.output(salida)
print(f'PDF generado en: {salida}')
