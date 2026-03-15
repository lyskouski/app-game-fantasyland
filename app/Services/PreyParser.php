<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

class PreyParser
{
    public function parse(string $html, string $captcha) {
        $content = '';
        if (preg_match('/<HR>(.*?)<\/TD><\/TR><\/TABLE>/is', $html, $matches)) {
            $content = $matches[1];
            $content = str_replace('action="work_start.php"', 'action="/cgi/work_start.php"', $content);
            $content = str_replace('../images', Defines::URL . 'images', $content);
            $content = preg_replace_callback(
                "/<IMG\s+SRC='png.php\?c=(\d+)'([^>]*)>/i",
                function ($m) use ($captcha) {
                    return "<img src='" . $captcha . "'" . $m[2] . ">";
                },
                $content
            );
        }
        $image = '';
        if (preg_match('/<image[^>]*src=(["\'])([^"\']+)\1/i', $html, $imgMatch)) {
           $image = str_replace('..', '', $imgMatch[2]);
        }
        $timer = 0;
        if (preg_match('/InsertTimer2\\s*\\(\\s*(\\d+)/', $html, $matches)) {
            $timer = (int)$matches[1];
        }
        $message = '';
        if (preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            $message = $matches[1];
        }
        return ['data' => $content, 'image' => $image, 'timer' => $timer, 'message' => $message];
    }
}
