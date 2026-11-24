@echo off
setlocal
title Masjid Finder Launcher
cd /d "%~dp0"

echo Starting Masjid Finder...
echo This runs the website on this PC only. XAMPP is one option for local PHP and MySQL.
echo.

set "PHP_EXE="
for /f "delims=" %%I in ('where php 2^>nul') do if not defined PHP_EXE set "PHP_EXE=%%I"
if not defined PHP_EXE if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"
if not defined PHP_EXE if exist "C:\xampp8.2\php\php.exe" set "PHP_EXE=C:\xampp8.2\php\php.exe"
if not defined PHP_EXE if exist "C:\xampp82\php\php.exe" set "PHP_EXE=C:\xampp82\php\php.exe"
if not defined PHP_EXE if exist "C:\Program Files\XAMPP\php\php.exe" set "PHP_EXE=C:\Program Files\XAMPP\php\php.exe"
if not defined PHP_EXE if exist "C:\tools\xampp\php\php.exe" set "PHP_EXE=C:\tools\xampp\php\php.exe"
if not defined PHP_EXE (
    echo ERROR: No PHP runtime was found on PATH or in common XAMPP folders.
    echo Local setup needs PHP 8.2 or newer. Install XAMPP once, or install PHP separately.
    echo If XAMPP is installed elsewhere, add its php folder to PATH or update this launcher with its php.exe path.
    goto :failed
)

"%PHP_EXE%" -r "exit(PHP_VERSION_ID >= 80200 ? 0 : 1);" >nul 2>&1
if errorlevel 1 (
    echo ERROR: This project requires PHP 8.2 or newer.
    "%PHP_EXE%" --version
    goto :failed
)

if not exist ".env" (
    if not exist ".env.example" (
        echo ERROR: Local app configuration template is missing: .env.example
        goto :failed
    )
    copy /Y ".env.example" ".env" >nul
    if errorlevel 1 (
        echo ERROR: Could not create the local .env file.
        goto :failed
    )
    echo Created local configuration from .env.example.
)

if not exist "vendor\autoload.php" (
    where composer >nul 2>&1
    if errorlevel 1 (
        echo ERROR: Composer is required to install Laravel dependencies.
        echo Install Composer, then double-click this launcher again.
        goto :failed
    )
    echo Installing Laravel dependencies. This may take a few minutes the first time...
    call composer install --no-interaction --prefer-dist
    if errorlevel 1 (
        echo ERROR: Composer could not install Laravel dependencies.
        goto :failed
    )
)

if not exist "node_modules\vite\bin\vite.js" (
    where npm >nul 2>&1
    if errorlevel 1 (
        echo ERROR: Node.js and npm are required to install the frontend assets.
        goto :failed
    )
    echo Installing frontend dependencies. This may take a few minutes the first time...
    call npm install --no-package-lock
    if errorlevel 1 (
        echo ERROR: npm could not install frontend dependencies.
        goto :failed
    )
)

if exist "node_modules\vite\bin\vite.js" (
    set "RUN_VITE=1"
) else if exist "public\build\manifest.json" (
    set "RUN_VITE=0"
) else (
    echo ERROR: Frontend assets are missing after npm install.
    goto :failed
)

if not exist "database\database.sqlite" type nul > "database\database.sqlite"

set "APP_KEY="
for /f "tokens=1,* delims==" %%A in ('findstr /B /C:"APP_KEY=" ".env"') do set "APP_KEY=%%B"
if not defined APP_KEY (
    echo Generating the local application key...
    "%PHP_EXE%" artisan key:generate
    if errorlevel 1 (
        echo ERROR: Could not generate the application key.
        goto :failed
    )
)

echo Preparing the local database...
"%PHP_EXE%" artisan migrate --force
if errorlevel 1 (
    echo ERROR: Database setup failed. Check the database settings in .env.
    goto :failed
)

if not exist "public\storage" (
    echo Linking uploaded images...
    "%PHP_EXE%" artisan storage:link
    if errorlevel 1 (
        echo WARNING: Uploaded images may not appear because the public storage link could not be created.
    )
)

echo Starting Laravel at http://127.0.0.1:8000
start "Masjid Finder - Laravel" /D "%~dp0" cmd /k ""%PHP_EXE%" artisan serve --host=127.0.0.1 --port=8000"

if "%RUN_VITE%"=="1" (
    echo Starting the frontend development server...
    start "Masjid Finder - Vite" /D "%~dp0" cmd /k npm run dev -- --host 127.0.0.1
)

echo.
echo Your browser will open shortly. Keep the server windows open while using the site.
echo The default setup uses a local SQLite database. If you changed .env, make sure its configured database is available.
echo This is local-only: anyone visiting from the internet needs the site deployed to a web host.
timeout /t 3 /nobreak >nul
start "" "http://127.0.0.1:8000"
exit /b 0

:failed
echo.
echo Setup is incomplete. Fix the item above, then double-click this file again.
pause
exit /b 1
