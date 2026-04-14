<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Providers\AppProxyProvider;
use App\Services\InfoParser;
use App\Services\LabParser;
use App\Settings\Defines;

class LabController extends Controller
{
    protected AppProxyProvider $cit;

    public function __construct()
    {
        parent::__construct();
        $this->cit = new AppProxyProvider('citadel');
    }

    public function index() {
        $parser = new LabParser();
        $info = new InfoParser();
        $loc = $this->get('cgi/ch_who.php', []);
        $locData = $parser->getLocation($loc);
        Notification::addIfExists($loc);
        $state = $this->get('cgi/maze_ref.php', []);
        $stateData = $parser->getState($state);
        Notification::addIfExists($state);
        $scrolls = $this->get('cgi/inv_load_items.php', ['tp' => 26, 'dv' => 'd126', 'expand' => true]);
        $potions = $this->get('cgi/inv_load_items.php', ['tp' => 25, 'dv' => 'd125', 'expand' => true]);
        $post = [
            '0.x' => rand(1, 10),
            '0.y' => rand(1, 10),
        ];
        $html = $this->post('cgi/change_info.php', [], $post);
        Notification::addIfExists($html);
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

    public function config() {
        return view('labyrinth_config', ['type' => null]);
    }

    protected function init() {
        $loc = $this->get('cgi/ch_who.php', []);
        $locData = (new LabParser)->getLocation($loc);
        $data = [
            'action' => 'init',
            'version' => [Defines::PLUGIN_VERSION, Defines::PLUGIN_VERSION],
            'loc' => sprintf('%03d_%03d', $locData['loc'], $locData['place']),
            'user' => $locData['login'],
        ];
        $result = $this->cit->boot(Defines::CITADEL . 'plugin', [], $data, false);
        if (preg_match("/setToken\('[^']*',\s*'([^']+)'\)/", $result, $matches)) {
            session()->put('token', $matches[1]);
        }
        return $result;
    }

    public function sync() {
        $conn = $this->cit->boot(Defines::CITADEL . 'plugin/status/user', [], null, false);
        if (strpos($conn, 'Прохожий') !== false) {
            $this->init();
        }
        $conn = $this->cit->boot(Defines::CITADEL . 'plugin/status/user', [], null, false);
        $type = null;
        if (preg_match('/<p>([^<]+)<\/p>/', $conn, $matches)) {
            $type = $matches[1];
        }
        return view('labyrinth_config', ['type' => $type]);
    }

    public function initToCitadel() {
        $response = $this->init();
        return view('empty', ['data' => $response]);
    }

    public function saveToCitadel() {
        $data = request()->post();
        $data['token'] = session()->get('token');
        $response = $this->cit->boot(Defines::CITADEL . 'plugin', [], $data, false);
        return view('empty', ['data' => $response]);
    }

    public function clear() {
        $lastHour = request()->query('last_hour', false);
        $loc = $this->get('cgi/ch_who.php', []);
        $locData = (new LabParser)->getLocation($loc);
        \App\Models\Map::clearLocation($locData['loc'], $locData['place'], $lastHour);
        return redirect('/labyrinth');
    }

    public function move() {
        $content = $this->get('cgi/maze_move.php');
        $content .= $this->get('cgi/maze_ref.php', []);
        Notification::addIfExists($content);
        if (strpos($content, 'ShowCod()') !== false) {
            $content .= 'captcha[' . $this->captcha(time()) . ']';
        }
        return view('empty', ['data' => $content]);
    }

    public function pickUp() {
        $content = $this->get('cgi/maze_pickup.php');
        $content .= $this->get('cgi/maze_ref.php', []);
        Notification::addIfExists($content);
        return view('empty', ['data' => $content]);
    }

    public function questAction() {
        $content = $this->get('cgi/maze_qaction.php');
        Notification::addIfExists($content);
        return view('empty', ['data' => $content]);
    }

    public function questMain() {
        $content = $this->get('cgi/mc_main.php');
        Notification::addIfExists($content);
        $hid = $this->get('/cgi/mc_hid.php');
        if (strpos($content, 'mc_hid.php') !== false) {
           $hid .= $this->get('/cgi/mc_hid.php');
        }
        Notification::addIfExists($hid);
        return view('labyrinth_quest', (new LabParser)->getQuest($content . $hid));
    }

    public function questReply() {
        $html = $this->post('/cgi/mc_hid.php');
        Notification::addIfExists($html);
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
