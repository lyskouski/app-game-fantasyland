<?php

namespace Tests\Unit\NativeComponents;

use App\NativeComponents\HomeShell;
use App\NativeComponents\NavigationLayout;
use PHPUnit\Framework\TestCase;

class HomeShellTest extends TestCase
{
    public function test_tab_actions_switch_the_source_for_the_single_webview(): void
    {
        $screen = new HomeShell;

        $this->assertSame('/cgi/no_combat.php', $screen->source());

        $tabs = [
            ['showProfile', 'profile', '/cgi/show_info.php'],
            ['showHome', 'home', '/cgi/no_combat.php'],
            ['showChat', 'chat', '/ch/chout.php'],
            ['showForum', 'forum', '/cgi/forum_rooms.php'],
        ];

        foreach ($tabs as [$action, $activeTab, $source]) {
            $screen->{$action}();

            $this->assertSame($activeTab, $screen->activeTab);
            $this->assertSame($source, $screen->source());
        }
    }

    public function test_active_tab_stays_highlighted_after_framework_highlight(): void
    {
        $screen = new HomeShell;
        $screen->showChat();

        $bar = (new NavigationLayout)->tabBar($screen)->highlight('/shell/home');

        $active = array_map(fn ($item) => $item->isActive(), $bar->tabElements());

        $this->assertSame([false, false, true, false], $active);
    }

    public function test_pressing_the_active_tab_again_changes_the_webview_src(): void
    {
        $screen = new HomeShell;

        $screen->showProfile();
        $first = $screen->webviewSrc();

        $screen->showProfile();
        $second = $screen->webviewSrc();

        $this->assertStringStartsWith('/cgi/show_info.php', $first);
        $this->assertStringStartsWith('/cgi/show_info.php', $second);
        $this->assertNotSame($first, $second);
    }
}
