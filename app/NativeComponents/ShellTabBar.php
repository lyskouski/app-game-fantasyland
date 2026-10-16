<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\NativeComponents;

use Native\Mobile\Edge\Layouts\Builders\TabBar;

/**
 * Tabs are actions on a single screen (no URLs), so the framework's
 * URL-based highlight would reset every tab to inactive.
 * The active tab is set explicitly by the layout instead.
 */
class ShellTabBar extends TabBar
{
    public function highlight(string $currentUrl): self
    {
        return $this;
    }
}
