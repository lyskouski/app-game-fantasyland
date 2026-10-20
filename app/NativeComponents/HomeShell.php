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

    /**
     * Bumped on every tab press: the native webview reloads only when `src`
     * changes, so re-pressing the active tab would otherwise keep the page
     * navigated inside it instead of reopening the tab's initial one.
     */
    public int $reload = 0;

    public function mount(): void
    {
        Cache::forever(self::SHELL_PID_KEY, getmypid());
        Screen::keepAwake();
    }

    public function showProfile(): void
    {
        $this->open(self::TAB_PROFILE);
    }

    public function showHome(): void
    {
        $this->open(self::TAB_HOME);
    }

    public function showChat(): void
    {
        $this->open(self::TAB_CHAT);
    }

    public function showForum(): void
    {
        $this->open(self::TAB_FORUM);
    }

    private function open(string $tab): void
    {
        $this->activeTab = $tab;
        $this->reload++;
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

    public function webviewSrc(): string
    {
        return $this->reload > 0 ? $this->source() . '?_r=' . $this->reload : $this->source();
    }

    public function render(): View
    {
        return view('native.home', ['src' => $this->webviewSrc()]);
    }
}
