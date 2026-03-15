<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\CraftParser;
use App\Services\LocationParser;
use App\Services\PreyParser;

class MainController extends Controller
{
    public function index() {
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/no_combat.php', $post);
        $loc = new LocationParser();
        $prey = new PreyParser();
        if (strpos($html, 'work_stop.php') !== false) {
            return view('prey_start', [
                ...$loc->onPlace($html),
                ...$prey->parse($html, $this->captcha(time()))
            ]);
        } elseif (strpos($html, 'craft_favorite_ref.php') !== false) {
            return view('craft_stop', [
                ...$loc->onPlace($html),
                ...(new CraftParser)->parse($html),
                'captcha' => $this->captcha(time())
            ]);
        } elseif (strpos($html, 'work_start.php') !== false) {
            return view('prey_stop', [
                ...$loc->onPlace($html),
                ...$prey->parse($html, $this->captcha(time()))
            ]);
        } elseif (strpos($html, 'id="LocTable"') !== false) {
            return view('main_location', $loc->onLocation($html));
        } elseif (strpos($html, 'cssLocImage') !== false || strpos($html, '<image height=150 width=150') !== false) {
            return view('main_place', $loc->onPlace($html));
        } elseif (strpos($html, 'travel_start.php') !== false) {
            return $this->map();
        }
        return view('generic', ['data' => $html]);
    }

    public function map() {
        $post = request()->post();
        if (!empty($post)) {
            $html = $this->curl->boot($this->url . 'cgi/travel_start.php', $post);
        } else {
            $html = $this->curl->boot($this->url . 'cgi/map.php');
        }
        return view('main_map', (new LocationParser)->onMap($html));
    }

    public function mapStop() {
        $this->curl->boot($this->url . 'cgi/travel_stop.php');
        return $this->index();
    }
}
