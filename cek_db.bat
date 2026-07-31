@echo off
set PHP=C:\laragon\bin\php\php-8.3.12-nts-Win32-vs16-x64\php.exe
set MYSQL=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe
cd c:\laragon\www\rumahsakit

echo === CEK TABEL DAN JUMLAH DATA ===
%MYSQL% -u root -e "SELECT TABLE_NAME, TABLE_ROWS FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'rumasakit' ORDER BY TABLE_NAME;"

echo.
echo === CEK USER ===
%MYSQL% -u root rumasakit -e "SELECT id, username, email, role, status FROM users LIMIT 5;"

echo.
echo === CEK MIGRATE STATUS ===
%PHP% artisan migrate:status
