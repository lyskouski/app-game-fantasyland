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
