<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\InfoParser;
use App\Services\LabParser;

class LabController extends Controller
{
    public function index() {
        $parser = new LabParser();
        $info = new InfoParser();
        $loc = $this->get('cgi/ch_who.php', []);
        $locData = $parser->getLocation($loc);
        $state = $this->get('cgi/maze_ref.php', []);
        $stateData = $parser->getState($state);
        $scrolls = $this->get('cgi/inv_load_items.php', ['tp' => 26, 'dv' => 'd126', 'expand' => true]);
        $potions = $this->get('cgi/inv_load_items.php', ['tp' => 25, 'dv' => 'd125', 'expand' => true]);
        $post = [
            '0.x' => rand(1, 10),
            '0.y' => rand(1, 10),
        ];
        $html = $this->post('cgi/change_info.php', [], $post);
        return view('labyrinth', [
            ...$locData,
            ...$stateData,
            'scrolls' => $info->getStuffItems($scrolls),
            'potions' => $info->getStuffItems($potions),
            'active_potions' => $info->getPotions($html),
            'captcha' => $this->captcha(time()),
            'map_data' => \App\Models\Map::getByLocation($locData['loc'], $locData['place'], $stateData['lvl']),
        ]);
    }

    public function save() {
        $data = request()->post();
        \App\Models\Map::updateOrCreate(
            [
                'location_id' => $data['location_id'],
                'place_id' => $data['place_id'] ?? 0,
                'z' => $data['z'],
                'x' => $data['x'],
                'y' => $data['y'],
            ],
            [
                'type' => $data['type'] ?? 0,
                'loc' => json_encode($data['loc'] ?? []),
                'info' => json_encode($data['info'] ?? []),
            ]
        );

        return view('empty', ['data' => 'OK']);
    }

    public function move() {
        $content = $this->get('cgi/maze_move.php');
        $content .= $this->get('cgi/maze_ref.php', []);
        if (strpos($content, 'ShowCod()') !== false) {
            $content .= 'captcha[' . $this->captcha(time()) . ']';
        }
        return view('empty', ['data' => $content]);
    }

    public function pickUp() {
        $content = $this->get('cgi/maze_pickup.php');
        $content .= $this->get('cgi/maze_ref.php', []);
        return view('empty', ['data' => $content]);
    }

    public function questAction() {
        $content = $this->get('cgi/maze_qaction.php');
        return view('empty', ['data' => $content]);
    }

    public function questMain() {
        $content = $this->get('cgi/mc_main.php');
        $hid = $this->get('/cgi/mc_hid.php');
        if (strpos($content, "location.href='mc_hid.php'") !== false) {
           $hid .= $this->get('/cgi/mc_hid.php');
        }
        return view('labyrinth_quest', (new LabParser)->getQuest($content . $hid));
    }

    public function questReply() {
        $html = $this->post('/cgi/mc_hid.php');
        if (strpos($html, 'location.href="no_combat.php"') !== false) {
            return redirect('/cgi/no_combat.php');
        }
        return view('labyrinth_quest', (new LabParser)->getQuest($html));
    }

    public function technicalInfo() {
        $html = $this->get('/cgi/technical_lab_info.php');
        return view('empty', ['data' => $html]);
    }
}
