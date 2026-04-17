<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class StoreParser
{
    public function parseBuyStore(string $html) {
        $items = [];

        // Find all complete rows: <tr>...form with id=i{goodId}...</tr>
        // Using [\s\S]*? to match any character including newlines, non-greedy
        if (preg_match_all('#<tr[^>]*>[\s\S]*?<form[^>]*id=[\'"]*i(\d+)[\'"]*[^>]*>[\s\S]*?</form>[\s\S]*?</tr>#i', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $goodId = $match[1];
                $rowContent = $match[0];

                $item = [];
                $item['good_id'] = $goodId;

                // Extract image src
                if (preg_match('#src="([^"]+?\.(gif|jpg|png))"#i', $rowContent, $m)) {
                    $img = preg_replace('#^\.\./+#', '', $m[1]);
                    $item['img'] = $img;
                }

                // Extract count and title: (N)&nbsp;<b>Title</b>
                if (preg_match('#\((\d+)\)\s*&nbsp;\s*<b>([^<]+)</b>#i', $rowContent, $m)) {
                    $item['count'] = $m[1];
                    $item['title'] = str_replace('&nbsp;', ' ', $m[2]);
                }

                // Extract cost from div id=d{goodId}
                if (preg_match("#<div[^>]*id=d{$goodId}[^>]*>(\d+)</div>#i", $rowContent, $m)) {
                    $item['cost'] = $m[1];
                }

                // Extract form hidden inputs - flexible pattern for name and value attributes
                if (preg_match("#name=['\"]?good_id['\"]?[^>]*value=['\"]?([^'\">\s]+)#i", $rowContent, $m)) {
                    $item['good_id'] = $m[1];
                }
                if (preg_match("#name=['\"]?shp_id['\"]?[^>]*value=['\"]?([^'\">\s]+)#i", $rowContent, $m)) {
                    $item['shp_id'] = $m[1];
                }
                if (preg_match("#name=['\"]?good_type['\"]?[^>]*value=['\"]?([^'\">\s]+)#i", $rowContent, $m)) {
                    $item['good_type'] = $m[1];
                }
                // price_quest can be empty
                if (preg_match("#name=['\"]?price_quest['\"]?[^>]*value=['\"]?([^'\">\s]*)#i", $rowContent, $m)) {
                    $item['price_quest'] = $m[1];
                }
                // capCode can be empty
                if (preg_match("#name=['\"]?capCode['\"]?[^>]*value=['\"]?([^'\">\s]*)#i", $rowContent, $m)) {
                    $item['capCode'] = $m[1];
                }
                if (preg_match("#name=['\"]?number['\"]?[^>]*value=['\"]?([^'\">\s]+)#i", $rowContent, $m)) {
                    $item['number'] = $m[1];
                }

                // Only add items with required data
                if (!empty($item['good_id']) && !empty($item['img'])) {
                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    public function parseSellStore(string $html) {
        $items = [];
        // ...
        return $items;
    }
}