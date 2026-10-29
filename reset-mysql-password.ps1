$ErrorActionPreference = "Stop"

if (-not ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Write-Host "ERROR: Please run this script as Administrator!" -ForegroundColor Red
    pause
    exit 1
}

$mysqlBin = "C:\Program Files\MySQL\MySQL Server 8.0\bin"
$iniFile = "C:\ProgramData\MySQL\MySQL Server 8.0\my.ini"
$newPass = "boostcafe123"

Write-Host "[1/5] Stopping MySQL80 service..." -ForegroundColor Cyan
Stop-Service MySQL80 -Force
Start-Sleep -Seconds 3

Write-Host "[2/5] Creating init file..." -ForegroundColor Cyan
$initFile = Join-Path $env:TEMP "mysql-reset-pass.txt"
Set-Content -Path $initFile -Value "ALTER USER 'root'@'localhost' IDENTIFIED BY '$newPass'; FLUSH PRIVILEGES;" -Encoding ASCII

Write-Host "[3/5] Starting mysqld with init file..." -ForegroundColor Cyan
$proc = Start-Process -FilePath "$mysqlBin\mysqld.exe" -ArgumentList "`"--defaults-file=$iniFile`"", "`"--init-file=$initFile`"", "--console" -PassThru -WindowStyle Hidden

$ready = $false
for ($i = 0; $i -lt 30; $i++) {
    Start-Sleep -Seconds 2
    & "$mysqlBin\mysql.exe" -u root "-p$newPass" -e "SELECT 1;" 2>$null | Out-Null
    if ($LASTEXITCODE -eq 0) { $ready = $true; break }
    if ($proc.HasExited) { break }
}

if ($ready) {
    Write-Host "[4/5] Password reset SUCCESS! New root password: boostcafe123" -ForegroundColor Green
} else {
    Write-Host "Password reset FAILED. Check mysqld output above." -ForegroundColor Red
}

Write-Host "[5/5] Restarting MySQL80 service..." -ForegroundColor Cyan
try { Stop-Process -Id $proc.Id -Force -ErrorAction SilentlyContinue } catch {}
Start-Sleep -Seconds 3
Start-Service MySQL80
Start-Sleep -Seconds 2

Remove-Item $initFile -Force -ErrorAction SilentlyContinue
Write-Host "DONE! You can close this window." -ForegroundColor Green
pause
