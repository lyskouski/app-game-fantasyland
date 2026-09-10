<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use SRWieZ\NativePHP\Mobile\Screen\Facades\Screen;

class ProfileShell extends NativeComponent
{
    public function mount(): void
    {
        Screen::keepAwake();
    }

    public function render(): View
    {
        return view('native.profile');
    }
}
