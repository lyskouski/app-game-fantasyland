#!/bin/bash

# Script to apply screen always-on patch to NativePHP MainActivity
# Run this after: php artisan native:install

MAINACTIVITY_FILE="nativephp/android/app/src/main/java/com/nativephp/mobile/ui/MainActivity.kt"

if [ ! -f "$MAINACTIVITY_FILE" ]; then
    echo "Error: MainActivity.kt not found at $MAINACTIVITY_FILE"
    echo "Make sure to run 'php artisan native:install' first"
    exit 1
fi

echo "Applying screen always-on patch to MainActivity.kt..."

# 1. Add WindowManager import if not present
if ! grep -q "import android.view.WindowManager" "$MAINACTIVITY_FILE"; then
    echo "Adding WindowManager import..."
    sed -i '/import android\.view\.ViewGroup/a import android.view.WindowManager' "$MAINACTIVITY_FILE"
fi

# 2. Add FLAG_KEEP_SCREEN_ON after instance = this
if ! grep -q "FLAG_KEEP_SCREEN_ON" "$MAINACTIVITY_FILE"; then
    echo "Adding FLAG_KEEP_SCREEN_ON to onCreate()..."
    sed -i '/instance = this/a \\n        \/\/ Keep screen always on while app is running\n        window.addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON)' "$MAINACTIVITY_FILE"
fi

echo "✅ Screen always-on patch applied successfully!"
echo "The app will now keep the screen on while running."