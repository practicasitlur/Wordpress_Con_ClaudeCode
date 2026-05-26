"""
Lee el parametro locopolo_wordpress.token desde Odoo y muestra exactamente que hay,
incluyendo longitud y representacion repr() para ver caracteres invisibles.
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
        json={"jsonrpc": "2.0", "method": "call", "id": 1,
              "params": {"service": service, "method": method, "args": list(args)}},
        timeout=30,
    )
    result = response.json()
    if "error" in result:
        raise Exception(result['error']['data']['message'])
    return result["result"]


if __name__ == "__main__":
    uid = odoo_call("common", "authenticate", DB, USERNAME, API_KEY, {})

    for clave in ["locopolo_wordpress.url", "locopolo_wordpress.token"]:
        params = odoo_call(
            "object", "execute_kw",
            DB, uid, API_KEY,
            "ir.config_parameter", "search_read",
            [[["key", "=", clave]]],
            {"fields": ["key", "value"]},
        )
        if not params:
            print(f"{clave:<35} → NO EXISTE")
            continue
        valor = params[0]["value"]
        print(f"Clave    : {clave}")
        print(f"Valor    : '{valor}'")
        print(f"Longitud : {len(valor)} caracteres")
        print(f"Bytes    : {valor.encode('utf-8')!r}")
        print(f"Repr     : {valor!r}")
        print("-" * 60)
