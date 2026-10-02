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

    public function mount(): void
    {
        Cache::forever(self::SHELL_PID_KEY, getmypid());
        Screen::keepAwake();
    }

    public function render(): View
    {
        return view('native.home');
    }
}
