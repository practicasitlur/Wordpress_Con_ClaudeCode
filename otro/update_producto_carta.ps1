$spainTime = [System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')
$timeStr = $spainTime.ToString("yyyy-MM-dd HH:mm")

$b64 = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("idoia:Yzod AsFG nEbz NXwJ 9CX2 PC1P"))
$headers = @{ Authorization = "Basic $b64" }

$page = Invoke-RestMethod -Uri "https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009?context=edit" -Headers $headers
$content = $page.content.raw
Write-Host "Longitud inicial: $($content.Length)"

# PASO 1: Extraer el HTML del lp-fc-wrap contando anidacion de divs
$wrapStart = $content.IndexOf('<div class="lp-fc-wrap">')
$depth = 0
$pos = $wrapStart
while ($true) {
    $nextOpen = $content.IndexOf('<div', $pos + 1)
    $nextClose = $content.IndexOf('</div>', $pos + 1)
    if ($nextOpen -lt 0 -or ($nextClose -ge 0 -and $nextClose -lt $nextOpen)) {
        $depth--
        $pos = $nextClose + 6
        if ($depth -lt 0) { break }
    } else {
        $depth++
        $pos = $nextOpen
    }
}
$wrapEnd = $pos
$wrapHtml = $content.Substring($wrapStart, $wrapEnd - $wrapStart)
Write-Host "lp-fc-wrap extraido: $($wrapHtml.Length) chars"

# PASO 2: Anadir clases lp-dN a los puntos (labels de navegacion)
for ($n = 1; $n -le 15; $n++) {
    $oldLabel = '<label for="lp-fc' + $n + '" class="lp-fc-dot">'
    $newLabel = '<label for="lp-fc' + $n + '" class="lp-fc-dot lp-d' + $n + '">'
    $wrapHtml = $wrapHtml.Replace($oldLabel, $newLabel)
}
Write-Host "Clases lp-dN anadidas a los puntos"

# PASO 3: Cambios en el CSS

# 3a. Product block: align-items center -> stretch
$content = $content.Replace(
    '.lp-product-block { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: center; }',
    '.lp-product-block { display: grid; grid-template-columns: 1fr 1fr; gap: 0; align-items: stretch; }'
)

# 3b. Product image: height auto -> 100% con object-fit cover
$content = $content.Replace(
    '.lp-product-img img { width: 100%; height: auto; display: block; border: 2px solid #000; box-shadow: 5px 5px 0 #000; }',
    '.lp-product-img { display: flex; border: 2px solid #000; } .lp-product-img img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }'
)

# 3c. Anadir flex al product text (justo antes de la regla h2)
$content = $content.Replace(
    '.lp-product-text h2 { font-size: 22px; font-weight: 900; text-transform: uppercase; margin-bottom: 10px; line-height: 1.1; }',
    '.lp-product-text { display: flex; flex-direction: column; border: 2px solid #000; border-left: none; padding: 20px 22px; } .lp-product-text h2 { font-size: 22px; font-weight: 900; text-transform: uppercase; margin-bottom: 10px; line-height: 1.1; }'
)

# 3d. Arreglar bug de puntos: reemplazar label:nth-child(N) por clase .lp-dN
for ($n = 1; $n -le 15; $n++) {
    $oldDot = '#lp-fc' + $n + ':checked~.lp-fc-card .lp-fc-dots label:nth-child(' + $n + '){background:#1a1a1a;}'
    $newDot = '#lp-fc' + $n + ':checked~.lp-fc-card .lp-fc-dots .lp-d' + $n + '{background:#1a1a1a;}'
    $content = $content.Replace($oldDot, $newDot)
}
Write-Host "CSS actualizado"

# PASO 4: Localizar los limites de las secciones a reemplazar
# Seccion El Producto (tiene lp-product-block)
$prodBlockIdx = $content.IndexOf('class="lp-product-block"')
$prodSectionStart = $content.LastIndexOf('<div class="lp-section">', $prodBlockIdx)

