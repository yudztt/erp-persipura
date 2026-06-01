@echo off
echo ============================================
echo   SETUP PERSIPURA ERP
echo ============================================
echo.

echo [1/4] Clearing cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo.
echo [2/4] Running migrations (fresh)...
php artisan migrate:fresh --force

echo.
echo [3/4] Running seeder...
php artisan db:seed --class=UserSeeder --force

echo.
echo [4/4] Setup complete!
echo.
echo ============================================
echo   LOGIN CREDENTIALS
echo ============================================
echo Email    : admin@persipura.id
echo Password : admin123
echo ============================================
echo.
echo Press any key to start server...
pause > nul

php artisan serve
