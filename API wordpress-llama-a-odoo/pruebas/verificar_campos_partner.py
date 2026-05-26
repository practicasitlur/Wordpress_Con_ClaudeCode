"""
Verifica que los campos que usa el modulo locopolo_wordpress_sync en res.partner
existen realmente en la instancia de Odoo. Consulta ir.model.fields.

Uso: python verificar_campos_partner.py
"""

import os
import requests
from dotenv import load_dotenv

load_dotenv(os.path.join(os.path.dirname(__file__), '..', '.env'))

URL      = os.getenv('ODOO_URL')
DB       = os.getenv('ODOO_DB')
USERNAME = os.getenv('ODOO_USERNAME')
API_KEY  = os.getenv('ODOO_API_KEY')

# Campos que usamos en _datos_para_wordpress()
CAMPOS_USADOS = [
    'id',
    'name',
    'street',
    'street_name',   # incluido por si Locopolo usa este en lugar de street
    'city',
    'zip',
    'state_id',
    'phone',
    'mobile',
    'category_id',
]


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


if __name__ == "__main__":
    print("Autenticando...")
    uid = autenticar()
    print(f"UID: {uid}\n")

    print("Consultando campos de res.partner...")
    campos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "ir.model.fields", "search_read",
        [[
            ["model", "=", "res.partner"],
            ["name", "in", CAMPOS_USADOS],
        ]],
        {"fields": ["name", "ttype", "field_description", "required"]},
    )

    encontrados = {c["name"]: c for c in campos}

    print(f"\n{'CAMPO':<20} {'EXISTE':<10} {'TIPO':<15} {'DESCRIPCION'}")
    print("-" * 80)
    for campo in CAMPOS_USADOS:
        if campo in encontrados:
            c = encontrados[campo]
            print(f"{campo:<20} {'OK':<10} {c['ttype']:<15} {c['field_description']}")
        else:
            print(f"{campo:<20} {'NO EXISTE':<10}")

    # Comprobacion adicional: leer una tienda real para ver los valores
    print("\n\nLeyendo el partner LUISA MARIA ZUIN (id=7918) para confirmar:")
    partner = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "res.partner", "read",
        [[7918]],
        {"fields": ["id", "name", "street", "city", "zip", "state_id", "phone", "mobile", "category_id"]},
    )
    if partner:
        p = partner[0]
        for k, v in p.items():
            print(f"  {k:<15} = {v}")
