"""
Test de conexión con la API de Odoo.
Verifica que las credenciales son correctas y la conexión funciona.

Requisitos: pip install python-dotenv requests
Uso: python test_conexion_odoo.py
"""

import json
import os
import requests
from dotenv import load_dotenv

# Cargar variables del .env
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
        raise Exception(f"Error Odoo: {result['error']['data']['message']}")
    return result["result"]


def test_autenticacion():
    print("=" * 50)
    print("TEST 1: Autenticación")
    print("=" * 50)
    print(f"  URL:      {URL}")
    print(f"  Base de datos: {DB}")
    print(f"  Usuario:  {USERNAME}")
    print()

    uid = odoo_call("common", "authenticate", DB, USERNAME, API_KEY, {})

    if uid:
        print(f"  CONECTADO — UID del usuario: {uid}")
    else:
        print("  ERROR — Credenciales incorrectas o usuario no encontrado")

    return uid


def test_primer_contacto(uid):
    print()
    print("=" * 50)
    print("TEST 2: Leer primer contacto de Odoo")
    print("=" * 50)

    contactos = odoo_call(
        "object", "execute_kw",
        DB, uid, API_KEY,
        "res.partner", "search_read",
        [[]],
        {"fields": ["id", "name", "email"], "limit": 3}
    )

    if contactos:
        print(f"  Conexión correcta. Primeros contactos encontrados:")
        for c in contactos:
            print(f"    ID: {c['id']}  |  Nombre: {c['name']}  |  Email: {c.get('email') or '-'}")
    else:
        print("  No se encontraron contactos.")

    return contactos


if __name__ == "__main__":
    try:
        uid = test_autenticacion()
        if uid:
            test_primer_contacto(uid)
    except Exception as e:
        print(f"\n  ERROR: {e}")
