<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class SecureStorage
{
    public static string $storagePath = 'secure_storage_';

    public static function set(string $key, string $value): void
    {
        $path = storage_path(self::$storagePath . $key);
        $encrypted = encrypt($value);
        file_put_contents($path, $encrypted);
    }

    public static function get(string $key): ?string
    {
        $path = storage_path(self::$storagePath . $key);
        if (!file_exists($path)) {
            return null;
        }
        $encrypted = file_get_contents($path);
        return $encrypted ? decrypt($encrypted) : null;
    }

    public static function delete(string $key): void
    {
        $path = storage_path(self::$storagePath . $key);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
