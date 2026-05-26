"""
Explorador interactivo de modelos de Odoo.

Por defecto muestra SOLO los modelos relevantes para el modulo
locopolo_wordpress_sync (whitelist abajo). Modos adicionales:
  business  -> oculta wizards/reports/internals pero deja muchos modelos
  all       -> muestra todos (1.000+ modelos)

Comandos dentro del prompt:
    <nombre.del.modelo>   -> muestra los campos de ese modelo
    filtro <texto>        -> re-lista modelos cuyo nombre contiene <texto>
    modulo                -> vuelve al modo whitelist (solo modelos del modulo)
    business              -> modo intermedio (oculta ruido pero incluye otros)
    all                   -> incluye TODOS los modelos
    exit / salir / Enter  -> sale

Uso:
    python listar_modelos.py
    python listar_modelos.py sale       (filtro inicial)
"""

# Whitelist: modelos directamente relevantes para locopolo_wordpress_sync.
MODELOS_DEL_MODULO = (
    "res.partner",            # contacto / tienda
    "res.partner.category",   # etiquetas (donde vive la categoria TIENDA)
    "res.country",            # pais (usado al normalizar la zona)
    "res.country.state",      # provincia (de aqui sale el campo "zona")
    "sale.order",             # presupuesto / pedido (action_confirm)
    "sale.order.line",        # lineas del pedido (al crearlo)
    "product.product",        # producto (variante)
    "product.template",       # producto (plantilla)
    "ir.config_parameter",    # donde guardamos URL y token de WordPress
)

# Prefijos/substrings que consideramos "ruido" y se ocultan por defecto.
PREFIJOS_RUIDO = (
    "_",
    "ir.",
    "bus.",
    "web.",
    "base_import.",
    "base.module.",
    "base.language.",
    "base.partner.",
    "mail.activity",
    "mail.alias",
    "mail.blacklist",
    "mail.canned",
    "mail.compose",
    "mail.followers",
    "mail.gateway",
    "mail.guest",
    "mail.ice",
    "mail.link",
    "mail.mail",
    "mail.message",
    "mail.notification",
    "mail.push",
    "mail.render",
    "mail.resend",
    "mail.scheduled",
    "mail.shortcode",
    "mail.template",
    "mail.thread",
    "mail.tracking",
    "mail.wizard",
    "mailing.",
    "snailmail.",
    "spreadsheet.",
    "iap.",
    "auth.",
    "digest.",
    "decimal.precision",
    "format.",
    "im_livechat.",
    "discuss.",
    "rating.",
    "documents.",
    "knowledge.",
    "social.",
    "sign.",
    "studio.",
    "website.",
    "theme.",
    "portal.",
    "html_editor.",
    "loyalty.history",
    "barcodes.",
    "phone.",
    "onboarding.",
    "score.",
)

SUBSTRINGS_RUIDO = (
    ".wizard",
    ".report.handler",
    ".config.settings",
    ".configurator",
    "import.summary",
    ".tour",
    ".access",
    ".group",
    ".rule",
    ".cron",
    ".sequence",
    ".attachment",
    ".cache",
    ".gauge",
    ".update",
    ".log.report",
    ".mixin",
)


def es_modelo_negocio(modelo_record):
    """Decide si un modelo es 'de negocio' (queremos mostrarlo) o ruido (ocultarlo)."""
    name = modelo_record["model"]
    if modelo_record.get("transient"):
        return False
    if any(name.startswith(p) for p in PREFIJOS_RUIDO):
        return False
    if any(s in name for s in SUBSTRINGS_RUIDO):
        return False
    return True

import os
import sys
import requests
from dotenv import load_dotenv

load_dotenv(os.path.join(os.path.dirname(__file__), '..', '.env'))

URL      = os.getenv('ODOO_URL')
DB       = os.getenv('ODOO_DB')
USERNAME = os.getenv('ODOO_USERNAME')
API_KEY  = os.getenv('ODOO_API_KEY')


def odoo_call(service, method, *args):
    response = requests.post(
        f"{URL}/jsonrpc",
        json={
            "jsonrpc": "2.0",
            "method": "call",
            "id": 1,
            "params": {"service": service, "method": method, "args": list(args)},
        },
        timeout=30,
    )
    result = response.json()
    if "error" in result:
        raise Exception(result['error']['data']['message'])
    return result["result"]


