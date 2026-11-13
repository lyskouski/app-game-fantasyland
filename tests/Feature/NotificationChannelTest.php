<?php

namespace Tests\Feature;

use App\Jobs\ListenStream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Native\Mobile\Facades\Dialog;
use Tests\TestCase;

class NotificationChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_stream_messages_store_their_channels(): void
    {
        Dialog::shouldReceive('toast')->twice();

        $html = '<SCRIPT async="true">b("priv(Росомаха)тест common","Росомаха",15,15,2,1,0);</SCRIPT>'
            . '<SCRIPT async="true">p();</SCRIPT>'
            . '<SCRIPT async="true">a("test","Росомаха",15,15,2,1,54);</SCRIPT>';
        preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $html, $scripts);
        foreach ($scripts[1] as $script) {
            ListenStream::processScript($script, true);
        }

        $this->assertDatabaseCount('notifications', 2);
        $this->assertDatabaseHas('notifications', ['message' => '<b>Росомаха:</b> priv(Росомаха)тест common', 'channel' => 0]);
        $this->assertDatabaseHas('notifications', ['message' => '<b>Росомаха:</b> test', 'channel' => 54]);
    }

    public function test_defaults_and_social_restrictions_are_preserved(): void
    {
        Dialog::shouldReceive('toast')->twice();

        ListenStream::processScript('a("blocked","player",15,15,2,1,54);', false);
        ListenStream::processScript('a("legacy, message","player");', true);
        ListenStream::processScript("c('system message');", false);
        DB::table('notifications')->insert(['message' => 'database default']);

        $this->assertDatabaseCount('notifications', 3);
        $this->assertDatabaseHas('notifications', ['message' => '<b>player:</b> legacy, message', 'channel' => 0]);
        $this->assertDatabaseHas('notifications', ['message' => 'system message', 'channel' => 0]);
        $this->assertDatabaseHas('notifications', ['message' => 'database default', 'channel' => 0]);
    }
}
