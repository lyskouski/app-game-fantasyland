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
        $calendar = [
            ['images/clans/hunters_small.gif', 'Неделя Охотника (+1 к охоте)', ''],
            ['images/clans/druids_small.gif', 'Неделя Собирателя (+1 к добыче)', ''],
            ['images/clans/fighters_small.gif', 'Неделя Бойца (+1 монета)', ''],
            ['images/clans/thinkers_small.gif', 'Неделя Мудреца (+1 монета)', ''],
            ['images/clans/merch_small.gif', 'Неделя Купца (+1 к торговле)', ''],
            ['images/clans/miners_capital_small.gif', 'Неделя Шахтёра (+1 к добыче)', '']
        ];
        foreach ($calendar as &$item) {
            if (strpos($html, $item[0]) !== false) {
                $item[2] = 'main--light';
            }
            $item[0] = Defines::URL . $item[0];
        }

        return ['calendar' => $calendar];
    }
}
