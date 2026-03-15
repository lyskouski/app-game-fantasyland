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
        return ['data' => $result];
    }
}
