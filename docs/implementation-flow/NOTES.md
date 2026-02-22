# Installation

https://nativephp.com/docs/mobile/3/getting-started/quick-start

Require at least PHP 8.3
sudo apt install php8.3 php8.3-cli php8.3-zip php8.3-mbstring
sudo update-alternatives --set php /usr/bin/php8.3

sudo apt install php8.4-xml - required dependency
sudo apt install php8.4-zip - required dependency
sudo apt install zip unzip - required dependency

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

## 1.1
WARNING: The following problems were found when resolving the SDK location:
Where: sdk.dir property in local.properties file. Problem: Set with empty value

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

### Solution:

Fixed three issues in LaravelEnvironment.kt:

1. **Storage path issue**: Changed from `context.getDir("storage", Context.MODE_PRIVATE)` to `File(context.dataDir, "app_storage")` to correctly use the app's data directory

2. **ZIP extraction issue**: The unzip function was loading entire files into memory before writing. This could fail with large bundles. Refactored to stream directly from ZIP to disk.

3. **Version detection failure**: The code was stopping extraction when it couldn't read the version from the bundle. Fixed to proceed with extraction regardless, as long as Laravel hasn't been extracted yet.

### Changes made:

- Line 19: `private val appStorageDir = File(context.dataDir, "app_storage")`
- Line 91: `val appStorageDir = File(context.dataDir, "app_storage")`
- Lines 569-600: Refactored unzip() to stream directly instead of buffering
- Lines 186-200: Modified to continue extraction even when version can't be read

Result: ✅ Laravel bundle now extracts successfully, native.php file is accessible

### 3.1

DEBUG: Request handling error: Illuminate\Routing\UrlGenerator::__construct(): Argument #2 ($request) must be of type Illuminate\Http\Request, null given, called in /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Routing/RoutingServiceProvider.php on line 63 DEBUG: Error type: TypeError DEBUG: Trace: #0 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Routing/RoutingServiceProvider.php(63): Illuminate\Routing\UrlGenerator->__construct(Object(Illuminate\Routing\RouteCollection), NULL, 'http://127.0.0....') #1 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php(1115): Illuminate\Routing\RoutingServiceProvider->{closure:Illuminate\Routing\RoutingServiceProvider::registerUrlGenerator():55}(Object(Illuminate\Foundation\Application), Array) #2 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php(933): Illuminate\Container\Container->build(Object(Closure)) #3 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1078): Illuminate\Container\Container->resolve('url', Array, true) #4 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php(864): Illuminate\Foundation\Application->resolve('url', Array) #5 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1058): Illuminate\Container\Container->make('url', Array) #6 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php(1805): Illuminate\Foundation\Application->make('url') #7 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(240): Illuminate\Container\Container->offsetGet('url') #8 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(211): Illuminate\Support\Facades\Facade::resolveFacadeInstance('url') #9 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php(357): Illuminate\Support\Facades\Facade::getFacadeRoot() #10 /data/data/com.tercad.fantasyland/app_storage/laravel/app/Providers/AppServiceProvider.php(23): Illuminate\Support\Facades\Facade::__callStatic('forceHttps', Array) #11 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\Providers\AppServiceProvider->boot() #12 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\Container\BoundMethod::{closure:Illuminate\Container\BoundMethod::call():35}() #13 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\Container\Util::unwrapIfClosure(Object(Closure)) #14 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure)) #15 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL) #16 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1151): Illuminate\Container\Container->call(Array) #17 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1132): Illuminate\Foundation\Application->bootProvider(Object(App\Providers\AppServiceProvider)) #18 [internal function]: Illuminate\Foundation\Application->{closure:Illuminate\Foundation\Application::boot():1131}(Object(App\Providers\AppServiceProvider), 'App\\Providers\\A...') #19 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1131): array_walk(Array, Object(Closure)) #20 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/BootProviders.php(17): Illuminate\Foundation\Application->boot() #21 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(342): Illuminate\Foundation\Bootstrap\BootProviders->bootstrap(Object(Illuminate\Foundation\Application)) #22 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(186): Illuminate\Foundation\Application->bootstrapWith(Array) #23 /data/data/com.tercad.fantasyland/app_storage/laravel/vendor/nativephp/mobile/bootstrap/android/native.php(77): Illuminate\Foundation\Http\Kernel->bootstrap() #24 {main}

https://github.com/NativePHP/mobile-starter/issues/1
