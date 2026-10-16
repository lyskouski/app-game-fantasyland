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

    public string $activeTab = 'home';

    public function mount(): void
    {
        Cache::forever(self::SHELL_PID_KEY, getmypid());
        Screen::keepAwake();
    }

    public function showProfile(): void
    {
        $this->activeTab = 'profile';
    }

    public function showHome(): void
    {
        $this->activeTab = 'home';
    }

    public function showChat(): void
    {
        $this->activeTab = 'chat';
    }

    public function showForum(): void
    {
        $this->activeTab = 'forum';
    }

    public function source(): string
    {
        return match ($this->activeTab) {
            'profile' => '/cgi/show_info.php',
            'home' => '/cgi/no_combat.php',
            'chat' => '/ch/chout.php',
            'forum' => '/cgi/forum_rooms.php',
        };
    }

    public function render(): View
    {
        return view('native.home', ['src' => $this->source()]);
    }
}
