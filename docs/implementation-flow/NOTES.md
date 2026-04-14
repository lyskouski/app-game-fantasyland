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

composer install
copy .env.example .env
php artisan key:generate

## Ready your app to go native
php artisan native:install
npm install

### Add the following lines in the .env file:

NATIVEPHP_APP_ID=com.tercad.fantasyland
NATIVEPHP_APP_VERSION=1.0.0
NATIVEPHP_APP_VERSION_CODE=1


## Run your app on a mobile device
npm run build
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
https://stackoverflow.com/questions/79894147/nativephp-new-android-application/79895048


# Apple Distribution

These credentials are generated in App Store Connect. Here's where to find them:

IOS_API_KEY_ID
Go to App Store Connect
Click Users and Access → Integrations → App Store Connect API
Click the + button to create a new API key
Select the role (usually Developer or App Manager)
Click Generate
Copy the Key ID (this is your IOS_API_KEY_ID)
Download the .p8 file (this is the private key you're base64-encoding as IOS_API_KEY)
IOS_API_ISSUER_ID
In the same App Store Connect API section
Look at the top of the page for Issuer ID (appears above the API keys table)
Copy this value (this is your IOS_API_ISSUER_ID)
Note: The Issuer ID is the same for all API keys in your organization and is essentially your Team ID.

Summary for your workflow:
IOS_API_KEY_ID: From the generated App Store Connect API key
IOS_API_ISSUER_ID: Your organization's Issuer ID (visible in App Store Connect API section)
IOS_API_KEY: The .p8 file you download (base64 encoded as shown in line 139)
These should be added as secrets in your GitHub repository settings under Settings → Secrets and variables → Actions.

Option 1: Create a new Provisioning Profile for com.tercad.fantasyland
Go to Apple Developer
Certificates, Identifiers & Profiles → Identifiers
Click + to register a new App ID if com.tercad.fantasyland doesn't exist
App ID: com.tercad.fantasyland
Description: Fantasyland
Go to Profiles
Click + to create a new provisioning profile
Select App Store
Select the com.tercad.fantasyland App ID
Select your distribution certificate
Name it: Fantasyland Distribution
Download the profile
Base64 encode and add to GitHub Secrets as IOS_PROVISIONING_PROFILE
Option 2: Update .env to match existing profile (Quickest)
If you want to keep using the existing com.tercad.fingrom profile, update your .env:

Recommended: Use Option 1 if this app should be com.tercad.fantasyland. The provisioning profile you currently have is for a different app (fingrom).


# Database creation

php artisan make:migration create_map_table


NOTE: it looks like migrations were not applied during the aplication start. How to fix?

> SQLSTATE[HY000]: General error: 1 no such table: maps (Connection: sqlite, Database: /data/user/0/com.tercad.fantasyland/app_storage/persisted_data/database/database.sqlite, SQL: select * from "maps" where "location_id" = 15 and "place_id" = 11 and "z" = 0)


The issue is a table name mismatch. The migration creates map (singular), but Eloquent's default convention looks for maps (plural).

Fix 1: Update the migration to use maps (recommended)

> Schema::create('maps', function (Blueprint $table) {

Or Fix 2: Tell the Model to use map table
Add this to Map.php:

> protected $table = 'map';


composer require nativephp/mobile-background-tasks
php artisan native:plugin:register nativephp/mobile-background-tasks
