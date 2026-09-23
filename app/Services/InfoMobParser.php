<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

use App\Settings\Defines;

class InfoMobParser
{
    public function getMobInfo(string $html): array
    {
        $result = [
            'name' => '',
            'level' => 0,
            'image' => '',
            'hp' => '',
            'effects' => [],
            'activity' => '',
            'aggressive' => '',
            'habitat' => [],
            'description' => '',
            'drop' => [],
            'profit' => '',
        ];

        if (preg_match('/<B>&nbsp;\s*(.+?)\s*\[L(\d+)\]\s*&nbsp;<\/B>/u', $html, $matches)) {
            $result['name'] = $matches[1];
            $result['level'] = (int)$matches[2];
        }

        if (preg_match("/src='([^']*\/mmobs\/\d+\.jpg)'/u", $html, $matches)) {
            $result['image'] = Defines::URL . ltrim($matches[1], '/');
        }

        if (preg_match('/<TD id=hp1>.*?(\d+\/\d+)/su', $html, $matches)) {
            $result['hp'] = $matches[1];
        }

        if (preg_match_all(
            "/<image[^>]*src='([^']+)'[^>]*title='([^']+)'>&nbsp;<image[^>]*src='([^']+)'[^>]*title='([^']+)'><\/td><td nowrap>([^<]+)<\/td>/u",
            $html,
            $matches,
            PREG_SET_ORDER
        )) {
            foreach ($matches as $match) {
                [$attackValue, $defenceValue] = array_pad(explode('/', $match[5], 2), 2, '');
                $result['effects'][] = [
                    'image' => Defines::URL . ltrim($match[1], '/'),
                    'title' => $match[2],
                    'value' => $attackValue,
                ];
                $result['effects'][] = [
                    'image' => Defines::URL . ltrim($match[3], '/'),
                    'title' => $match[4],
                    'value' => $defenceValue,
                ];
            }
        }

        if (preg_match("/<td colspan=3>(.*?)<\/td><\/tr><\/table><\/TD><\/TR><\/TABLE><TABLE width=100%><TR><TD class='cell' width=100% align=center><b>Активность/su", $html, $matches)) {
            preg_match_all("/<image[^>]*src='([^']+)'[^>]*title='([^']+)'><\/td><td[^>]*>([^<]+)<\/td>/u", $matches[1], $effectMatches, PREG_SET_ORDER);
            foreach ($effectMatches as $effect) {
                $result['effects'][] = [
                    'image' => Defines::URL . ltrim($effect[1], '/'),
                    'title' => $effect[2],
                    'value' => trim($effect[3]),
                ];
            }
        }

        if (preg_match('/<b>Активность:\s*([^<]+)<\/b>.*?<b>([^<]+)<\/b>/su', $html, $matches)) {
            $result['activity'] = trim($matches[1]);
            $result['aggressive'] = trim($matches[2]);
        }

        if (preg_match("/GetBlockTitle\('Место обитания'\).*?<table[^>]*>(.*?)<\/table>/su", $html, $matches)) {
            preg_match_all('/<td class="cell">([^<]+)<\/td>\s*<td class="cell">(\d+)<\/td>/u', $matches[1], $rows, PREG_SET_ORDER);
            foreach ($rows as $row) {
                $result['habitat'][] = ['location' => trim($row[1]), 'level' => (int)$row[2]];
            }
        }

        if (preg_match("/GetBlockTitle\('Описание'\).*?<td class=\"cell\" align=\"center\">([^<]+)<\/td>/su", $html, $matches)) {
            $result['description'] = trim($matches[1]);
        }

        $dropStart = strpos($html, "GetBlockTitle('Дроп')");
        if ($dropStart !== false) {
            $profitPos = strpos($html, 'Средняя прибыль', $dropStart);
            $dropSection = $profitPos !== false ? substr($html, $dropStart, $profitPos - $dropStart) : substr($html, $dropStart);

            $rows = preg_split('/(?=<tr><td align=center class=cell>)/u', $dropSection);
            foreach ($rows as $row) {
                if (!str_contains($row, 'align=center class=cell')) {
                    continue;
                }
                if (!preg_match("/title='([^']+)'\s+src=\"([^\"]+)\"/u", $row, $imgMatch)) {
                    continue;
                }
                preg_match_all('/<td class=cell>([^<]*)<\/td>/u', $row, $cellMatch);
                $cells = $cellMatch[1] ?? [];
                if (count($cells) < 2) {
                    continue;
                }
                $result['drop'][] = [
                    'image' => Defines::URL . ltrim(str_replace('../', '', $imgMatch[2]), '/'),
                    'name' => str_replace('&nbsp;', ' ', $imgMatch[1]),
                    'count' => trim($cells[count($cells) - 2]),
                    'chance' => trim($cells[count($cells) - 1]),
                ];
            }

            if (preg_match('/Средняя прибыль:.*?(\d+(?:\.\d+)?)<\/b>/su', $html, $matches)) {
                $result['profit'] = $matches[1];
            }
        }

        return $result;
    }
}
