<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class CraftParser
{
    public function parse(string $html) {
        $message = '';
        if (preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches)) {
            $message = $matches[1];
        }
        $craft = [];
        if (preg_match_all(
            '/<BUTTON[^>]+onClick=\'regimeTo\(([^)]+)\)[^>]*>([^<]+)<\/BUTTON>/ui',
            $html,
            $matches,
            PREG_SET_ORDER
        )) {
            foreach ($matches as $m) {
                $expr = $m[1];
                $title = trim($m[2]);
                $id = null;
                if (preg_match('/^\d+$/', $expr)) {
                    $id = (int)$expr;
                } else {
                    if (preg_match('/^(\d+)\s*\+\s*(\d+)$/', $expr, $em)) {
                        $id = (int)$em[1] + (int)$em[2];
                    } else {
                        $id = @eval('return ' . $expr . ';');
                    }
                }
                if ($id !== null) {
                    $craft[] = ['id' => $id, 'title' => $title];
                }
            }
        }
        return ['craft' => $craft, 'recipes' => $this->getRecipes($html), 'message' => $message];
    }

    protected function getRecipes($html) {
        $recipes = [];
        if (preg_match_all('/<TR>(.*?)<\/TR>/s', $html, $rows, PREG_SET_ORDER)) {
            foreach ($rows as $row) {
                $tr = $row[1];
                if (strpos($tr, "startWork(") === false && strpos($tr, "startWorkCount(") === false) {
                    continue;
                }
                $src = '';
                $title = '';
                // Always extract src and title from <img ...>
                if (preg_match('/<img[^>]+title=["\']?([^"\'>]+)["\']?[^>]*src=["\']([^"\'>]+)["\']/i', $tr, $imgMatch)) {
                    $title = html_entity_decode($imgMatch[1]);
                    $src = str_replace('../', '/', $imgMatch[2]);
                }
                // Get recipe id
                $id = null;
                if (preg_match('/startWork\((\d+)\)/', $tr, $idMatch)) {
                    $id = (int)$idMatch[1];
                } elseif (preg_match('/startWorkCount\((\d+),/', $tr, $idMatch)) {
                    $id = (int)$idMatch[1];
                }
                // Get count (prefer bracket in title, else button/input value)
                $count = 1;
                if (preg_match('/\[(\d+)\]/', $tr, $bracketMatch)) {
                    $count = (int)$bracketMatch[1];
                }
                // Get time
                $time = '';
                if (preg_match('/<span[^>]+id=["\']t\d+["\'][^>]*>([^<]+)<\/span>/i', $tr, $timeMatch)) {
                    $time = trim($timeMatch[1]);
                }
                // Get receipt (ingredients)
                $receipt = [];
                if (preg_match_all('/<b>([^<]+)<\/b>/i', $tr, $bMatch)) {
                    foreach ($bMatch[1] as $value) {
                        $receipt[] = str_replace('&nbsp;', ' ', $value);
                    }
                }
                $receiptStr = implode(', ', $receipt);
                if ($id !== null && $title !== '') {
                    $recipes[] = [
                        'title' => $title,
                        'count' => $count,
                        'src' => $src,
                        'id' => $id,
                        'time' => $time,
                        'receipt' => $receiptStr
                    ];
                }
            }
        }
        return $recipes;
    }
}
