$spainTime = [System.TimeZoneInfo]::ConvertTimeBySystemTimeZoneId([DateTime]::UtcNow, 'Romance Standard Time')
$timeStr = $spainTime.ToString("yyyy-MM-dd HH:mm")

$b64 = [Convert]::ToBase64String([System.Text.Encoding]::UTF8.GetBytes("idoia:Yzod AsFG nEbz NXwJ 9CX2 PC1P"))
$headers = @{ Authorization = "Basic $b64" }

$page = Invoke-RestMethod -Uri "https://locopolostg.wpenginepowered.com/wp-json/wp/v2/pages/4009?context=edit" -Headers $headers
$content = $page.content.raw

$newCss = @'
.lp-fc-r{display:none;}.lp-fc-card{display:grid;grid-template-columns:1fr 200px;border:2px solid #1a1a1a;background:#fff;min-height:220px;}
.lp-fc-info{padding:20px 22px;display:flex;flex-direction:column;}.lp-fc-panels{flex:1;position:relative;min-height:140px;}
.lp-fc-panel{display:none;position:absolute;top:0;left:0;right:0;}
.lp-fc-cat{display:block;font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#d4a843;margin-bottom:8px;}
.lp-fc-fname{font-size:22px;font-weight:900;text-transform:uppercase;line-height:1.1;color:#1a1a1a;margin-bottom:8px;}
.lp-fc-desc{font-size:12px;line-height:1.6;color:#555;}
.lp-fc-nav{display:flex;align-items:center;gap:8px;padding-top:14px;border-top:1px solid #e0e0e0;}
.lp-fc-ba{position:relative;width:28px;height:28px;flex-shrink:0;}
.lp-fc-btn{position:absolute;top:0;left:0;width:100%;height:100%;display:none;align-items:center;justify-content:center;border:1.5px solid #1a1a1a;cursor:pointer;font-size:18px;background:none;text-align:center;line-height:28px;}
.lp-fc-btn:hover{background:#1a1a1a;color:#F1EFEE;}
.lp-fc-dots{display:flex;gap:3px;flex-wrap:wrap;max-width:100px;}
.lp-fc-dot{width:6px;height:6px;border-radius:50%;background:#ccc;cursor:pointer;display:inline-block;}
.lp-fc-dot:hover{background:#888;}
.lp-fc-ctr{margin-left:auto;font-size:10px;color:#999;font-weight:700;letter-spacing:1px;white-space:nowrap;}
.lp-fc-ctr::before{content:"-- / 15";}
.lp-fc-img-wrap{position:relative;border-left:2px solid #1a1a1a;overflow:hidden;}
.lp-fc-img-wrap img{width:100%;height:100%;object-fit:cover;display:block;}
.lp-fc-new{position:absolute;top:8px;right:8px;background:#d4a843;color:#fff;font-size:8px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;padding:3px 8px;display:none;}
#lp-fc1:checked~.lp-fc-card .lp-fc-p1{display:block;}
#lp-fc2:checked~.lp-fc-card .lp-fc-p2{display:block;}
#lp-fc3:checked~.lp-fc-card .lp-fc-p3{display:block;}
#lp-fc4:checked~.lp-fc-card .lp-fc-p4{display:block;}
#lp-fc5:checked~.lp-fc-card .lp-fc-p5{display:block;}
#lp-fc6:checked~.lp-fc-card .lp-fc-p6{display:block;}
#lp-fc7:checked~.lp-fc-card .lp-fc-p7{display:block;}
#lp-fc8:checked~.lp-fc-card .lp-fc-p8{display:block;}
#lp-fc9:checked~.lp-fc-card .lp-fc-p9{display:block;}
#lp-fc10:checked~.lp-fc-card .lp-fc-p10{display:block;}
#lp-fc11:checked~.lp-fc-card .lp-fc-p11{display:block;}
#lp-fc12:checked~.lp-fc-card .lp-fc-p12{display:block;}
#lp-fc13:checked~.lp-fc-card .lp-fc-p13{display:block;}
#lp-fc14:checked~.lp-fc-card .lp-fc-p14{display:block;}
#lp-fc15:checked~.lp-fc-card .lp-fc-p15{display:block;}
#lp-fc1:checked~.lp-fc-card .lp-fc-new,#lp-fc2:checked~.lp-fc-card .lp-fc-new,#lp-fc3:checked~.lp-fc-card .lp-fc-new,#lp-fc4:checked~.lp-fc-card .lp-fc-new,#lp-fc5:checked~.lp-fc-card .lp-fc-new{display:block;}
#lp-fc1:checked~.lp-fc-card .lp-fc-dots label:nth-child(1){background:#1a1a1a;}
#lp-fc2:checked~.lp-fc-card .lp-fc-dots label:nth-child(2){background:#1a1a1a;}
#lp-fc3:checked~.lp-fc-card .lp-fc-dots label:nth-child(3){background:#1a1a1a;}
#lp-fc4:checked~.lp-fc-card .lp-fc-dots label:nth-child(4){background:#1a1a1a;}
#lp-fc5:checked~.lp-fc-card .lp-fc-dots label:nth-child(5){background:#1a1a1a;}
#lp-fc6:checked~.lp-fc-card .lp-fc-dots label:nth-child(6){background:#1a1a1a;}
#lp-fc7:checked~.lp-fc-card .lp-fc-dots label:nth-child(7){background:#1a1a1a;}
#lp-fc8:checked~.lp-fc-card .lp-fc-dots label:nth-child(8){background:#1a1a1a;}
#lp-fc9:checked~.lp-fc-card .lp-fc-dots label:nth-child(9){background:#1a1a1a;}
#lp-fc10:checked~.lp-fc-card .lp-fc-dots label:nth-child(10){background:#1a1a1a;}
#lp-fc11:checked~.lp-fc-card .lp-fc-dots label:nth-child(11){background:#1a1a1a;}
#lp-fc12:checked~.lp-fc-card .lp-fc-dots label:nth-child(12){background:#1a1a1a;}
#lp-fc13:checked~.lp-fc-card .lp-fc-dots label:nth-child(13){background:#1a1a1a;}
#lp-fc14:checked~.lp-fc-card .lp-fc-dots label:nth-child(14){background:#1a1a1a;}
#lp-fc15:checked~.lp-fc-card .lp-fc-dots label:nth-child(15){background:#1a1a1a;}
#lp-fc1:checked~.lp-fc-card .lp-fc-ctr::before{content:"01 / 15";}
#lp-fc2:checked~.lp-fc-card .lp-fc-ctr::before{content:"02 / 15";}
#lp-fc3:checked~.lp-fc-card .lp-fc-ctr::before{content:"03 / 15";}
#lp-fc4:checked~.lp-fc-card .lp-fc-ctr::before{content:"04 / 15";}
#lp-fc5:checked~.lp-fc-card .lp-fc-ctr::before{content:"05 / 15";}
#lp-fc6:checked~.lp-fc-card .lp-fc-ctr::before{content:"06 / 15";}
#lp-fc7:checked~.lp-fc-card .lp-fc-ctr::before{content:"07 / 15";}
#lp-fc8:checked~.lp-fc-card .lp-fc-ctr::before{content:"08 / 15";}
#lp-fc9:checked~.lp-fc-card .lp-fc-ctr::before{content:"09 / 15";}
#lp-fc10:checked~.lp-fc-card .lp-fc-ctr::before{content:"10 / 15";}
#lp-fc11:checked~.lp-fc-card .lp-fc-ctr::before{content:"11 / 15";}
#lp-fc12:checked~.lp-fc-card .lp-fc-ctr::before{content:"12 / 15";}
#lp-fc13:checked~.lp-fc-card .lp-fc-ctr::before{content:"13 / 15";}
#lp-fc14:checked~.lp-fc-card .lp-fc-ctr::before{content:"14 / 15";}
#lp-fc15:checked~.lp-fc-card .lp-fc-ctr::before{content:"15 / 15";}
#lp-fc1:checked~.lp-fc-card .lp-fc-p1-pr,#lp-fc2:checked~.lp-fc-card .lp-fc-p2-pr,#lp-fc3:checked~.lp-fc-card .lp-fc-p3-pr,#lp-fc4:checked~.lp-fc-card .lp-fc-p4-pr,#lp-fc5:checked~.lp-fc-card .lp-fc-p5-pr,#lp-fc6:checked~.lp-fc-card .lp-fc-p6-pr,#lp-fc7:checked~.lp-fc-card .lp-fc-p7-pr,#lp-fc8:checked~.lp-fc-card .lp-fc-p8-pr,#lp-fc9:checked~.lp-fc-card .lp-fc-p9-pr,#lp-fc10:checked~.lp-fc-card .lp-fc-p10-pr,#lp-fc11:checked~.lp-fc-card .lp-fc-p11-pr,#lp-fc12:checked~.lp-fc-card .lp-fc-p12-pr,#lp-fc13:checked~.lp-fc-card .lp-fc-p13-pr,#lp-fc14:checked~.lp-fc-card .lp-fc-p14-pr,#lp-fc15:checked~.lp-fc-card .lp-fc-p15-pr{display:flex;}
#lp-fc1:checked~.lp-fc-card .lp-fc-p1-nx,#lp-fc2:checked~.lp-fc-card .lp-fc-p2-nx,#lp-fc3:checked~.lp-fc-card .lp-fc-p3-nx,#lp-fc4:checked~.lp-fc-card .lp-fc-p4-nx,#lp-fc5:checked~.lp-fc-card .lp-fc-p5-nx,#lp-fc6:checked~.lp-fc-card .lp-fc-p6-nx,#lp-fc7:checked~.lp-fc-card .lp-fc-p7-nx,#lp-fc8:checked~.lp-fc-card .lp-fc-p8-nx,#lp-fc9:checked~.lp-fc-card .lp-fc-p9-nx,#lp-fc10:checked~.lp-fc-card .lp-fc-p10-nx,#lp-fc11:checked~.lp-fc-card .lp-fc-p11-nx,#lp-fc12:checked~.lp-fc-card .lp-fc-p12-nx,#lp-fc13:checked~.lp-fc-card .lp-fc-p13-nx,#lp-fc14:checked~.lp-fc-card .lp-fc-p14-nx,#lp-fc15:checked~.lp-fc-card .lp-fc-p15-nx{display:flex;}
@media(max-width:600px){.lp-fc-card{grid-template-columns:1fr;min-height:auto;}.lp-fc-img-wrap{height:160px;border-left:none;border-top:2px solid #1a1a1a;}}
'@

$newSection = @'

<div class="lp-section">
    <div class="lp-container">
        <p class="lp-section-title">Nuestros productos</p>
        <h2 class="lp-section-h2">Carta de sabores</h2>
        <div class="lp-fc-wrap">
            <input type="radio" name="lp-fc" id="lp-fc1" class="lp-fc-r" checked>
            <input type="radio" name="lp-fc" id="lp-fc2" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc3" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc4" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc5" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc6" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc7" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc8" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc9" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc10" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc11" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc12" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc13" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc14" class="lp-fc-r">
            <input type="radio" name="lp-fc" id="lp-fc15" class="lp-fc-r">
            <div class="lp-fc-card">
                <div class="lp-fc-info">
                    <div class="lp-fc-panels">
                        <div class="lp-fc-panel lp-fc-p1"><span class="lp-fc-cat">Locob&#243; Cocktail &#8212; Salados</span><div class="lp-fc-fname">Pesto y Tomate</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco y pasta de pistacho y albahaca</div></div>
                        <div class="lp-fc-panel lp-fc-p2"><span class="lp-fc-cat">Locob&#243; Cocktail &#8212; Salados</span><div class="lp-fc-fname">Foie y Frambuesa</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco, kikos en polvo y frambuesa liofilizada</div></div>
                        <div class="lp-fc-panel lp-fc-p3"><span class="lp-fc-cat">Locob&#243; Cocktail &#8212; Salados</span><div class="lp-fc-fname">Queso Gorgonzola y Pera</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco y granillo de nuez</div></div>
                        <div class="lp-fc-panel lp-fc-p4"><span class="lp-fc-cat">Locob&#243; Cocktail &#8212; Salados</span><div class="lp-fc-fname">Salm&#243;n y Aguacate</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco y eneldo</div></div>
                        <div class="lp-fc-panel lp-fc-p5"><span class="lp-fc-cat">Locob&#243; Cocktail &#8212; Salados</span><div class="lp-fc-fname">Gilda</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco y aceitunas liofilizadas</div></div>
                        <div class="lp-fc-panel lp-fc-p6"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Mango y Maracuy&#225;</div><div class="lp-fc-desc">Ba&#241;ado en chocolate negro</div></div>
                        <div class="lp-fc-panel lp-fc-p7"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Oreo</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco con trocitos de Oreo</div></div>
                        <div class="lp-fc-panel lp-fc-p8"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Cacahuete</div><div class="lp-fc-desc">Relleno de crema de cacahuete y ba&#241;ado en chocolate blanco</div></div>
                        <div class="lp-fc-panel lp-fc-p9"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Choco Caramelo Salado</div><div class="lp-fc-desc">Relleno de crema de caramelo salado y ba&#241;ado en chocolate con leche</div></div>
                        <div class="lp-fc-panel lp-fc-p10"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Caf&#233;</div><div class="lp-fc-desc">Relleno de crema de caf&#233; y ba&#241;ado en chocolate con leche</div></div>
                        <div class="lp-fc-panel lp-fc-p11"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Doble Choco</div><div class="lp-fc-desc">Relleno de coulant de chocolate y ba&#241;ado en chocolate negro</div></div>
                        <div class="lp-fc-panel lp-fc-p12"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Pistacho</div><div class="lp-fc-desc">Relleno de crema de pistacho y ba&#241;ado en chocolate blanco</div></div>
                        <div class="lp-fc-panel lp-fc-p13"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Choco Vegano</div><div class="lp-fc-desc">Ba&#241;ado en chocolate negro &#8212; apto para veganos</div></div>
                        <div class="lp-fc-panel lp-fc-p14"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Choco Avellana</div><div class="lp-fc-desc">Relleno de pralin&#233; de avellana y ba&#241;ado en chocolate con leche y avellana</div></div>
                        <div class="lp-fc-panel lp-fc-p15"><span class="lp-fc-cat">Locob&#243; &#8212; Dulces</span><div class="lp-fc-fname">Frambuesa</div><div class="lp-fc-desc">Ba&#241;ado en chocolate blanco y frambuesa</div></div>
                    </div>
                    <div class="lp-fc-nav">
                        <div class="lp-fc-ba">
                            <label for="lp-fc15" class="lp-fc-btn lp-fc-p1-pr">&#8249;</label>
                            <label for="lp-fc1" class="lp-fc-btn lp-fc-p2-pr">&#8249;</label>
                            <label for="lp-fc2" class="lp-fc-btn lp-fc-p3-pr">&#8249;</label>
                            <label for="lp-fc3" class="lp-fc-btn lp-fc-p4-pr">&#8249;</label>
                            <label for="lp-fc4" class="lp-fc-btn lp-fc-p5-pr">&#8249;</label>
                            <label for="lp-fc5" class="lp-fc-btn lp-fc-p6-pr">&#8249;</label>
                            <label for="lp-fc6" class="lp-fc-btn lp-fc-p7-pr">&#8249;</label>
                            <label for="lp-fc7" class="lp-fc-btn lp-fc-p8-pr">&#8249;</label>
                            <label for="lp-fc8" class="lp-fc-btn lp-fc-p9-pr">&#8249;</label>
                            <label for="lp-fc9" class="lp-fc-btn lp-fc-p10-pr">&#8249;</label>
                            <label for="lp-fc10" class="lp-fc-btn lp-fc-p11-pr">&#8249;</label>
                            <label for="lp-fc11" class="lp-fc-btn lp-fc-p12-pr">&#8249;</label>
                            <label for="lp-fc12" class="lp-fc-btn lp-fc-p13-pr">&#8249;</label>
                            <label for="lp-fc13" class="lp-fc-btn lp-fc-p14-pr">&#8249;</label>
                            <label for="lp-fc14" class="lp-fc-btn lp-fc-p15-pr">&#8249;</label>
                        </div>
                        <div class="lp-fc-dots">
                            <label for="lp-fc1" class="lp-fc-dot"></label>
                            <label for="lp-fc2" class="lp-fc-dot"></label>
                            <label for="lp-fc3" class="lp-fc-dot"></label>
                            <label for="lp-fc4" class="lp-fc-dot"></label>
                            <label for="lp-fc5" class="lp-fc-dot"></label>
                            <label for="lp-fc6" class="lp-fc-dot"></label>
                            <label for="lp-fc7" class="lp-fc-dot"></label>
                            <label for="lp-fc8" class="lp-fc-dot"></label>
                            <label for="lp-fc9" class="lp-fc-dot"></label>
                            <label for="lp-fc10" class="lp-fc-dot"></label>
                            <label for="lp-fc11" class="lp-fc-dot"></label>
                            <label for="lp-fc12" class="lp-fc-dot"></label>
                            <label for="lp-fc13" class="lp-fc-dot"></label>
                            <label for="lp-fc14" class="lp-fc-dot"></label>
                            <label for="lp-fc15" class="lp-fc-dot"></label>
                        </div>
                        <span class="lp-fc-ctr"></span>
                        <div class="lp-fc-ba">
                            <label for="lp-fc2" class="lp-fc-btn lp-fc-p1-nx">&#8250;</label>
                            <label for="lp-fc3" class="lp-fc-btn lp-fc-p2-nx">&#8250;</label>
                            <label for="lp-fc4" class="lp-fc-btn lp-fc-p3-nx">&#8250;</label>
                            <label for="lp-fc5" class="lp-fc-btn lp-fc-p4-nx">&#8250;</label>
                            <label for="lp-fc6" class="lp-fc-btn lp-fc-p5-nx">&#8250;</label>
                            <label for="lp-fc7" class="lp-fc-btn lp-fc-p6-nx">&#8250;</label>
                            <label for="lp-fc8" class="lp-fc-btn lp-fc-p7-nx">&#8250;</label>
                            <label for="lp-fc9" class="lp-fc-btn lp-fc-p8-nx">&#8250;</label>
                            <label for="lp-fc10" class="lp-fc-btn lp-fc-p9-nx">&#8250;</label>
                            <label for="lp-fc11" class="lp-fc-btn lp-fc-p10-nx">&#8250;</label>
                            <label for="lp-fc12" class="lp-fc-btn lp-fc-p11-nx">&#8250;</label>
                            <label for="lp-fc13" class="lp-fc-btn lp-fc-p12-nx">&#8250;</label>
                            <label for="lp-fc14" class="lp-fc-btn lp-fc-p13-nx">&#8250;</label>
                            <label for="lp-fc15" class="lp-fc-btn lp-fc-p14-nx">&#8250;</label>
                            <label for="lp-fc1" class="lp-fc-btn lp-fc-p15-nx">&#8250;</label>
                        </div>
                    </div>
                </div>
                <div class="lp-fc-img-wrap">
                    <img src="https://locopolostg.wpenginepowered.com/wp-content/uploads/prueba-resol-72ppp-1.jpg" alt="Producto Locopolo" loading="lazy">
                    <span class="lp-fc-new">NEW</span>
                </div>
            </div>
        </div>
    </div>
</div>
'@

$content = $content.Replace('</style>', $newCss + "</style>")

$cartaMarkerIdx = $content.IndexOf('Nuestros productos')
$cartaStart = $content.LastIndexOf("`n`n<div", $cartaMarkerIdx)
$beneficiosMarkerIdx = $content.IndexOf('class="lp-section lp-section-dark"')
$cartaEnd = $content.LastIndexOf("`n`n<div", $beneficiosMarkerIdx)

Write-Host "Carta: $cartaStart a $cartaEnd (eliminando $($cartaEnd-$cartaStart) chars)"
$content = $content.Remove($cartaStart, $cartaEnd - $cartaStart)
$content = $content.Insert($cartaStart, $newSection)
Write-Host "Nuevo total: $($content.Length) chars"

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
Write-Host "Hora: $timeStr"
