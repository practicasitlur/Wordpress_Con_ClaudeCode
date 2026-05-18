$spainTime = [System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')
$timeStr = $spainTime.ToString("yyyy-MM-dd HH:mm")

$b64 = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("idoia:Yzod AsFG nEbz NXwJ 9CX2 PC1P"))
$headers = @{ Authorization = "Basic $b64" }

$page = Invoke-RestMethod -Uri "https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009?context=edit" -Headers $headers
$content = $page.content.raw
Write-Host "Longitud inicial: $($content.Length)"

# CAMBIO 1: Product block — columnas 1fr/1fr -> 1fr/2fr, align stretch -> start
# La columna izquierda pasa de 50% a 33% del ancho = 2/3 del actual
$content = $content.Replace(
    '.lp-product-block { display: grid; grid-template-columns: 1fr 1fr; gap: 0; align-items: stretch; }',
    '.lp-product-block { display: grid; grid-template-columns: 1fr 2fr; gap: 0; align-items: start; }'
)

# CAMBIO 2: Imagen — quitar height:100% para que use aspect ratio natural (height auto)
$content = $content.Replace(
    '.lp-product-img { display: flex; border: 2px solid #000; } .lp-product-img img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }',
    '.lp-product-img { display: flex; border: 2px solid #000; } .lp-product-img img { width: 100%; height: auto; display: block; object-fit: cover; object-position: top center; }'
)

# CAMBIO 3: Puntos (dots) — ocultar en CSS
$content = $content.Replace(
    '.lp-fc-dots{display:flex;gap:3px;flex-wrap:wrap;max-width:100px;}',
    '.lp-fc-dots{display:none;}'
)
Write-Host "CSS actualizado"

# CAMBIO 4: Eliminar el div .lp-fc-dots del HTML
# El div contiene solo labels (sin divs anidados), el primer </div> despues es el cierre
$dotsStart = $content.IndexOf('<div class="lp-fc-dots">')
if ($dotsStart -ge 0) {
    $dotsEnd = $content.IndexOf('</div>', $dotsStart) + 6  # +6 = longitud de "</div>"
    # Incluir el salto de linea posterior si existe
    $nextNewline = $dotsEnd
    while ($nextNewline -lt $content.Length -and ($content[$nextNewline] -eq "`n" -or $content[$nextNewline] -eq "`r")) {
        $nextNewline++
    }
    $content = $content.Remove($dotsStart, $nextNewline - $dotsStart)
    Write-Host "Div lp-fc-dots eliminado del HTML"
} else {
    Write-Host "AVISO: lp-fc-dots no encontrado en HTML"
}

Write-Host "Longitud final: $($content.Length)"

# Publicar via API
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
