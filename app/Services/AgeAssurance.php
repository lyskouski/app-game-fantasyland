<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use Illuminate\Support\Facades\Hash;

/**
 * Age assurance (neutral age screen) and parental controls.
 * Social features (chat, mail, forum) are available only for declared adults,
 * and can additionally be locked by a parent behind a PIN.
 */
final class AgeAssurance
{
    public const ADULT_AGE = 18;

    private const BIRTH_YEAR = 'birth_year';
    private const PIN = 'parental_pin';
    private const SOCIAL_LOCK = 'parental_social_lock';

    public static function birthYear(): ?int
    {
        $value = SecureStorage::get(self::BIRTH_YEAR);
        return $value ? (int) $value : null;
    }

    public static function declare(int $birthYear): void
    {
        SecureStorage::set(self::BIRTH_YEAR, (string) $birthYear);
    }

    public static function isDeclared(): bool
    {
        return self::birthYear() !== null;
    }

    // Only the year is known, so the birthday is assumed to be at its end (the youngest possible age)
    public static function isAdult(): bool
    {
        $birthYear = self::birthYear();
        return $birthYear !== null && now()->year - $birthYear - 1 >= self::ADULT_AGE;
    }

    public static function isSocialLocked(): bool
    {
        return SecureStorage::get(self::SOCIAL_LOCK) === '1';
    }

    public static function setSocialLock(bool $locked): void
    {
        $locked ? SecureStorage::set(self::SOCIAL_LOCK, '1') : SecureStorage::delete(self::SOCIAL_LOCK);
    }

    public static function isSocialAllowed(): bool
    {
        return self::isAdult() && !self::isSocialLocked();
    }

    public static function hasPin(): bool
    {
        return SecureStorage::get(self::PIN) !== null;
    }

    public static function setPin(string $pin): void
    {
        SecureStorage::set(self::PIN, Hash::make($pin));
    }

    public static function checkPin(string $pin): bool
    {
        $hash = SecureStorage::get(self::PIN);
        return $hash !== null && Hash::check($pin, $hash);
    }

    public static function removePin(): void
    {
        SecureStorage::delete(self::PIN);
    }
}
