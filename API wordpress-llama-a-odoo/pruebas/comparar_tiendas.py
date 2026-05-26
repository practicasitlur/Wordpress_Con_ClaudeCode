"""
Compara los meta y taxonomias de dos tiendas en WordPress staging:
- Una creada por la sincronizacion Odoo (AIBAK = post 4126)
- Una que SI aparece en /encuentranos/ (a definir)

Usa la application password de idoia para autenticarse.
"""

import requests
from requests.auth import HTTPBasicAuth
import json

URL = "https://locopolostg.wpenginepowered.com"
AUTH = HTTPBasicAuth("idoia", "Yzod AsFG nEbz NXwJ 9CX2 PC1P")
HEADERS = {"User-Agent": "LocopoloDiagnostic/1.0"}


def listar_tiendas():
    """Lista las tiendas, ordenadas por fecha (mas nuevas primero)"""
    r = requests.get(
        f"{URL}/wp-json/wp/v2/tienda",
        params={"per_page": 20, "_fields": "id,title,date,status"},
        auth=AUTH, headers=HEADERS, timeout=15,
    )
    if r.status_code == 404:
        r = requests.get(
            f"{URL}/wp-json/wp/v2/tiendas",
            params={"per_page": 20, "_fields": "id,title,date,status"},
            auth=AUTH, headers=HEADERS, timeout=15,
        )
    return r.status_code, r.text


def detalle_post(post_id):
    r = requests.get(
        f"{URL}/wp-json/wp/v2/tienda/{post_id}",
        auth=AUTH, headers=HEADERS, timeout=15,
    )
    if r.status_code == 404:
        r = requests.get(
            f"{URL}/wp-json/wp/v2/tiendas/{post_id}",
            auth=AUTH, headers=HEADERS, timeout=15,
        )
    return r.status_code, r.text


if __name__ == "__main__":
    print("=== Listando tiendas ===")
    status, body = listar_tiendas()
    print(f"HTTP {status}")
    try:
        data = json.loads(body)
        if isinstance(data, list):
            print(f"Total tiendas devueltas: {len(data)}")
            for t in data:
                titulo = t.get("title", {}).get("rendered", "?") if isinstance(t.get("title"), dict) else t.get("title", "?")
                print(f"  ID {t.get('id')} | {t.get('status')} | {titulo}")
        else:
            print("Respuesta (no es lista):", json.dumps(data, indent=2, ensure_ascii=False)[:500])
    except Exception as e:
        print(f"No se pudo parsear como JSON: {e}")
        print("Body:", body[:300])

    print()
    print("=== Detalle de AIBAK (post 4126) ===")
    status, body = detalle_post(4126)
    print(f"HTTP {status}")
    try:
        data = json.loads(body)
        if isinstance(data, dict):
            print(f"  ID: {data.get('id')}")
            print(f"  title: {data.get('title')}")
            print(f"  status: {data.get('status')}")
            print(f"  type: {data.get('type')}")
            print(f"  Campos disponibles: {list(data.keys())}")
            if 'meta' in data:
                print(f"  meta: {data['meta']}")
            if 'acf' in data:
                print(f"  acf: {data['acf']}")
    except Exception as e:
        print(f"No JSON: {e}")
        print(body[:300])
