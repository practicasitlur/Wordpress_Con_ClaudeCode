$b64 = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("idoia:Yzod AsFG nEbz NXwJ 9CX2 PC1P"))
$headers = @{ Authorization = "Basic $b64" }
$baseDir = "c:\Users\usuario\Documents\lur\wordpress\imagenes_catering"

# slot -> nombre de archivo
$files = @(
    "02_Pesto y Tomate.png",
    "04_Foie y Frambuesa.png",
    "03_Queso Gorgonzola y Pera.png",
    "05_Salmon y Aguacate.png",
    "01_Gilda.png",
    "06_Mango y Maracuya.png",
    "07_Oreo.png",
    "08_Cacahuete.png",
    "09_Choco Caramelo Salado.png",
    "10_Cafe.png",
    "12_Doble Choco.png",
    "13_Pistacho.png",
    "11_Choco Vegano.png",
    "14_Choco Avellana.png",
    "15_Frambuesa.png"
)

$alts = @(
    "Pesto y Tomate",
    "Foie y Frambuesa",
    "Queso Gorgonzola y Pera",
    "Salmon y Aguacate",
    "Gilda",
    "Mango y Maracuya",
    "Oreo",
    "Cacahuete",
    "Choco Caramelo Salado",
    "Cafe",
    "Doble Choco",
    "Pistacho",
    "Choco Vegano",
    "Choco Avellana",
    "Frambuesa"
)

# Nota: los archivos en disco tienen acentos, buscar con nombre real
$realFiles = @(
    "02_Pesto y Tomate.png",
    "04_Foie y Frambuesa.png",
    "03_Queso Gorgonzola y Pera.png",
    "05_Salm`u00f3n y Aguacate.png",
    "01_Gilda.png",
    "06_Mango y Maracuy`u00e1.png",
    "07_Oreo.png",
    "08_Cacahuete.png",
    "09_Choco Caramelo Salado.png",
    "10_Caf`u00e9.png",
    "12_Doble Choco.png",
    "13_Pistacho.png",
    "11_Choco Vegano.png",
    "14_Choco Avellana.png",
    "15_Frambuesa.png"
)

# Obtener los archivos reales del directorio
$allFiles = Get-ChildItem $baseDir -Filter "*.png" | Sort-Object Name

Write-Host "Archivos encontrados en el directorio:"
$allFiles | ForEach-Object { Write-Host "  $($_.Name)" }
Write-Host ""

$slotUrls = @{}

for ($i = 0; $i -lt 15; $i++) {
    $slot = $i + 1
    $searchName = $files[$i]

    # Encontrar el archivo real (puede tener acentos)
    $matchFile = $allFiles | Where-Object { $_.Name -like "*$($searchName.Substring(0,6))*" } | Select-Object -First 1
    if (-not $matchFile) {
        # Buscar por numero al inicio
        $num = $searchName.Substring(0,2)
        $matchFile = $allFiles | Where-Object { $_.Name.StartsWith($num) } | Select-Object -First 1
    }

    if (-not $matchFile) {
        Write-Host "ERROR: No se encontro archivo para slot $slot ($searchName)"
        continue
    }

    Write-Host "Subiendo slot ${slot}: $($matchFile.Name) ..."
    $bytes = [System.IO.File]::ReadAllBytes($matchFile.FullName)
    $uploadName = $files[$i]

    try {
        $req = [System.Net.HttpWebRequest]::Create("https://locopolostg.wpenginepowered.com/wp-json/wp/v2/media")
        $req.Method = "POST"
        $req.Headers.Add("Authorization", "Basic $b64")
        $req.ContentType = "image/png"
        $req.Headers.Add("Content-Disposition", "attachment; filename=`"$uploadName`"")
        $req.ContentLength = $bytes.Length
        $st = $req.GetRequestStream()
        $st.Write($bytes, 0, $bytes.Length)
        $st.Close()
        $resp = $req.GetResponse()
        $rdr = New-Object System.IO.StreamReader($resp.GetResponseStream())
        $media = $rdr.ReadToEnd() | ConvertFrom-Json
        $slotUrls[$slot] = $media.source_url
        Write-Host "  OK: $($media.source_url)"
    } catch {
        Write-Host "  ERROR slot ${slot}: $_"
    }
}

Write-Host ""
Write-Host "=== Resumen de URLs ==="
for ($i = 1; $i -le 15; $i++) {
    Write-Host "p${i}: $($slotUrls[$i])"
}

if ($slotUrls.Count -lt 15) {
    Write-Host "ABORTANDO: no se subieron todas las imagenes ($($slotUrls.Count)/15)"
    exit 1
}

# Construir bloque de imagenes
$imgLines = ""
for ($i = 1; $i -le 15; $i++) {
    $idx = $i - 1
    $url = $slotUrls[$i]
    $alt = $alts[$idx]
    $imgLines += "                    <img class=""lp-fc-img-p${i}"" src=""$url"" alt=""$alt"" loading=""lazy"">`n"
}

$newImgBlock = "<div class=""lp-fc-img-wrap"">`n$imgLines                    <span class=""lp-fc-new"">NEW</span>`n                </div>"

# CSS para imagenes dinamicas
$imgCss = @'
.lp-fc-img-wrap img{display:none;width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;}
#lp-fc1:checked~.lp-fc-card .lp-fc-img-p1{display:block;}
#lp-fc2:checked~.lp-fc-card .lp-fc-img-p2{display:block;}
#lp-fc3:checked~.lp-fc-card .lp-fc-img-p3{display:block;}
#lp-fc4:checked~.lp-fc-card .lp-fc-img-p4{display:block;}
#lp-fc5:checked~.lp-fc-card .lp-fc-img-p5{display:block;}
#lp-fc6:checked~.lp-fc-card .lp-fc-img-p6{display:block;}
#lp-fc7:checked~.lp-fc-card .lp-fc-img-p7{display:block;}
#lp-fc8:checked~.lp-fc-card .lp-fc-img-p8{display:block;}
#lp-fc9:checked~.lp-fc-card .lp-fc-img-p9{display:block;}
#lp-fc10:checked~.lp-fc-card .lp-fc-img-p10{display:block;}
#lp-fc11:checked~.lp-fc-card .lp-fc-img-p11{display:block;}
#lp-fc12:checked~.lp-fc-card .lp-fc-img-p12{display:block;}
#lp-fc13:checked~.lp-fc-card .lp-fc-img-p13{display:block;}
#lp-fc14:checked~.lp-fc-card .lp-fc-img-p14{display:block;}
#lp-fc15:checked~.lp-fc-card .lp-fc-img-p15{display:block;}
'@

# Obtener contenido actual de la pagina
Write-Host ""
Write-Host "Obteniendo pagina 4009..."
$page = Invoke-RestMethod -Uri "https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009?context=edit" -Headers $headers
$content = $page.content.raw

# Insertar CSS antes de </style>
$content = $content.Replace("</style>", $imgCss + "</style>")

# Localizar y reemplazar el bloque de imagen en lp-fc-img-wrap
$imgWrapStart = $content.IndexOf('<div class="lp-fc-img-wrap">')
if ($imgWrapStart -lt 0) {
    Write-Host "ERROR: No se encontro lp-fc-img-wrap en la pagina"
    exit 1
}
$imgWrapEnd = $content.IndexOf('</div>', $imgWrapStart) + 6
$oldImgBlock = $content.Substring($imgWrapStart, $imgWrapEnd - $imgWrapStart)
Write-Host "Bloque actual encontrado ($($oldImgBlock.Length) chars):"
Write-Host $oldImgBlock.Substring(0, [Math]::Min(200, $oldImgBlock.Length))

$content = $content.Remove($imgWrapStart, $imgWrapEnd - $imgWrapStart)
$content = $content.Insert($imgWrapStart, $newImgBlock)
Write-Host "Bloque reemplazado. Nuevo total: $($content.Length) chars"

# Guardar caterings_current2.html actualizado
# (opcional, para referencia local)

# Enviar a WordPress
Write-Host "Actualizando pagina en WordPress..."
$bodyObj = [ordered]@{ content = $content }
$bodyJson = $bodyObj | ConvertTo-Json -Depth 3 -Compress
$bodyBytes = [System.Text.Encoding]::UTF8.GetBytes($bodyJson)

$req2 = [System.Net.HttpWebRequest]::Create("https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009")
$req2.Method = "PUT"
$req2.ContentType = "application/json; charset=utf-8"
$req2.Headers.Add("Authorization", "Basic $b64")
$req2.ContentLength = $bodyBytes.Length
$st2 = $req2.GetRequestStream()
$st2.Write($bodyBytes, 0, $bodyBytes.Length)
$st2.Close()

$resp2 = $req2.GetResponse()
$rdr2 = New-Object System.IO.StreamReader($resp2.GetResponseStream())
$result = $rdr2.ReadToEnd() | ConvertFrom-Json
Write-Host "Estado: $($result.status) | URL: $($result.link)"
$spainTime = [System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')
Write-Host "Hora: $($spainTime.ToString('yyyy-MM-dd HH:mm'))"
