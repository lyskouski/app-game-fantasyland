<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

class InfoParser
{
    public function options(string $html): array
    {
        $result = [];
        // Extract user info: name, level, alignment
        $userInfo = '';
        if (preg_match(
            "/<font class='cp'[^>]*>([^<]+)<\/font>\s*<font[^>]*>\[<\/font><span class='cp'[^>]*>([^<]+)<\/span><font[^>]*>\]<\/font>:&nbsp;<font[^>]*><b>([A-Z])<\/b><\/font><font[^>]*><b>([A-Z])<\/b><\/font>/u",
            $html,
            $matches
        )) {
            // $matches[1]: name, $matches[2]: level/exp, $matches[3]: alignment1, $matches[4]: alignment2
            $userInfo = sprintf('%s [%s]: %s%s', $matches[1], $matches[2], $matches[3], $matches[4]);
        }

        preg_match_all('/<input[^>]*type=["\']image["\'][^>]*>/i', $html, $inputs);
        foreach ($inputs[0] as $input) {
            preg_match('/name=["\']?(\d+)["\']?/i', $input, $name);
            preg_match('/src=["\']?([^"\'> ]+)["\']?/i', $input, $src);
            preg_match('/title=["\']?([^"\'>]+)["\']?/i', $input, $title);
            if (isset($name[1]) && isset($src[1]) && isset($title[1])) {
                $result[] = [
                    'key' => $name[1],
                    'image' => Defines::URL . $src[1],
                    'value' => $title[1],
                ];
            }
        }
        return [
            'data' => $result,
            'user' => $userInfo,
        ];
    }

    public function getDiary(string $html): array
    {
        return [];
    }
}
