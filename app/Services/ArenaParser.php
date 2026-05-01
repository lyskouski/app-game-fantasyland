<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class ArenaParser
{
    public function train(string $html) {
        $train = [];
        preg_match_all("/addToContent\('(.+?'\s*,\s*'.+?'\s*,\s*'.+?'.+?)\);/", $html, $matches);
        foreach ($matches[1] as $match) {
            $parts = str_getcsv(trim($match), ',', "'");
            $parts = array_map('trim', $parts);
            if (count($parts) >= 16) {
                $train[] = [
                    'name' => $parts[0],
                    'img' => $parts[1],
                    'uid' => $parts[2],
                    'name2' => $parts[3],
                    'img2' => $parts[4],
                    'w1' => (int)$parts[5],
                    'percent' => (float)$parts[6],
                    'w2' => (int)$parts[7],
                    'w3' => (int)$parts[8],
                    'chck' => (bool)$parts[9],
                    'level' => (int)$parts[10],
                    'pid' => (int)$parts[11],
                    'type' => $parts[12],
                    'level2' => (int)$parts[13],
                    'type2' => $parts[14],
                    'uid2' => $parts[15],
                ];
            }
        }
        return ['train' => $train];
    }

    public function timer(string $html) {
        preg_match("/getPreTimerString\s*\(\s*(\d+)\s*,/", $html, $matches);
        return isset($matches[1]) ? (int)$matches[1] : 0;
    }
}
