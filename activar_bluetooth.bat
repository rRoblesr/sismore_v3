@echo off
setlocal EnableExtensions

net session >nul 2>&1
if %errorlevel% neq 0 (
  powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -FilePath '%~f0' -Verb RunAs"
  exit /b
)

echo ==========================================================
echo  Activando Bluetooth (servicios + habilitar dispositivo)
echo ==========================================================

echo.
echo [1/3] Configurando e iniciando servicio Bluetooth (bthserv)...
sc config bthserv start= auto >nul 2>&1
sc start bthserv >nul 2>&1

echo [2/3] Iniciando servicios de emparejamiento/dispositivos...
sc start DeviceAssociationService >nul 2>&1
sc start DevQueryBroker >nul 2>&1

echo.
echo [3/3] Intentando habilitar dispositivos Bluetooth (PnP)...
powershell -NoProfile -ExecutionPolicy Bypass -Command ^
  "$ErrorActionPreference='SilentlyContinue';" ^
  "$devs = Get-PnpDevice -Class Bluetooth -ErrorAction SilentlyContinue;" ^
  "if(-not $devs){ Write-Host 'No se detectan dispositivos Bluetooth en PnP (puede ser driver/adaptador).'; exit }" ^
  "$toEnable = $devs | Where-Object { $_.Status -in @('Error','Disabled','Unknown') -or $_.Problem -ne 0 };" ^
  "foreach($d in $toEnable){ try { Enable-PnpDevice -InstanceId $d.InstanceId -Confirm:$false | Out-Null } catch {} }" ^
  "Write-Host 'Dispositivos Bluetooth detectados:';" ^
  "$devs | Select-Object Status, FriendlyName, Class | Format-Table -AutoSize | Out-String | Write-Host;"

echo.
echo Abriendo Configuracion de Bluetooth...
start "" ms-settings:bluetooth

echo.
pause
endlocal
