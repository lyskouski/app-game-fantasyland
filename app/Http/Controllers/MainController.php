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
        }
        return view('generic');
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

}