def autenticar():
    uid = odoo_call("common", "authenticate", DB, USERNAME, API_KEY, {})
    if not uid:
        raise Exception("Credenciales incorrectas")
    return uid


def listar_modelos(uid, filtro=None):
    domain = []
    if filtro:
        domain = [["model", "ilike", filtro]]
    return odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "ir.model", "search_read",
        [domain],
        {"fields": ["model", "name", "transient"], "order": "model"},
    )


def mostrar_lista_modelos(modelos, filtro, modo):
    cabecera = f"\nTotal modelos ({modo}"
    if filtro:
        cabecera += f", filtro: '{filtro}'"
    cabecera += f"): {len(modelos)}\n"
    print(cabecera)
    print(f"{'NOMBRE TECNICO':<50} NOMBRE HUMANO")
    print("-" * 100)
    for m in modelos:
        nombre = (m.get("name") or "")[:48]
        print(f"{m['model']:<50} {nombre}")


def listar_campos(uid, modelo):
    return odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "ir.model.fields", "search_read",
        [[["model", "=", modelo]]],
        {
            "fields": ["name", "ttype", "field_description", "required", "relation"],
            "order": "name",
        },
    )


def mostrar_campos(modelo, campos):
    if not campos:
        print(f"\nEl modelo '{modelo}' no existe o no tiene campos visibles.")
        return
    print(f"\nCampos del modelo '{modelo}' (total: {len(campos)})\n")
    print(f"{'CAMPO':<35} {'TIPO':<15} {'REQ':<5} {'RELACION':<30} DESCRIPCION")
    print("-" * 130)
    for c in campos:
        relacion = c.get("relation") or ""
        req = "si" if c.get("required") else "no"
        descripcion = (c.get("field_description") or "")[:40]
        print(f"{c['name']:<35} {c['ttype']:<15} {req:<5} {relacion:<30} {descripcion}")


if __name__ == "__main__":
    filtro_inicial = sys.argv[1] if len(sys.argv) > 1 else None

    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}")

    # Modos posibles: "modulo" (whitelist), "business" (sin ruido), "all" (todos).
    modo = "modulo"

    def refrescar(filtro):
        if modo == "modulo":
            todos = listar_modelos(uid, filtro)
            return [m for m in todos if m["model"] in MODELOS_DEL_MODULO]
        if modo == "business":
            todos = listar_modelos(uid, filtro)
            return [m for m in todos if es_modelo_negocio(m)]
        # "all"
        return listar_modelos(uid, filtro)

    filtro_actual = filtro_inicial
    modelos = refrescar(filtro_actual)
    mostrar_lista_modelos(modelos, filtro_actual, modo)
    nombres_tecnicos = {m["model"] for m in modelos}

    print("\n" + "=" * 100)
    print("Escribe un NOMBRE TECNICO para ver sus campos. Otros comandos:")
    print("  filtro <texto>  | re-lista filtrando por substring")
    print("  modulo          | solo los modelos relevantes para locopolo_wordpress_sync (defecto)")
    print("  business        | quita wizards/reports/internals pero deja muchos modelos")
    print("  all             | incluye TODOS los modelos (1.000+)")
    print("  exit | salir    | terminar")
    print("=" * 100)

    while True:
        try:
            entrada = input("\n> ").strip()
        except (EOFError, KeyboardInterrupt):
            print()
            break

        if not entrada or entrada.lower() in ("exit", "salir", "quit", "q"):
            print("Saliendo.")
            break

        if entrada.lower().startswith("filtro "):
            filtro_actual = entrada[len("filtro "):].strip() or None
            modelos = refrescar(filtro_actual)
            mostrar_lista_modelos(modelos, filtro_actual, modo)
            nombres_tecnicos = {m["model"] for m in modelos}
            continue

        if entrada.lower() in ("modulo", "business", "all"):
            modo = entrada.lower()
            modelos = refrescar(filtro_actual)
            mostrar_lista_modelos(modelos, filtro_actual, modo)
            nombres_tecnicos = {m["model"] for m in modelos}
            continue

        # Aviso si el modelo no aparecia en la lista pero lo consultamos igual
        if entrada not in nombres_tecnicos:
            print(f"  (Aviso: '{entrada}' no esta en la lista actual. Lo consulto igualmente.)")

        campos = listar_campos(uid, entrada)
        mostrar_campos(entrada, campos)
