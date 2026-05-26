"""
Elimina el ultimo pedido confirmado en Odoo de LUISA MARIA ZUIN (partner_id 7918).
Sirve para revertir el pedido creado por crear_pedido_prueba.py.

Flujo: action_unlock -> action_cancel -> write state=draft -> unlink.
Las primeras llamadas se hacen en try/except porque pueden no ser necesarias.

Uso: python eliminar_pedido_prueba.py
"""

import os
import requests
from dotenv import load_dotenv

load_dotenv(os.path.join(os.path.dirname(__file__), '..', '.env'))

URL      = os.getenv('ODOO_URL')
DB       = os.getenv('ODOO_DB')
USERNAME = os.getenv('ODOO_USERNAME')
API_KEY  = os.getenv('ODOO_API_KEY')

PARTNER_ID = 7918  # LUISA MARIA ZUIN


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


def ejecutar(uid, model, method, ids, kwargs=None):
    args = [ids]
    if kwargs is None:
        return odoo_call("object", "execute_kw", DB, uid, API_KEY, model, method, args)
    return odoo_call("object", "execute_kw", DB, uid, API_KEY, model, method, args, kwargs)


if __name__ == "__main__":
    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}\n")

    print(f"Buscando el ultimo pedido confirmado del partner {PARTNER_ID}...")
    pedidos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "search_read",
        [[
            ["partner_id", "=", PARTNER_ID],
            ["state", "in", ["sale", "done"]],
        ]],
        {"fields": ["id", "name", "date_order", "state"], "limit": 1, "order": "date_order desc"},
    )

    if not pedidos:
        raise SystemExit("No hay pedidos confirmados para ese partner. Nada que eliminar.")

    pedido = pedidos[0]
    order_id = pedido["id"]
    print(f"  Pedido: {pedido['name']}  ID: {order_id}  Fecha: {pedido['date_order']}  Estado: {pedido['state']}\n")

    print("Paso 1: action_unlock (por si esta bloqueado)...")
    try:
        odoo_call("object", "execute_kw", DB, uid, API_KEY,
                  "sale.order", "action_unlock", [[order_id]])
        print("  Desbloqueado.")
    except Exception as e:
        print(f"  No hace falta o no aplica: {e}")

    print("Paso 2: action_cancel (cancelar el pedido)...")
    try:
        odoo_call("object", "execute_kw", DB, uid, API_KEY,
                  "sale.order", "_action_cancel", [[order_id]])
        print("  Cancelado.")
    except Exception:
        try:
            odoo_call("object", "execute_kw", DB, uid, API_KEY,
                      "sale.order", "action_cancel", [[order_id]])
            print("  Cancelado.")
        except Exception as e:
            print(f"  Aviso: {e}")

    print("Paso 3: forzar state=draft (algunas configuraciones lo requieren para unlink)...")
    try:
        odoo_call("object", "execute_kw", DB, uid, API_KEY,
                  "sale.order", "write", [[order_id], {"state": "draft"}])
        print("  Estado forzado a draft.")
    except Exception as e:
        print(f"  Aviso: {e}")

    print("Paso 4: unlink (eliminar definitivamente)...")
    odoo_call("object", "execute_kw", DB, uid, API_KEY,
              "sale.order", "unlink", [[order_id]])
    print("  Pedido eliminado.\n")

    print("Verificando estado del partner tras la eliminacion...")
    restantes = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "search_read",
        [[
            ["partner_id", "=", PARTNER_ID],
            ["state", "in", ["sale", "done"]],
        ]],
        {"fields": ["name", "date_order"], "limit": 1, "order": "date_order desc"},
    )
    if restantes:
        p = restantes[0]
        print(f"  Ultimo pedido confirmado restante: {p['name']}  |  Fecha: {p['date_order']}")
    else:
        print("  El partner ya no tiene pedidos confirmados.")

    print(f"\nListo. Si vuelves a darle a 'Importar tiendas desde Odoo', LUISA MARIA ZUIN deberia desaparecer.")
