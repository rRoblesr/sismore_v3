@echo off
setlocal enabledelayedexpansion

if /i "%~1" neq "__INTERNAL__" (
  rem Abrir una consola que se quede abierta y ejecute este mismo BAT
  start "LIMPIEZA PRODUCCION" cmd /k ""%~f0" __INTERNAL__"
  exit /b
)

echo --- LIMPIEZA / OPTIMIZACION PARA SERVIDOR (PRODUCCION) ---
echo Directorio: %~dp0
echo.

cd /d "%~dp0"

if not exist "logs" mkdir "logs"
for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HH-mm-ss"') do set "TS=%%i"
set "LOG=logs\limpieza_prod_%TS%.log"

echo Log: %LOG%
echo.

echo --- INICIO %date% %time% --- > "%LOG%"
echo Directorio: %cd%>> "%LOG%"

echo 0. Verificando PHP...
where php >> "%LOG%" 2>&1
if errorlevel 1 (
  echo ERROR: No se encontro "php" en PATH. >> "%LOG%"
  echo ERROR: No se encontro "php" en PATH.
  echo Agrega PHP al PATH o ejecuta este BAT desde la consola de XAMPP.
  echo.
  type "%LOG%"
  pause
  exit /b 1
)
php -v >> "%LOG%" 2>&1

if not exist ".env" goto :AFTER_ENV
goto :SET_ENV

:SET_ENV
echo 0. Asegurando variables de entorno para produccion (.env)...
echo 0. Asegurando variables de entorno para produccion (.env)...>> "%LOG%"
attrib -R ".env" >> "%LOG%" 2>&1
if not exist "scripts\\set_env_produccion.ps1" goto :ENV_SCRIPT_MISSING
powershell -NoProfile -ExecutionPolicy Bypass -File "scripts\\set_env_produccion.ps1" -EnvPath ".env" >> "%LOG%" 2>&1
echo   Valores actuales en .env:>> "%LOG%"
findstr /R /C:"^APP_ENV=" /C:"^APP_DEBUG=" ".env" >> "%LOG%" 2>&1
echo   Valores actuales en .env:
findstr /R /C:"^APP_ENV=" /C:"^APP_DEBUG=" ".env"
goto :AFTER_ENV

:ENV_SCRIPT_MISSING
echo ERROR: Falta scripts\\set_env_produccion.ps1 (no se actualiza .env).
echo ERROR: Falta scripts\\set_env_produccion.ps1 (no se actualiza .env).>> "%LOG%"
goto :AFTER_ENV

:AFTER_ENV
if not exist ".env" (
  echo 0. No existe .env ^(se omite ajuste APP_ENV/APP_DEBUG^).
  echo 0. No existe .env ^(se omite ajuste APP_ENV/APP_DEBUG^).>> "%LOG%"
)

echo.
echo 1. Limpiando caches...
echo 1. Limpiando caches...>> "%LOG%"
php artisan cache:clear >> "%LOG%" 2>&1
php artisan route:clear >> "%LOG%" 2>&1
php artisan config:clear >> "%LOG%" 2>&1
php artisan view:clear >> "%LOG%" 2>&1
php artisan optimize:clear >> "%LOG%" 2>&1

echo.
echo 2. Regenerando caches para produccion (si aplica)...
echo 2. Regenerando caches para produccion (si aplica)...>> "%LOG%"
php artisan config:cache >> "%LOG%" 2>&1
if errorlevel 1 (
  echo   - config:cache fallo. Revisa permisos y la configuracion del servidor.
  echo   - config:cache fallo.>> "%LOG%"
) else (
  echo   - config:cache OK
  echo   - config:cache OK>> "%LOG%"
)

php artisan route:cache >> "%LOG%" 2>&1
if errorlevel 1 (
  echo   - route:cache fallo ^(suele pasar si hay rutas con closures^). Se mantiene sin cache de rutas.
  echo   - route:cache fallo.>> "%LOG%"
  php artisan route:clear >> "%LOG%" 2>&1
) else (
  echo   - route:cache OK
  echo   - route:cache OK>> "%LOG%"
)

php artisan view:cache >> "%LOG%" 2>&1
if errorlevel 1 (
  echo   - view:cache fallo. Se mantiene sin cache de vistas.
  echo   - view:cache fallo.>> "%LOG%"
  php artisan view:clear >> "%LOG%" 2>&1
) else (
  echo   - view:cache OK
  echo   - view:cache OK>> "%LOG%"
)

php artisan event:cache >> "%LOG%" 2>&1
if errorlevel 1 (
  echo   - event:cache fallo. Se mantiene sin cache de eventos.
  echo   - event:cache fallo.>> "%LOG%"
  php artisan event:clear >> "%LOG%" 2>&1
) else (
  echo   - event:cache OK
  echo   - event:cache OK>> "%LOG%"
)

echo.
echo 3. Importante: si el servidor usa OPcache, reinicia Apache/PHP-FPM para aplicar cambios de codigo.
echo    - En XAMPP Windows: reinicia Apache desde el panel.
echo.
echo --- PROCESO TERMINADO ---
echo --- FIN %date% %time% --- >> "%LOG%"
echo.
echo Mostrando log:
type "%LOG%"
pause

