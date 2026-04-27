<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Services;

class LocationParser
{
    public function onLocation(string $html) {
        $map = [];
        if (preg_match_all('/selectArray\["(Sel\d+)"\]\s*=\s*new Array\([^)]*?,\s*[^,]*?,\s*[^,]*?,\s*[^,]*?,\s*[^,]*?,\s*([\'\"])(.*?)\2,\s*\[[^\]]*\],\s*(\d+)\);/u', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $loc = $m[3];
                $id = (int)$m[4];
                $map[] = ['loc' => $loc, 'id' => $id];
            }
        } elseif (preg_match_all('/<area[^>]+href=["\']javascript:\s*goTo\((\d+)\);?["\'][^>]+onmousemove=["\']ToolTipShow\(([^)]*)\)/i', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $id = (int)$m[1];
                $args = $m[2];
                // Extract the first argument (location name) from ToolTipShow('loc', ...)
                if (preg_match("/^[\'\"]([^'\"]+)[\'\"]/u", trim($args), $locMatch)) {
                    $loc = $locMatch[1];
                } else {
                    $loc = '';
                }
                $map[] = ['loc' => $loc, 'id' => $id];
            }
        }
        $image = '';
        if (preg_match('/<td[^>]*id="LocTd"[^>]*>.*?<img\s+src=[\'\"]([^\'\"]+)[\'\"]/s', $html, $imgMatch)) {
            $image = $imgMatch[1];
        }
        return ['map' => $map, 'image' => $image, 'hasRoad' => strpos($html, 'map.php') !== false];
    }

    public function onPlace(string $html) {
        $title = '';
        if (preg_match('/show_title\(["\']([^"\']+)["\']\)/', $html, $titleMatch)) {
            $title = html_entity_decode($titleMatch[1]);
        } elseif (preg_match('/<script>document.write\(show_title\(["\']([^"\']+)["\']\)\);<\/script>/i', $html, $titleMatch)) {
            $title = html_entity_decode($titleMatch[1]);
        }
        $image = '';
        if (preg_match('/<image[^>]*class=(["\'])?cssLocImage\1?[^>]*src=(["\'])([^"\']+)\2/i', $html, $imgMatch)) {
            $image = str_replace('..', '', $imgMatch[3]);
        } elseif (preg_match('/<image[^>]*src=(["\'])([^"\']+)\1/i', $html, $imgMatch)) {
            $image = str_replace('..', '', $imgMatch[2]);
        }
        $map = [];
        if (preg_match_all("/<A[^>]*HREF=\s*['\"]\\s*javascript:goTo\((\d+)\)['\"][^>]*>.*?<\/A>.*?<TD>([^<]+)<\/TD>/is", $html, $goToMatches, PREG_SET_ORDER)) {
            foreach ($goToMatches as $m) {
                $id = (int)$m[1];
                $loc = trim($m[2]);
                $map[] = ['loc' => $loc, 'id' => $id];
            }
        }
        if (preg_match_all('/<button[^>]+onClick=["\']goTo\((\d+)\)["\'][^>]*>([^<]*)<\/button>/i', $html, $btnMatches, PREG_SET_ORDER)) {
            foreach ($btnMatches as $m) {
                $loc = trim($m[2]);
                $id = (int)$m[1];
                $map[] = ['loc' => $loc ?: 'Обновить', 'id' => $id];
            }
        }
        $place = [];
        if (preg_match_all("/<A[^>]*HREF=['\"]javascript:regimeTo\((\d+)\)['\"][^>]*>.*?<\/A>.*?<TD>([^<]+)<\/TD>/is", $html, $regimeToMatches, PREG_SET_ORDER)) {
            foreach ($regimeToMatches as $m) {
                $id = (int)$m[1];
                $loc = trim($m[2]);
                $place[] = ['loc' => $loc, 'id' => $id];
            }
        }
        $description = '';
        if (preg_match('/<TD[^>]*>(.*?)<TABLE>/is', $html, $descMatch)) {
            $description = strip_tags($descMatch[1]);
        }
        return [
            'image' => $image,
            'title' => $title,
            'description' => $description,
            'map' => $map,
            'place' => $place,
            'hasRoad' => strpos($html, 'map.php') !== false
        ];
    }

    public function onMap(string $html) {
        $map = [];
        if (preg_match_all('/<area[^>]+href=["\']javascript:Travel\((\d+)\)["\'][^>]+onmousemove=["\']ToolTipShow\(([^)]*)\)/i', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $id = (int)$m[1];
                $args = $m[2];
                // Extract the first argument (location name) from ToolTipShow('loc', ...)
                if (preg_match("/^[\'\"]([^'\"]+)[\'\"]/u", trim($args), $locMatch)) {
                    $loc = $locMatch[1];
                } else {
                    $loc = '';
                }
                $map[] = ['loc' => $loc, 'id' => $id];
            }
        }
        $return = null;
        if (preg_match('/no_travel.php\?return=(\d+)/', $html, $matches)) {
            $return = (int)$matches[1];
        }
        $timer = 100;
        if (preg_match('/st = InsertTimer\(\s*(-?\d+)/', $html, $matches)) {
            $timer = (int)$matches[1];
            if ($timer < 0) {
                $timer = 0;
            }
        }
        $title = null;
        if (preg_match('/<td>\s*Направление:\s*<b><font[^>]*>([^<]+)<\/font><\/b>\./ui', $html, $matches)) {
            $title = $matches[1];
        }
        return ['map' => $map, 'return' => $return, 'timer' => $timer, 'title' => $title];
    }
}
