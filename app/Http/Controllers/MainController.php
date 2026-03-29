<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\CraftParser;
use App\Services\InfoParser;
use App\Services\LabParser;
use App\Services\LocationParser;
use App\Services\PreyParser;

class MainController extends Controller
{
    public function index() {
        $html = $this->post('cgi/no_combat.php', []);
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
        } elseif (strpos($html, '/cgi/maze_move.php') !== false) {
            return $this->lab();
        } elseif (strpos($html, 'id="LocTable"') !== false) {
            return view('main_location', $loc->onLocation($html));
        } elseif (
            strpos($html, 'cssLocImage') !== false ||
            strpos($html, '<image height=150 width=150') !== false ||
            strpos($html, "window.open('loc_desc.php") !== false
        ) {
            return view('main_place', $loc->onPlace($html));
        } elseif (strpos($html, 'travel_start.php') !== false) {
            return $this->map();
        }
        return view('generic', ['data' => $html]);
    }

    public function map() {
        $post = request()->post();
        if (!empty($post)) {
            $html = $this->post('cgi/travel_start.php', [], $post);
        } else {
            $html = $this->get('cgi/map.php', []);
        }
        return view('main_map', (new LocationParser)->onMap($html));
    }

    public function mapStop() {
        $this->get('cgi/travel_stop.php', []);
        return $this->index();
    }

    public function lab() {
        $parser = new LabParser();
        $info = new InfoParser();
        $loc = $this->get('cgi/ch_who.php', []);
        $state = $this->get('cgi/maze_ref.php', []);
        $scrolls = $this->get('cgi/inv_load_items.php', ['tp' => 26, 'dv' => 'd126', 'expand' => true]);
        $potions = $this->get('cgi/inv_load_items.php', ['tp' => 25, 'dv' => 'd125', 'expand' => true]);
        $post = [
            '0.x' => rand(1, 10),
            '0.y' => rand(1, 10),
        ];
        $html = $this->post('cgi/change_info.php', [], $post);
        return view('labyrinth', [
            ...$parser->getLocation($loc),
            ...$parser->getState($state),
            'scrolls' => $info->getStuffItems($scrolls),
            'potions' => $info->getStuffItems($potions),
            'active_potions' => $info->getPotions($html),
            'captcha' => $this->captcha(time()),
        ]);
    }

    public function wear() {
        $this->get('cgi/inv_wear.php');
        return $this->index();
    }
}
