@echo off
echo ======================================
echo   Starting CMS Development Server
echo ======================================
echo.

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: PHP is not installed
    pause
    exit /b 1
)

cd ..

echo Starting PHP development server on port 8000...
echo.
echo Access your site at:
echo   • Main Launch Page:  http://localhost:8000/launch/
echo   • Public Site:       http://localhost:8000/public/
echo   • Admin Panel:       http://localhost:8000/admin/login.php
echo.
echo Default admin credentials:
echo   Username: admin
echo   Password: admin123
echo.
echo Press Ctrl+C to stop the server
echo ======================================
echo.

php -S localhost:8000