# Seccion siguiente (lp-section-dark = Beneficios)
$darkSectionStart = $content.IndexOf('<div class="lp-section lp-section-dark"')

Write-Host "El Producto empieza en: $prodSectionStart"
Write-Host "Beneficios empieza en: $darkSectionStart"
Write-Host "Seccion a reemplazar: $($darkSectionStart - $prodSectionStart) chars"

# PASO 5: Construir la nueva seccion combinada (Producto + Carousel)
$newSection = (
    '<div class="lp-section">' + "`n" +
    '    <div class="lp-container">' + "`n" +
    '        <div class="lp-product-block">' + "`n" +
    '            <div class="lp-product-img">' + "`n" +
    '                <img src="https://locopolostg.wpenginepowered.com/wp-content/uploads/prueba-resol-72ppp-1.jpg" alt="Locob&#243; en evento" loading="lazy">' + "`n" +
    '            </div>' + "`n" +
    '            <div class="lp-product-text">' + "`n" +
    '                <div>' + "`n" +
    '                    <p class="lp-section-title">El producto</p>' + "`n" +
    '                    <h2>Locob&#243;: el postre congelado que elimina el estr&#233;s del servicio</h2>' + "`n" +
    '                    <p>Mantiene su estructura y presencia hasta los 23&#176;C, sin necesidad de cadena de fr&#237;o estricta durante el servicio. Termo-reversible, consistente y visualmente impactante.</p>' + "`n" +
    '                    <p>Un concepto que se entiende de inmediato, es f&#225;cil de explicar al cliente final y refuerza la percepci&#243;n de calidad de cualquier evento corporativo o social.</p>' + "`n" +
    '                    <div class="lp-badge-row">' + "`n" +
    '                        <span class="lp-badge">Estable hasta 23&#176;C</span>' + "`n" +
    '                        <span class="lp-badge">Termo-reversible</span>' + "`n" +
    '                        <span class="lp-badge">+100 clientes</span>' + "`n" +
    '                    </div>' + "`n" +
    '                </div>' + "`n" +
    '                <div style="margin-top:20px;padding-top:14px;border-top:1px solid #ddd;">' + "`n" +
    '                    <p style="font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#888;margin:0 0 10px;">Carta de sabores</p>' + "`n" +
    '                    ' + $wrapHtml + "`n" +
    '                </div>' + "`n" +
    '            </div>' + "`n" +
    '        </div>' + "`n" +
    '    </div>' + "`n" +
    '</div>' + "`n"
)

# PASO 6: Reemplazar las dos secciones antiguas con la nueva combinada
$before = $content.Substring(0, $prodSectionStart)
$after = $content.Substring($darkSectionStart)
$content = $before + $newSection + "`n" + $after
Write-Host "Nueva longitud total: $($content.Length)"

# PASO 7: Publicar via API (HttpWebRequest para UTF-8 correcto)
$bodyObj = [ordered]@{ content = $content }
$bodyJson = $bodyObj | ConvertTo-Json -Depth 3 -Compress
$bodyBytes = [System.Text.Encoding]::UTF8.GetBytes($bodyJson)

$request = [System.Net.HttpWebRequest]::Create("https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009")
$request.Method = "PUT"
$request.ContentType = "application/json; charset=utf-8"
$request.Headers.Add("Authorization", "Basic $b64")
$request.ContentLength = $bodyBytes.Length
$stream = $request.GetRequestStream()
$stream.Write($bodyBytes, 0, $bodyBytes.Length)
$stream.Close()

$response = $request.GetResponse()
$reader = New-Object System.IO.StreamReader($response.GetResponseStream())
$result = $reader.ReadToEnd() | ConvertFrom-Json
Write-Host "Estado: $($result.status) | URL: $($result.link)"
Write-Host "Hora Espana: $timeStr"
