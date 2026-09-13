<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class FightParser
{
    public function getScrolls(string $html) {
        $scrolls = [];
        preg_match_all(
            "/<img[^>]*?src='(?<image>\/images\/items\/[^']+)'[^>]*?title='(?<descr>[^']+?)'\s*onClick='setScroll\((?<id>\d+)\)'/s",
            $html,
            $matches,
            PREG_SET_ORDER
        );
        foreach ($matches as $match) {
            $title = str_replace('&nbsp;', ' ', strtok($match['descr'], "\n"));
            $scrolls[] = [
                'image' => $match['image'],
                'title' => $title,
                'id' => (int)$match['id'],
            ];
        }
        return $scrolls;
    }

    public function getArmy(string $html) {
        $army = [];
        preg_match_all(
            "/InvArmyShow\((?<type>\d+),'(?<image>[^']*)',\s*\"(?<descr>.*?)\",\s*(?<count>\d+),(?<id>\d+),/s",
            $html,
            $matches,
            PREG_SET_ORDER
        );
        foreach ($matches as $match) {
            $army[] = [
                'type' => (int)$match['type'],
                'id' => (int)$match['id'],
                'image' => $match['image'],
                'descr' => str_replace(
                    ['src=', '%ba%', '%oa%', '%sa%'],
                    ['src=https://www.fantasyland.ru'],
                    $match['descr']
                ),
                'count' => (int)$match['count'],
            ];
        }
        return $army;
    }
}
