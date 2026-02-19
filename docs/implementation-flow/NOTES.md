# Installation

https://nativephp.com/docs/mobile/3/getting-started/quick-start

Require at least PHP 8.3

sudo apt install php8.4-xml - required dependency
sudo apt install php8.4-zip - required dependency

composer create-project laravel/laravel .

## Install NativePHP for Mobile into a new Laravel app
composer require nativephp/mobile

## Ready your app to go native
php artisan native:install

## Run your app on a mobile device
php artisan native:run

# Error during a startup

## 1

```
Starting a Gradle Daemon (subsequent builds will be faster)

> Configure project :app
WARNING: The following problems were found when resolving the SDK location:
Where: sdk.dir property in local.properties file. Problem: Set with empty value


FAILURE: Build failed with an exception.
```
Solution:
```
export ANDROID_HOME=$HOME/Android/Sdk
echo 'export ANDROID_HOME=$HOME/Android/Sdk' >> ~/.bashrc
source ~/.bashrc
```

nativephp/android/local.properties
+ sdk.dir=/home/vlyskouski/Android/Sdk


## 2
APK installation failed

 adb: failed to install /home/vlyskouski/Work/_tercad/fantasyland/nativephp/android/app/build/outputs/apk/debug/app-debug.apk: Failure [INSTALL_FAILED_NO_MATCHING_ABIS: Failed to extract native libraries, res=-113]


The issue is that the APK is only built for arm64-v8a ABI, but your emulator (Pixel_7_API_30) likely uses x86_64 architecture. I've updated the build.gradle.kts to include both ABIs.

Run php artisan native:run again. It should rebuild the APK with support for both arm64-v8a and x86_64, allowing installation on your emulator.

nativephp/android/app/build.gradle.kts

android {
    defaultConfig {
        ndk {
            abiFilters.add("arm64-v8a")
+           abiFilters.add("x86_64")
        }
    }

... CMakeLists.txt

- set(PHP_LIB_DIR ${CMAKE_CURRENT_SOURCE_DIR}/../jniLibs/arm64-v8a)
+ set(PHP_LIB_DIR ${CMAKE_CURRENT_SOURCE_DIR}/../jniLibs/${ANDROID_ABI})

Since NativePHP mobile apps are primarily designed for ARM64 devices, use the ARM64 emulator.

echo "no" | /home/vlyskouski/Android/Sdk/cmdline-tools/latest/bin/avdmanager create avd -n Pixel_ARM64 -k 'system-images;android-30;google_apis;arm64-v8a'
/home/vlyskouski/Android/Sdk/cmdline-tools/latest/bin/avdmanager list avd

vlyskouski@VLV-desk ~/Work/_tercad/fantasyland (main)$ /home/vlyskouski/Android/Sdk/emulator/emulator -avd Pixel_ARM64
INFO         | Android emulator version 36.4.9.0 (build_id 14788078) (CL:N/A)
INFO         | Graphics backend: gfxstream
INFO         | Found systemPath /home/vlyskouski/Android/Sdk/system-images/android-30/google_apis/arm64-v8a/
FATAL        | Avd's CPU Architecture 'arm64' is not supported by the QEMU2 emulator on x86_64 host. System image must match the host architecture.

## 3

Warning: Unknown: Failed to open stream: No such file or directory in Unknown on line 0 Fatal error: Failed opening required '/data/user/0/com.tercad.fantasyland/app_storage/laravel/vendor/nativephp/mobile/bootstrap/android/native.php' (include_path='.:') in Unknown on line 0

