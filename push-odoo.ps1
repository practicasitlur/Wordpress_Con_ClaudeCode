# push-odoo.ps1
# para ejecutar: powershell -ExecutionPolicy Bypass -File .\push-odoo.ps1
# Hace commit y push al repositorio de Odoo SIEMPRE en la rama DEVELOPMENT

$repoPath = "C:\Users\Lur\Documents\lur\modulos_odoo"

Set-Location $repoPath

# Verificar que estamos en development — bloqueo de seguridad
$branch = git branch --show-current
if ($branch -ne "development") {
    Write-Host "BLOQUEADO: Estas en la rama '$branch'. Este script solo permite push a 'development'." -ForegroundColor Red
    exit 1
}

Write-Host "Rama actual: $branch" -ForegroundColor Green

# Mostrar cambios pendientes
$status = git status --porcelain
if ([string]::IsNullOrWhiteSpace($status)) {
    Write-Host "No hay cambios para commitear." -ForegroundColor Yellow
    exit 0
}

Write-Host "`nArchivos con cambios:" -ForegroundColor Cyan
git status --short

# Pedir mensaje de commit
Write-Host ""
$message = Read-Host "Mensaje del commit"

if ([string]::IsNullOrWhiteSpace($message)) {
    Write-Host "ERROR: El mensaje del commit no puede estar vacio." -ForegroundColor Red
    exit 1
}

# Staging y commit
git add .
git commit -m $message

if (-not $?) {
    Write-Host "ERROR al hacer commit." -ForegroundColor Red
    exit 1
}

# Push a development
Write-Host "`nHaciendo push a origin/development..." -ForegroundColor Cyan
git push origin development

if ($?) {
    Write-Host "Push completado correctamente en la rama development." -ForegroundColor Green
} else {
    Write-Host "ERROR al hacer push." -ForegroundColor Red
    exit 1
}
