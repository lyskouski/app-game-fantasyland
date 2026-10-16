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
        $activeTab = $screen instanceof HomeShell ? $screen->activeTab : HomeShell::TAB_HOME;

        return TabBar::make()
            ->dark()
            ->activeColor('#0891b2')
            ->labelVisibility('labeled')
            ->add(Tab::action('Профиль', icon: 'person')->press('showProfile')->active($activeTab === HomeShell::TAB_PROFILE))
            ->add(Tab::action('Главная', icon: 'home')->press('showHome')->active($activeTab === HomeShell::TAB_HOME))
            ->add(Tab::action('Чат', icon: 'chat_bubble')->press('showChat')->active($activeTab === HomeShell::TAB_CHAT))
            ->add(Tab::action('Форум', icon: 'forum')->press('showForum')->active($activeTab === HomeShell::TAB_FORUM));
    }
}
