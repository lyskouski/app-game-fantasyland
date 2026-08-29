<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Layouts;

use Native\Mobile\Edge\Layouts\Builders\NavAction;
use Native\Mobile\Edge\Layouts\Builders\NavBar;
use Native\Mobile\Edge\Layouts\Builders\Tab;
use Native\Mobile\Edge\Layouts\Builders\TabBar;
use Native\Mobile\Edge\Layouts\NativeLayout;
use Native\Mobile\Edge\NativeComponent;

class NavigationLayout extends NativeLayout
{
    public function navBar(NativeComponent $screen): ?NavBar
    {
        return NavBar::make()
            ->title($screen->navTitle())
            ->subtitle('All caught up')
            ->back()
            ->backgroundColor('#0891b2')
            ->textColor('#FFFFFF')
            ->elevation(8)
            ->action(NavAction::make('search')->icon('search')->press('openSearch'));
    }

    public function tabBar(NativeComponent $screen): ?TabBar
    {
        return TabBar::make()
            ->dark()
            ->activeColor('#0891b2')
            ->labelVisibility('labeled')
            ->add(Tab::link('Chats',   '/syncup',          icon: 'chat_bubble')->badge('2'))
            ->add(Tab::link('Friends', '/syncup/friends',  icon: 'person.3.fill')->news())
            ->add(Tab::link('Profile', '/syncup/profile',  icon: 'person'));
    }
}
