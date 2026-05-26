"""
Test: obtener el último pedido de una tienda en Odoo por su partner_id.
Uso: python test_ultimo_pedido.py
"""

import os
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
            "params": {"service": service, "method": method, "args": list(args)}
        },
        timeout=10
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


def buscar_partner_por_nombre(uid, nombre):
    """Busca un partner por nombre parcial — útil para pruebas."""
    partners = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "res.partner", "search_read",
        [[["name", "ilike", nombre]]],
        {"fields": ["id", "name", "email"], "limit": 5}
    )
    return partners


def ultimo_pedido(uid, partner_id):
    """Devuelve la fecha del último pedido confirmado de un partner."""
    pedidos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "search_read",
        [[
            ["partner_id", "=", partner_id],
            ["state", "in", ["sale", "done"]]  # solo pedidos confirmados
        ]],
        {
            "fields": ["name", "date_order", "state", "partner_id"],
            "limit": 1,
            "order": "date_order desc"
        }
    )
    return pedidos[0] if pedidos else None


if __name__ == "__main__":
    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}\n")

    # --- PASO 1: buscar un partner real para hacer la prueba ---
    nombre_busqueda = "Locopolo"  # Cambia esto por el nombre de una tienda real
    print(f"Buscando partners con nombre '{nombre_busqueda}'...")
    partners = buscar_partner_por_nombre(uid, nombre_busqueda)

    if not partners:
        print("No se encontraron partners con ese nombre.")
        print("Prueba cambiando 'nombre_busqueda' por el nombre de una tienda real en Odoo.")
    else:
        print(f"Partners encontrados:")
        for p in partners:
            print(f"  ID: {p['id']}  |  Nombre: {p['name']}  |  Email: {p.get('email') or '-'}")

        # --- PASO 2: buscar el último pedido del primer resultado ---
        partner = partners[0]
        print(f"\nBuscando último pedido del partner ID {partner['id']} ({partner['name']})...")
        pedido = ultimo_pedido(uid, partner['id'])

        if pedido:
            print(f"  Último pedido: {pedido['name']}")
            print(f"  Fecha:         {pedido['date_order']}")
            print(f"  Estado:        {pedido['state']}")
        else:
            print("  Este partner no tiene pedidos confirmados.")
