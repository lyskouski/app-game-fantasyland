<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Jobs;

use App\Models\Notification;
use App\Providers\AppProxyProvider;
use App\Settings\Defines;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ListenStream implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    public function handle(): void
    {
        $buffer = '';
        $proxy = new AppProxyProvider();

        $sessionId = '';
        $chMain = $proxy->boot(Defines::URL . '/ch/chmain.php');
        if (preg_match('/var\s+sesid\s*=\s*"([^"]*)"/', $chMain, $sesidMatch)) {
            $sessionId = $sesidMatch[1];
        }

        $ch = curl_init(Defines::URL . "gmmm?{$sessionId}");
        curl_setopt_array($ch, [
            CURLOPT_TIMEOUT => 25,
            CURLOPT_USERAGENT => $proxy->userAgent(),
            CURLOPT_COOKIEFILE => $proxy->cookieFile(),
            CURLOPT_COOKIEJAR => $proxy->cookieFile(),
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$buffer, $proxy) {
                $buffer .= $chunk;
                while (preg_match(
                    '/<script\b[^>]*>(.*?)<\/script>/is',
                    $buffer,
                    $m,
                    PREG_OFFSET_CAPTURE
                )) {
                    $script = $proxy->decode($m[1][0]);
                    $end = $m[0][1] + strlen($m[0][0]);

                    if (preg_match('/(?<![A-Za-z0-9_])[ab]\(\s*"((?:[^"\\\\]|\\\\.)*)"\s*,\s*"((?:[^"\\\\]|\\\\.)*)"/s', $script, $call)) {
                        $message = $call[1];
                        $user = $call[2];
                        Notification::addMessage("<b>{$user}:</b> {$message}");
                    }

                    $buffer = substr($buffer, $end);
                }
                return strlen($chunk);
            },
        ]);

        curl_exec($ch);
        curl_close($ch);

        static::dispatch()->delay(now()->addSeconds(5));
    }
}
