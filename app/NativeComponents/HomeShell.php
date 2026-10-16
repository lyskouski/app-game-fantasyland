<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\NativeComponents;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Native\Mobile\Edge\NativeComponent;
use SRWieZ\NativePHP\Mobile\Screen\Facades\Screen;

class HomeShell extends NativeComponent
{
    public const SHELL_PID_KEY = 'native_shell_pid';

    public const TAB_PROFILE = 'profile';
    public const TAB_HOME = 'home';
    public const TAB_CHAT = 'chat';
    public const TAB_FORUM = 'forum';

    public string $activeTab = self::TAB_HOME;

    public function mount(): void
    {
        Cache::forever(self::SHELL_PID_KEY, getmypid());
        Screen::keepAwake();
    }

    public function showProfile(): void
    {
        $this->activeTab = self::TAB_PROFILE;
    }

    public function showHome(): void
    {
        $this->activeTab = self::TAB_HOME;
    }

    public function showChat(): void
    {
        $this->activeTab = self::TAB_CHAT;
    }

    public function showForum(): void
    {
        $this->activeTab = self::TAB_FORUM;
    }

    public function source(): string
    {
        return match ($this->activeTab) {
            self::TAB_PROFILE => '/cgi/show_info.php',
            self::TAB_HOME => '/cgi/no_combat.php',
            self::TAB_CHAT => '/ch/chout.php',
            self::TAB_FORUM => '/cgi/forum_rooms.php',
        };
    }

    public function render(): View
    {
        return view('native.home', ['src' => $this->source()]);
    }
}
