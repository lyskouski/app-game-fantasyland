<?php

namespace Tests\Unit\NativeComponents;

use App\NativeComponents\HomeShell;
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
}
