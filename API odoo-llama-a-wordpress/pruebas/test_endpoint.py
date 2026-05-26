"""
Test del endpoint: POST /wp-json/odoo/v1/tiendas
Ejecutar con: python test_endpoint.py
Requiere: pip install requests
"""

import requests

URL    = "http://locopolo-local.local/wp-json/odoo/v1/tiendas"
TOKEN  = "MI_SUPER_TOKEN_SECRETO_12345"

VERDE  = "\033[92m"
ROJO   = "\033[91m"
RESET  = "\033[0m"

def ok(msg):   print(f"{VERDE}  ✓ {msg}{RESET}")
def fail(msg): print(f"{ROJO}  ✗ {msg}{RESET}")

# ---------------------------------------------------------------------------
# Test 1 — Token válido + datos correctos → debe devolver 200 y wp_post_id
# ---------------------------------------------------------------------------
print("\n[Test 1] Token válido — debe crear/actualizar la tienda")
try:
    r = requests.post(
        URL,
        json={
            "odoo_id":   "TEST-CACERES-002",
            "nombre":    "Café de Cáceres",
            "direccion": "Plaza Mayor 3, Cáceres",
            "telefono":  "927000000",
            "zona":      "caceres",
            "tipo":      "mini-corner",
            "ciudad":    "Cáceres",
        },
        headers={"X-Odoo-Token": TOKEN, "Content-Type": "application/json"},
        timeout=10,
    )
    if r.status_code == 200 and r.json().get("status") == "success":
        ok(f"HTTP {r.status_code} — respuesta completa: {r.json()}")
    else:
        fail(f"HTTP {r.status_code} — {r.text}")
except requests.exceptions.ConnectionError:
    fail("No se pudo conectar. ¿Está arrancado el servidor local?")

# ---------------------------------------------------------------------------
# Test 2 — Token inválido → debe devolver 401 o 403
# ---------------------------------------------------------------------------
print("\n[Test 2] Token inválido — debe rechazar la petición")
try:
    r = requests.post(
        URL,
        json={"odoo_id": "TEST-002", "nombre": "X", "direccion": "X", "telefono": "X"},
        headers={"X-Odoo-Token": "TOKEN_INCORRECTO", "Content-Type": "application/json"},
        timeout=10,
    )
    if r.status_code in (401, 403):
        ok(f"HTTP {r.status_code} — acceso denegado correctamente")
    else:
        fail(f"HTTP {r.status_code} — se esperaba 401/403, respuesta: {r.text}")
except requests.exceptions.ConnectionError:
    fail("No se pudo conectar. ¿Está arrancado el servidor local?")

# ---------------------------------------------------------------------------
# Test 3 — Sin token → debe devolver 401 o 403
# ---------------------------------------------------------------------------
print("\n[Test 3] Sin token — debe rechazar la petición")
try:
    r = requests.post(
        URL,
        json={"odoo_id": "TEST-003", "nombre": "X", "direccion": "X", "telefono": "X"},
        headers={"Content-Type": "application/json"},
        timeout=10,
    )
    if r.status_code in (401, 403):
        ok(f"HTTP {r.status_code} — acceso denegado correctamente")
    else:
        fail(f"HTTP {r.status_code} — se esperaba 401/403, respuesta: {r.text}")
except requests.exceptions.ConnectionError:
    fail("No se pudo conectar. ¿Está arrancado el servidor local?")

print()
