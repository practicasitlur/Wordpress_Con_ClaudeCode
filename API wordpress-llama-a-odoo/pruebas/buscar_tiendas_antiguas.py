"""
Busca tiendas (partners con categoria TIENDA) cuyo ultimo pedido sea de hace mas de 1 ano,
o que no tengan pedidos. Lista las primeras 10 candidatas para crear un pedido de prueba.

Uso: python buscar_tiendas_antiguas.py
"""

import os
import requests
from datetime import datetime, timedelta
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


def listar_tiendas(uid):
    return odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "res.partner", "search_read",
        [[["category_id.name", "=", "TIENDA"]]],
        {"fields": ["id", "name", "city", "state_id"]},
    )


def ultimo_pedido(uid, partner_id):
    pedidos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "sale.order", "search_read",
        [[
            ["partner_id", "=", partner_id],
            ["state", "in", ["sale", "done"]],
        ]],
        {"fields": ["name", "date_order"], "limit": 1, "order": "date_order desc"},
    )
    return pedidos[0] if pedidos else None


if __name__ == "__main__":
    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}\n")

    print("Listando tiendas con categoria TIENDA...")
    tiendas = listar_tiendas(uid)
    print(f"Total tiendas: {len(tiendas)}\n")

    un_anno_atras = datetime.now() - timedelta(days=365)

    candidatas = []
    print(f"{'ID':<6} {'NOMBRE':<40} {'ULTIMO PEDIDO':<25} {'ESTADO'}")
    print("-" * 100)
    for t in tiendas:
        pedido = ultimo_pedido(uid, t["id"])
        if not pedido:
            estado = "SIN PEDIDOS"
            fecha_str = "-"
            candidatas.append((t, None))
        else:
            fecha = datetime.strptime(pedido["date_order"], "%Y-%m-%d %H:%M:%S")
            fecha_str = fecha.strftime("%Y-%m-%d")
            if fecha < un_anno_atras:
                estado = ">1 ANO"
                candidatas.append((t, pedido))
            else:
                estado = "reciente"
        print(f"{t['id']:<6} {t['name'][:38]:<40} {fecha_str:<25} {estado}")

    print()
    print(f"Candidatas (sin pedidos o > 1 ano): {len(candidatas)}")
    if candidatas:
        print("\nLista de candidatas para crear pedido de prueba:")
        for t, p in candidatas:
            ciudad = t.get("city") or "?"
            print(f"  partner_id={t['id']}  {t['name']}  ({ciudad})")
