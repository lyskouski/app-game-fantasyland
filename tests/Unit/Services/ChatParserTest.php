<?php

namespace Tests\Unit\Services;

use App\Services\ChatParser;
use PHPUnit\Framework\TestCase;

class ChatParserTest extends TestCase
{
    public function test_menu_contains_channels_and_their_clan_metadata(): void
    {
        $html = '<script>
            var channels = []; channels.push( 0 );
            channels.push( 54 );
            var ic = []; for( i in channels ) ic[channels[i]] = i;
            var clan_icons = [];
        </script>';
        $w = "var ids = new Array(12, 54); var cnames = new Array('Other', 'Clan'); var imgs = new Array('other.gif', 'clan.gif');";

        $this->assertSame([
            ['id' => 0, 'name' => 'Общий', 'img' => null],
            ['id' => 54, 'name' => 'Clan', 'img' => 'clan.gif'],
        ], (new ChatParser())->getMenu($html, $w));
    }

    public function test_menu_handles_missing_metadata_and_no_channels(): void
    {
        $parser = new ChatParser();

        $this->assertSame([
            ['id' => 54, 'name' => '', 'img' => null],
        ], $parser->getMenu('channels.push(54);', ''));
        $this->assertSame([], $parser->getMenu('var channels = [];', ''));
    }
}
