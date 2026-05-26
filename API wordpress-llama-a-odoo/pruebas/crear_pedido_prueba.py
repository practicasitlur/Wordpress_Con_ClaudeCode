"""
Crea un pedido de prueba en Odoo para LUISA MARIA ZUIN (partner_id 7918) y lo confirma.
El objetivo es activar el flag "ultimo pedido reciente" para que el import desde WordPress la incluya.

Uso: python crear_pedido_prueba.py
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


def buscar_producto_vendible(uid):
    productos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "product.product", "search_read",
        [[["sale_ok", "=", True], ["active", "=", True]]],
        {"fields": ["id", "name", "list_price"], "limit": 5},
    )
    return productos


if __name__ == "__main__":
    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}\n")

    print("Buscando un producto vendible...")
    productos = buscar_producto_vendible(uid)
    if not productos:
        raise SystemExit("No hay productos vendibles disponibles.")

    producto = productos[0]
    print(f"  Usaremos producto ID {producto['id']}  ({producto['name']})  precio: {producto['list_price']}\n")

    print(f"Creando pedido para partner_id={PARTNER_ID}...")
    order_id = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "create",
        [{
            "partner_id": PARTNER_ID,
            "order_line": [(0, 0, {
                "product_id": producto["id"],
                "product_uom_qty": 1,
            })],
        }],
    )
    print(f"  Pedido creado con ID: {order_id}\n")

    print("Confirmando pedido (action_confirm)...")
    odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "action_confirm",
        [[order_id]],
    )
    print("  Pedido confirmado.\n")

    print("Verificando el ultimo pedido del partner...")
    pedidos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "search_read",
        [[
            ["partner_id", "=", PARTNER_ID],
            ["state", "in", ["sale", "done"]],
        ]],
        {"fields": ["name", "date_order", "state"], "limit": 1, "order": "date_order desc"},
    )
    if pedidos:
        p = pedidos[0]
        print(f"  Ultimo pedido: {p['name']}  |  Fecha: {p['date_order']}  |  Estado: {p['state']}")
    else:
        print("  No se encontro ningun pedido confirmado (esto seria un fallo).")

    print(f"\nListo. Ahora puedes ir al panel Sync Odoo en WordPress y darle a 'Importar tiendas desde Odoo'.")
    print(f"LUISA MARIA ZUIN (partner_id {PARTNER_ID}) deberia aparecer importada.")
