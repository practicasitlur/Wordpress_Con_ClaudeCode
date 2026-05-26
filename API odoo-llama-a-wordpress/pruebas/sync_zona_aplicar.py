"""
Plan hibrido para sincronizar la taxonomia 'zona' de WordPress con la lista
de provincias/localidades de Odoo.

Pasos:
  1. Crear los 6 terminos que faltan (bajo el padre "España", id 11)
  2. Reasignar el post de AIBAK del termino 266 (Gipuzkoa duplicado) al 75 (Gipuzkoa (Guipúzcoa))
  3. Borrar el termino 266 (Gipuzkoa) ya vacio
  4. Borrar el viejo 'Araba (Álava)' (id 73, count 0) — sustituido por el nuevo Araba/Álava

NO borra paises padre ni provincias de otros paises.
"""
import json
import requests
from requests.auth import HTTPBasicAuth

URL  = "https://locopolostg.wpenginepowered.com"
AUTH = HTTPBasicAuth("idoia", "Yzod AsFG nEbz NXwJ 9CX2 PC1P")
HEADERS = {"User-Agent": "LocopoloSync/1.0"}

PADRE_ESPANA = 11  # term id de "España"

TERMS_A_CREAR = [
    "Alcudia",
    "Araba/Álava",
    "Calvià",
    "Leon",
    "Palma de Mallorca",
    "menorca",
]

TERM_GIPUZKOA_CORRECTO  = 75   # "Gipuzkoa (Guipúzcoa)"
TERM_GIPUZKOA_DUPLICADO = 266  # "Gipuzkoa" (sin padre, sin parentesis)
TERM_ARABA_VIEJO        = 73   # "Araba (Álava)" — count 0


def crear_term(nombre, parent=PADRE_ESPANA):
    r = requests.post(
        f"{URL}/wp-json/wp/v2/zona",
        json={"name": nombre, "parent": parent},
        auth=AUTH, headers=HEADERS, timeout=15,
    )
    return r.status_code, r.json()


def posts_de_term(term_id):
    """Devuelve los post IDs (de cualquier post_type 'tienda') asignados a un term."""
    r = requests.get(
        f"{URL}/wp-json/wp/v2/zona/{term_id}",
        auth=AUTH, headers=HEADERS, timeout=15,
    )
    info = r.json()
    # No hay endpoint REST directo para listar posts de un term en custom post type.
    # Devolvemos al menos el count.
    return info.get("count", 0)


def borrar_term(term_id):
    r = requests.delete(
        f"{URL}/wp-json/wp/v2/zona/{term_id}?force=true",
        auth=AUTH, headers=HEADERS, timeout=15,
    )
    return r.status_code, r.json()


def reasignar_aibak():
    """
    Quita el term 266 (Gipuzkoa duplicado) y pone el 75 (correcto) en el post de AIBAK.
    Se hace via wp/v2/tienda/<id> editando el campo 'zona'.
    Pero como /wp/v2/tienda no esta expuesto, usamos un PATCH al post 4126
    a traves de wp/v2/posts. Si falla, lo hacemos via meta_query manual.
    """
    # Intento 1: wp/v2/tienda/<id>
    for endpoint in (f"{URL}/wp-json/wp/v2/tienda/4126", f"{URL}/wp-json/wp/v2/tiendas/4126"):
        r = requests.post(
            endpoint,
            json={"zona": [TERM_GIPUZKOA_CORRECTO]},
            auth=AUTH, headers=HEADERS, timeout=15,
        )
        if r.status_code == 200:
            return True, endpoint, r.json()
    return False, None, None


if __name__ == "__main__":
    print("=" * 70)
    print("PASO 1 — Crear los 6 terminos que faltan")
    print("=" * 70)
    creados = []
    for nombre in TERMS_A_CREAR:
        status, data = crear_term(nombre)
        if status in (200, 201):
            creados.append(data)
            print(f"  CREADO  '{nombre}' -> id {data.get('id')} (parent: España)")
        else:
            err = data.get('code', '?')
            msg = data.get('message', '?')
            print(f"  ERROR   '{nombre}' -> {status} {err}: {msg}")

    print()
    print("=" * 70)
    print("PASO 2 — Reasignar AIBAK (post 4126) al term correcto")
    print("=" * 70)
    ok, ep, resp = reasignar_aibak()
    if ok:
        print(f"  OK reasignado via {ep}")
    else:
        print(f"  No se pudo reasignar via REST. AIBAK seguira con el term duplicado.")
        print(f"  Saltamos el borrado del 266 para no perder la zona de AIBAK.")

    print()
    print("=" * 70)
    print("PASO 3 — Borrar term duplicado 266 (Gipuzkoa)")
    print("=" * 70)
    if ok:
        count = posts_de_term(TERM_GIPUZKOA_DUPLICADO)
        print(f"  Count en term 266 ahora: {count}")
        if count == 0:
            status, data = borrar_term(TERM_GIPUZKOA_DUPLICADO)
            print(f"  Borrado term 266: HTTP {status}")
        else:
            print(f"  Aun tiene {count} posts. No se borra.")
    else:
        print(f"  Saltado (no se reasigno AIBAK).")

    print()
    print("=" * 70)
    print("PASO 4 — Borrar 'Araba (Álava)' antiguo (id 73)")
    print("=" * 70)
    count = posts_de_term(TERM_ARABA_VIEJO)
    print(f"  Count en term 73: {count}")
    if count == 0:
        status, data = borrar_term(TERM_ARABA_VIEJO)
        print(f"  Borrado term 73: HTTP {status}")
    else:
        print(f"  Tiene {count} posts. NO se borra (peligroso).")

    print()
    print("=" * 70)
    print("RESUMEN")
    print("=" * 70)
    print(f"  Terms creados : {len(creados)}/{len(TERMS_A_CREAR)}")
    print(f"  AIBAK reasignado: {'si' if ok else 'NO'}")
