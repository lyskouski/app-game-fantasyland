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
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ListenStream implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    private const CACHE_KEY = 'listen_stream_generation';

    public function __construct(public string $generation = '', public string $sessionId = '')
    {
    }

    public static function sessionIdFrom(string $chMainHtml): string
    {
        return preg_match('/var\s+sesid\s*=\s*"([^"]*)"/', $chMainHtml, $m) ? $m[1] : '';
    }

    // Invalidates any running chain, then starts a new one bound to the given session id.
    public static function start(string $sessionId): void
    {
        if ($sessionId === '') {
            return;
        }
        $generation = bin2hex(random_bytes(8));
        Cache::forever(self::CACHE_KEY, $generation);
        static::dispatch($generation, $sessionId);
    }

    public static function stop(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function handle(): void
    {
        if (Cache::get(self::CACHE_KEY) !== $this->generation) {
            return;
        }

        $buffer = '';
        $proxy = new AppProxyProvider();

        $ch = curl_init(Defines::URL . "gmmm?{$this->sessionId}");
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
                    } elseif (preg_match('/(?<![A-Za-z0-9_])c\(\s*\'((?:[^\'\\\\]|\\\\.)*)\'\s*\)/s', $script, $call)) {
                        $message = str_replace('parent.ch_ref.location.href', 'window.location.href', $call[1]);
                        Notification::addMessage($message);
                    }

                    $buffer = substr($buffer, $end);
                }
                return strlen($chunk);
            },
        ]);

        curl_exec($ch);
        curl_close($ch);

        if (Cache::get(self::CACHE_KEY) === $this->generation) {
            static::dispatch($this->generation, $this->sessionId)->delay(now()->addSeconds(5));
        }
    }
}
