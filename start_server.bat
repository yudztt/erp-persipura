@echo off
echo ========================================
echo    ERP PERSIPURA DEVELOPMENT SERVER
echo ========================================
echo.
echo Starting Laravel development server...
echo.
echo Website akan tersedia di:
echo http://127.0.0.1:8000
echo http://localhost:8000
echo.
echo Login dengan:
echo Email: admin@persipura.id
echo Password: admin123
echo.
echo Tekan Ctrl+C untuk menghentikan server
echo ========================================
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause