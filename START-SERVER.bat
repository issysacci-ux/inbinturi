@echo off
title BoostCafe Inventory - Local Server
color 0B

echo ==================================================
echo    BOOSTCAFE INVENTORY - LOCAL NETWORK MODE
echo ==================================================
echo.

echo [1/2] Starting MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>nul | find /I "mysqld.exe" >nul
if errorlevel 1 start "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file=C:\xampp\mysql\bin\my.ini

echo [2/2] Starting Apache...
tasklist /FI "IMAGENAME eq httpd.exe" 2>nul | find /I "httpd.exe" >nul
if errorlevel 1 start "" "C:\xampp\apache\bin\httpd.exe"

timeout /t 5 /nobreak >nul

echo.
echo ==================================================
echo   ALL SET! The system is now running.
echo.
echo   On this PC:
echo     http://localhost/boostcafe/
echo.
echo   On phones and other PCs (same WiFi/network):
echo     http://20.20.20.101/boostcafe/
echo.
echo   DO NOT close the XAMPP services while in use.
echo   You may close this window safely.
echo ==================================================
echo.
pause
