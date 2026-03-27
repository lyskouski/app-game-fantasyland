@echo off
REM Script to apply screen always-on patch to NativePHP MainActivity
REM Run this after: php artisan native:install

set MAINACTIVITY_FILE=nativephp\android\app\src\main\java\com\nativephp\mobile\ui\MainActivity.kt

if not exist "%MAINACTIVITY_FILE%" (
    echo Error: MainActivity.kt not found at %MAINACTIVITY_FILE%
    echo Make sure to run 'php artisan native:install' first
    exit /b 1
)

echo Applying screen always-on patch to MainActivity.kt...

REM Check if WindowManager import already exists
findstr /C:"import android.view.WindowManager" "%MAINACTIVITY_FILE%" >nul
if %errorlevel% neq 0 (
    echo Adding WindowManager import...
    powershell -Command "(Get-Content '%MAINACTIVITY_FILE%') -replace 'import android\.view\.ViewGroup', 'import android.view.ViewGroup^nimport android.view.WindowManager' | Set-Content '%MAINACTIVITY_FILE%'"
)

REM Check if FLAG_KEEP_SCREEN_ON already exists
findstr /C:"FLAG_KEEP_SCREEN_ON" "%MAINACTIVITY_FILE%" >nul
if %errorlevel% neq 0 (
    echo Adding FLAG_KEEP_SCREEN_ON to onCreate^(^)...
    powershell -Command "(Get-Content '%MAINACTIVITY_FILE%') -replace 'instance = this', 'instance = this^n^n        // Keep screen always on while app is running^n        window.addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON)' | Set-Content '%MAINACTIVITY_FILE%'"
)

echo.
echo ✅ Screen always-on patch applied successfully!
echo The app will now keep the screen on while running.
pause