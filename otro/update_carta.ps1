$spainTime = [System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')
$timeStr = $spainTime.ToString("yyyy-MM-dd HH:mm")

$b64 = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("idoia:Yzod AsFG nEbz NXwJ 9CX2 PC1P"))
$headers = @{ Authorization = "Basic $b64" }

$page = Invoke-RestMethod -Uri "https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009?context=edit" -Headers $headers
$content = $page.content.raw

$newCss = @'
.lp-fc-card{display:grid;grid-template-columns:1fr 200px;border:2px solid #1a1a1a;background:#fff;}
.lp-fc-info{padding:20px 22px;display:flex;flex-direction:column;}
.lp-fc-cat{font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#d4a843;margin-bottom:8px;}
.lp-fc-name{font-size:22px;font-weight:900;text-transform:uppercase;line-height:1.1;color:#1a1a1a;margin-bottom:8px;}
.lp-fc-desc{font-size:12px;line-height:1.6;color:#555;flex:1;}
.lp-fc-nav{display:flex;align-items:center;gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid #e0e0e0;}
.lp-fc-btn{background:none;border:1.5px solid #1a1a1a;width:28px;height:28px;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:0.15s;padding:0;}
.lp-fc-btn:hover{background:#1a1a1a;color:#F1EFEE;}
.lp-fc-dots{display:flex;gap:3px;flex-wrap:wrap;max-width:110px;}
.lp-fc-dot{width:5px;height:5px;border-radius:50%;background:#ccc;cursor:pointer;display:inline-block;transition:0.15s;}
.lp-fc-dot.fc-on{background:#1a1a1a;}
.lp-fc-count{font-size:10px;color:#999;font-weight:700;margin-left:auto;letter-spacing:1px;white-space:nowrap;}
.lp-fc-img-wrap{position:relative;border-left:2px solid #1a1a1a;overflow:hidden;min-height:200px;}
.lp-fc-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;}
.lp-fc-new{position:absolute;top:8px;right:8px;background:#d4a843;color:#fff;font-size:8px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;padding:3px 8px;display:none;}
.lp-fc-new.fc-on{display:block;}
@media(max-width:600px){.lp-fc-card{grid-template-columns:1fr;}.lp-fc-img-wrap{min-height:160px;border-left:none;border-top:2px solid #1a1a1a;}}
'@

$newSection = @'

<div class="lp-section">
    <div class="lp-container">
        <p class="lp-section-title">Nuestros productos</p>
        <h2 class="lp-section-h2">Carta de sabores</h2>
        <div class="lp-fc-card">
            <div class="lp-fc-info">
                <div>
                    <div class="lp-fc-cat" id="fc-cat">Locob&#243; Cocktail &#8212; Salados</div>
                    <div class="lp-fc-name" id="fc-name">Pesto y Tomate</div>
                    <div class="lp-fc-desc" id="fc-desc">Ba&#241;ado en chocolate blanco y pasta de pistacho y albahaca</div>
                </div>
                <div class="lp-fc-nav">
                    <button class="lp-fc-btn" onclick="fcChange(-1)">&#8249;</button>
                    <button class="lp-fc-btn" onclick="fcChange(1)">&#8250;</button>
                    <div class="lp-fc-dots" id="fc-dots"></div>
                    <span class="lp-fc-count" id="fc-count">01 / 15</span>
                </div>
            </div>
            <div class="lp-fc-img-wrap">
                <img src="https://locopolostg.wpenginepowered.com/wp-content/uploads/prueba-resol-72ppp-1.jpg" alt="Producto Locopolo" loading="lazy">
                <span class="lp-fc-new" id="fc-new">NEW</span>
            </div>
        </div>
        <script>
var fcData=[{n:"Pesto y Tomate",d:"Bañado en chocolate blanco y pasta de pistacho y albahaca",c:"Locobó Cocktail — Salados",isNew:true},{n:"Foie y Frambuesa",d:"Bañado en chocolate blanco, kikos en polvo y frambuesa liofilizada",c:"Locobó Cocktail — Salados",isNew:true},{n:"Queso Gorgonzola y Pera",d:"Bañado en chocolate blanco y granillo de nuez",c:"Locobó Cocktail — Salados",isNew:true},{n:"Salmón y Aguacate",d:"Bañado en chocolate blanco y eneldo",c:"Locobó Cocktail — Salados",isNew:true},{n:"Gilda",d:"Bañado en chocolate blanco y aceitunas liofilizadas",c:"Locobó Cocktail — Salados",isNew:true},{n:"Mango y Maracuyá",d:"Bañado en chocolate negro",c:"Locobó — Dulces",isNew:false},{n:"Oreo",d:"Bañado en chocolate blanco con trocitos de Oreo",c:"Locobó — Dulces",isNew:false},{n:"Cacahuete",d:"Relleno de crema de cacahuete y bañado en chocolate blanco",c:"Locobó — Dulces",isNew:false},{n:"Choco Caramelo Salado",d:"Relleno de crema de caramelo salado y bañado en chocolate con leche",c:"Locobó — Dulces",isNew:false},{n:"Café",d:"Relleno de crema de café y bañado en chocolate con leche",c:"Locobó — Dulces",isNew:false},{n:"Doble Choco",d:"Relleno de coulant de chocolate y bañado en chocolate negro",c:"Locobó — Dulces",isNew:false},{n:"Pistacho",d:"Relleno de crema de pistacho y bañado en chocolate blanco",c:"Locobó — Dulces",isNew:false},{n:"Choco Vegano",d:"Bañado en chocolate negro — apto para veganos",c:"Locobó — Dulces",isNew:false},{n:"Choco Avellana",d:"Relleno de praliné de avellana y bañado en chocolate con leche y avellana",c:"Locobó — Dulces",isNew:false},{n:"Frambuesa",d:"Bañado en chocolate blanco y frambuesa",c:"Locobó — Dulces",isNew:false}];
var fcIdx=0;
function fcRender(){var p=fcData[fcIdx];document.getElementById('fc-cat').textContent=p.c;document.getElementById('fc-name').textContent=p.n;document.getElementById('fc-desc').textContent=p.d;document.getElementById('fc-count').textContent=(fcIdx<9?'0':'')+(fcIdx+1)+' / '+fcData.length;document.getElementById('fc-new').className='lp-fc-new'+(p.isNew?' fc-on':'');var dots=document.getElementById('fc-dots').children;for(var i=0;i<dots.length;i++){dots[i].className='lp-fc-dot'+(i===fcIdx?' fc-on':'');}}
function fcChange(d){fcIdx=(fcIdx+d+fcData.length)%fcData.length;fcRender();}
(function(){var el=document.getElementById('fc-dots');for(var i=0;i<fcData.length;i++){var s=document.createElement('span');s.className='lp-fc-dot'+(i===0?' fc-on':'');(function(idx){s.onclick=function(){fcIdx=idx;fcRender();};})(i);el.appendChild(s);}})();
        </script>
    </div>
</div>
'@

# Paso 1: Inyectar CSS nuevo en el bloque <style> existente
$content = $content.Replace('</style>', $newCss + "</style>")

# Paso 2: Localizar la seccion carta de sabores (despues de añadir el CSS, las posiciones son correctas)
$cartaMarkerIdx = $content.IndexOf('Nuestros productos')
$cartaStart = $content.LastIndexOf("`n`n<div", $cartaMarkerIdx)

$beneficiosMarkerIdx = $content.IndexOf('class="lp-section lp-section-dark"')
$cartaEnd = $content.LastIndexOf("`n`n<div", $beneficiosMarkerIdx)

Write-Host "Carta: posicion $cartaStart a $cartaEnd (eliminando $($cartaEnd - $cartaStart) chars)"

# Paso 3: Reemplazar seccion carta
$content = $content.Remove($cartaStart, $cartaEnd - $cartaStart)
$content = $content.Insert($cartaStart, $newSection)
Write-Host "Nuevo total: $($content.Length) chars"

# Paso 4: Publicar via API
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
