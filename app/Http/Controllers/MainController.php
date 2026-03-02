<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppProxyProvider;

class MainController extends Controller
{
    protected AppProxyProvider $curl;

    public function __construct()
    {
        $this->curl = new AppProxyProvider();
    }

    public function index() {
        $post = request()->post();
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/no_combat.php', $post);
        if (strpos($html, 'id="LocTable"') !== false) {
            return view('main_location', $this->onLocation($html));
        } elseif (strpos($html, 'cssLocImage') !== false) {
            return view('main_place', $this->onPlace($html));
        }
        return view('generic', ['data' => $html]);
    }

    private function onLocation(string $html) {
        $map = [];
        if (preg_match_all('/selectArray\["(Sel\d+)"\]\s*=\s*new Array\([^)]*?,\s*[^,]*?,\s*[^,]*?,\s*[^,]*?,\s*[^,]*?,\s*([\'\"])(.*?)\2,\s*\[[^\]]*\],\s*(\d+)\);/u', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $loc = $m[3];
                $id = (int)$m[4];
                $map[] = ['loc' => $loc, 'id' => $id];
            }
        }
        $image = '';
        if (preg_match('/<td[^>]*id="LocTd"[^>]*>.*?<img\s+src=[\'\"]([^\'\"]+)[\'\"]/s', $html, $imgMatch)) {
            $image = $imgMatch[1];
        }
        return ['map' => $map, 'image' => $image];
    }

    private function onPlace(string $html) {
        $title = '';
        if (preg_match('/show_title\(["\']([^"\']+)["\']\)/', $html, $titleMatch)) {
            $title = html_entity_decode($titleMatch[1]);
        } elseif (preg_match('/<script>document.write\(show_title\(["\']([^"\']+)["\']\)\);<\/script>/i', $html, $titleMatch)) {
            $title = html_entity_decode($titleMatch[1]);
        }
        $image = '';
        if (preg_match('/<image[^>]*class=(["\'])?cssLocImage\1?[^>]*src=(["\'])([^"\']+)\2/i', $html, $imgMatch)) {
            $image = str_replace('..', '', $imgMatch[3]);
        }
        $map = [];
        if (preg_match_all("/<A[^>]*HREF=['\"]javascript:goTo\((\d+)\)['\"][^>]*>.*?<\/A>.*?<TD>([^<]+)<\/TD>/is", $html, $goToMatches, PREG_SET_ORDER)) {
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
        return ['image' => $image, 'title' => $title, 'map' => $map, 'place' => $place];
    }

}
