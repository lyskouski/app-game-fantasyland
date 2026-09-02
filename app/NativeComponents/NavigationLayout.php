<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\NativeComponents;

use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\Builders\Tab;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

class NavigationLayout extends NativeLayout
{
    public function tabBar(NativeComponent $screen): ?TabBar
    {
        return TabBar::make()
            ->dark()
            ->activeColor('#0891b2')
            ->labelVisibility('labeled')
            ->add(Tab::link('Профиль', '/shell/profile', icon: 'person'))
            ->add(Tab::link('Главная', '/shell/home',    icon: 'home'))
            ->add(Tab::link('Чат',     '/shell/chat',    icon: 'chat_bubble'))
            ->add(Tab::link('Форум',   '/shell/forum',   icon: 'forum'));
    }
}
