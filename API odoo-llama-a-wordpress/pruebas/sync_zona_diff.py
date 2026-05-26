"""
Diagnostico — compara las taxonomias zona/localidad de WordPress con la lista
canonica de provincias/localidades de Odoo. Muestra el diff sin aplicar nada.

Uso: python sync_zona_diff.py
"""
import json
import requests
from requests.auth import HTTPBasicAuth

URL  = "https://locopolostg.wpenginepowered.com"
AUTH = HTTPBasicAuth("idoia", "Yzod AsFG nEbz NXwJ 9CX2 PC1P")
HEADERS = {"User-Agent": "LocopoloDiagnostic/1.0"}

# Lista canonica desde Odoo (nombre tal cual aparece en res.country.state)
ODOO_NOMBRES = [
    "Calvià",
    "Alcudia",
    "Leon",
    "Alacant (Alicante)",
    "Albacete",
    "Almería",
    "Ávila",
    "Barcelona",
    "Badajoz",
    "Bizkaia (Vizcaya)",
    "Burgos",
    "A Coruña (La Coruña)",
    "Cádiz",
    "Cáceres",
    "Ceuta",
    "Córdoba",
    "Ciudad Real",
    "Castelló (Castellón)",
    "Cuenca",
    "Las Palmas",
    "Girona (Gerona)",
    "Granada",
    "Guadalajara",
    "Huelva",
    "Huesca",
    "Jaén",
    "Lleida (Lérida)",
    "León",
    "La Rioja",
    "Lugo",
    "Madrid",
    "Málaga",
    "Melilla",
    "Murcia",
    "Navarra (Nafarroa)",
    "Asturias",
    "Ourense (Orense)",
    "Palencia",
    "Illes Balears (Islas Baleares)",
    "Pontevedra",
    "Palma de Mallorca",
    "Cantabria",
    "Salamanca",
    "Sevilla",
    "Segovia",
    "Soria",
    "Gipuzkoa (Guipúzcoa)",
    "Tarragona",
    "Teruel",
    "Santa Cruz de Tenerife",
    "Toledo",
    "València (Valencia)",
    "Valladolid",
    "Araba/Álava",
    "Zaragoza",
    "Zamora",
    "menorca",
]


def listar_taxonomias():
    r = requests.get(f"{URL}/wp-json/wp/v2/taxonomies", auth=AUTH, headers=HEADERS, timeout=15)
    return r.json()


def listar_terms(taxonomia):
    """Trae todos los terms de una taxonomia (paginado)."""
    todos = []
    page = 1
    while True:
        r = requests.get(
            f"{URL}/wp-json/wp/v2/{taxonomia}",
            params={"per_page": 100, "page": page, "hide_empty": "false"},
            auth=AUTH, headers=HEADERS, timeout=15,
        )
        if r.status_code != 200:
            return todos, r.status_code, r.text[:200]
        batch = r.json()
        if not batch:
            break
        todos.extend(batch)
        if len(batch) < 100:
            break
        page += 1
    return todos, 200, None


if __name__ == "__main__":
    print("=== Taxonomias disponibles en WP ===")
    tax = listar_taxonomias()
    if isinstance(tax, dict):
        for slug, info in tax.items():
            print(f"  {slug:<20} (rest_base: {info.get('rest_base')}, types: {info.get('types')})")
    print()

    # Probamos las dos posibles
    for taxonomia in ("zona", "localidad"):
        print(f"=== Terms en taxonomia '{taxonomia}' ===")
        terms, status, err = listar_terms(taxonomia)
        if status != 200:
            print(f"  HTTP {status}: {err}")
            print()
            continue

        nombres_wp = {t["name"]: t for t in terms}
        odoo_set = set(ODOO_NOMBRES)
        wp_set   = set(nombres_wp.keys())

        a_crear   = sorted(odoo_set - wp_set)
        a_borrar  = sorted(wp_set - odoo_set)
        ya_iguales = sorted(odoo_set & wp_set)

        print(f"  Total terms en WP: {len(terms)}")
        print(f"  Coincidencias (ya iguales): {len(ya_iguales)}")
        print(f"  A CREAR (estan en Odoo pero no en WP): {len(a_crear)}")
        for n in a_crear[:20]:
            print(f"    + {n}")
        if len(a_crear) > 20:
            print(f"    ... y {len(a_crear) - 20} mas")

        print(f"  A BORRAR (estan en WP pero no en Odoo): {len(a_borrar)}")
        for n in a_borrar[:30]:
            t = nombres_wp[n]
            print(f"    - '{n}' (id {t['id']}, count {t['count']}, slug {t['slug']})")
        if len(a_borrar) > 30:
            print(f"    ... y {len(a_borrar) - 30} mas")
        print()
