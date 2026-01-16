@echo off
echo --- INICIANDO LIMPIEZA PROFUNDA DE LARAVEL ---

echo 1. Limpiando cache de aplicacion...
call php artisan cache:clear

echo 2. Limpiando cache de rutas...
call php artisan route:clear

echo 3. Limpiando cache de configuracion...
call php artisan config:clear

echo 4. Limpiando cache de vistas compiladas...
call php artisan view:clear

echo 5. Limpiando todo (optimize:clear)...
call php artisan optimize:clear

echo.
echo --- PROCESO TERMINADO ---
echo Ya puedes probar tus cambios.
pause
