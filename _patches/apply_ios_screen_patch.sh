#!/bin/bash

# Script to apply screen always-on patch to NativePHP iOS AppDelegate
# Run this after: php artisan native:package ios

IOS_APPDELEGATE_FILE="nativephp/ios/App/App/AppDelegate.swift"

if [ ! -f "$IOS_APPDELEGATE_FILE" ]; then
    echo "Error: AppDelegate.swift not found at $IOS_APPDELEGATE_FILE"
    echo "Make sure to run 'php artisan native:package ios' first"
    exit 1
fi

echo "Applying screen always-on patch to iOS AppDelegate.swift..."

# Check if the screen always-on code is already present
if ! grep -q "isIdleTimerDisabled = true" "$IOS_APPDELEGATE_FILE"; then
    echo "Adding screen always-on functionality to AppDelegate..."
    
    # Find the didFinishLaunchingWithOptions method and add the screen always-on code
    # Look for the return true line and add our code before it
    sed -i.backup '/func application.*didFinishLaunchingWithOptions/,/return true/ {
        /return true/i\
\        // Keep screen always on while app is running\
        UIApplication.shared.isIdleTimerDisabled = true\

    }' "$IOS_APPDELEGATE_FILE"
    
    # Remove backup file
    rm -f "$IOS_APPDELEGATE_FILE.backup"
    
    echo "✅ Screen always-on code added to AppDelegate"
else
    echo "Screen always-on code already present in AppDelegate"
fi

echo "✅ iOS screen always-on patch applied successfully!"
echo "The app will now keep the screen on while running on iOS."