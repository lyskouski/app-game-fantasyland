<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

// php artisan test tests/Feature/AgeAssuranceTest.php

namespace Tests\Feature;

use App\Services\AgeAssurance;
use App\Services\SecureStorage;
use Tests\TestCase;

class AgeAssuranceTest extends TestCase
{
    private string $originalPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->originalPath = SecureStorage::$storagePath;
        SecureStorage::$storagePath = 'testing_secure_storage_';
        $this->clearStorage();
    }

    protected function tearDown(): void
    {
        $this->clearStorage();
        SecureStorage::$storagePath = $this->originalPath;
        parent::tearDown();
    }

    private function clearStorage(): void
    {
        foreach (['birth_year', 'parental_pin', 'parental_social_lock'] as $key) {
            SecureStorage::delete($key);
        }
    }

    public function test_undeclared_age_redirects_to_age_screen(): void
    {
        $this->get('/')->assertRedirect('/age');
        $this->get('/ch/chout.php')->assertRedirect('/age');
        $this->get('/age')->assertOk()->assertSee('Год рождения');
    }

    public function test_declare_age(): void
    {
        $this->post('/age', ['birth_year' => now()->year - 20])->assertRedirect('/');
        $this->assertTrue(AgeAssurance::isAdult());
    }

    public function test_invalid_birth_year_is_rejected(): void
    {
        $this->post('/age', ['birth_year' => now()->year + 1])->assertSessionHasErrors('birth_year');
        $this->post('/age', ['birth_year' => 'abc'])->assertSessionHasErrors('birth_year');
        $this->assertFalse(AgeAssurance::isDeclared());
    }

    public function test_declared_age_cannot_be_changed(): void
    {
        $this->post('/age', ['birth_year' => now()->year - 10]);
        $this->post('/age', ['birth_year' => now()->year - 30]);
        $this->assertFalse(AgeAssurance::isAdult());
    }

    public function test_minor_is_restricted_from_the_app(): void
    {
        $this->post('/age', ['birth_year' => now()->year - AgeAssurance::ADULT_AGE]);
        foreach (['/', '/registration', '/cgi/no_combat.php', '/ch/chout.php', '/cgi/forum_rooms.php', '/cgi/msgs_read.php', '/parental'] as $url) {
            $this->get($url)->assertForbidden()->assertSee('Доступ ограничен');
        }
        $this->get('/age')->assertRedirect('/');
    }

    public function test_parental_lock_requires_pin(): void
    {
        $this->post('/age', ['birth_year' => now()->year - 30]);

        $this->post('/parental', ['social_lock' => '1'])->assertSessionHasErrors('new_pin');
        $this->assertFalse(AgeAssurance::isSocialLocked());

        $this->post('/parental', ['social_lock' => '1', 'new_pin' => '1234', 'new_pin_confirmation' => '1234'])
            ->assertRedirect('/parental');
        $this->assertTrue(AgeAssurance::isSocialLocked());
        $this->get('/ch/chout.php')->assertForbidden();

        $this->post('/parental', ['pin' => '0000'])->assertSessionHasErrors('pin');
        $this->assertTrue(AgeAssurance::isSocialLocked());

        $this->post('/parental', ['pin' => '1234'])->assertRedirect('/parental');
        $this->assertFalse(AgeAssurance::isSocialLocked());
        $this->assertTrue(AgeAssurance::isSocialAllowed());
    }

    public function test_remove_pin_lifts_lock(): void
    {
        $this->post('/age', ['birth_year' => now()->year - 30]);
        $this->post('/parental', ['social_lock' => '1', 'new_pin' => '1234', 'new_pin_confirmation' => '1234']);

        $this->post('/parental', ['pin' => '1234', 'social_lock' => '1', 'remove_pin' => '1'])->assertRedirect('/parental');
        $this->assertFalse(AgeAssurance::hasPin());
        $this->assertFalse(AgeAssurance::isSocialLocked());
    }

    public function test_parental_page_renders(): void
    {
        $this->post('/age', ['birth_year' => now()->year - 30]);
        $this->get('/parental')->assertOk()->assertSee('Родительский контроль');
    }
}
