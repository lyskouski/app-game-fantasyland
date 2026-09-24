<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\ArenaParser;
use App\Services\LocationParser;
use Native\Mobile\Facades\Device;

final class ArenaController extends Controller
{
    protected function mainPage() {
        $html = $this->get('cgi/no_combat.php', []);
        $data = (new LocationParser)->onArena($html);
        $data['current'] = request()->input('g', 0);
        $data['unit_id'] = request()->input('unit_id', 0);
        return $data;
    }

    public function index() {
        $htmlArena = $this->get('/cgi/arena.php');
        if (preg_match('/DoScript\("DoExt\(\'([^\']+)\'\)/', $htmlArena, $matches)) {
            return redirect('/cgi/' . $matches[1]);
        }
        $data = $this->mainPage();
        if (!$data['current'] && preg_match('/arenaSelBtn\s*=\s*(\d+)/', $htmlArena, $matches)) {
            $data['current'] = (int)$matches[1];
        }
        $parser = new ArenaParser();
        if (str_contains($htmlArena, "id='hpLine'")) {
            $health = $parser->getHealthState($htmlArena);
            return view('arena_pause', [...request()->input(), ...$data, ...$health]);
        } else {
            $w = $this->get('cgi/w.JS', []);
            switch ($data['current']) {
                case 1: // Ring
                    $arena = $parser->getRingGroups($htmlArena, $w);
                    return view('arena_ring', [...request()->input(), ...$data, ...$arena]);
                case 2: // Mob
                    $data['captcha'] = $this->captcha(time());
                    return view('arena_mob', $data);
                case 9: // Train
                    $data['captcha'] = $this->captcha(time());
                    $arena = $parser->train($htmlArena);
                    return view('arena_train', [...$data, ...$arena]);
                default:
                    return view('main_arena', $data);
            }
        }
    }

    public function trainStart() {
        $data = $this->mainPage();
        $htmlStart = $this->get('/cgi/train_start.php');
        if (!$htmlStart || str_contains($htmlStart, 'parent.no_combat.ReloadFrame')) {
            $htmlStart = $this->get('/cgi/arena.php', ['rld' => 1]);
        }
        $parser = new ArenaParser();
        $start = $parser->timer($htmlStart);
        Notification::addIfExists($htmlStart);
        return view('arena_train_start', [...$data, ...$start]);
    }

    public function trainStop() {
        $htmlStop = $this->get('/cgi/train_stop.php');
        Notification::addIfExists($htmlStop);
        $parser = new ArenaParser();
        $data = $this->mainPage();
        if (str_contains($htmlStop, "parent.no_combat.ReloadFrame('&rws=1');")) {
            $htmlStart = $this->get('/cgi/arena.php', ['rld' => 1, 'rws' => 1]);
            $start = $parser->timer($htmlStart);
            return view('arena_train_start', [...$data, ...$start]);
        }
        Device::vibrate();
        $htmlArena = $this->get('/cgi/arena.php', ['g' => $data['current']]);
        $arena = $parser->train($htmlArena);
        if (sizeof($arena['train']) > 0) {
            $data['captcha'] = $this->captcha(time());
        }
        return view('arena_train', [...$data, ...$arena]);
    }

    public function attackMob() {
        $html = $this->get('/cgi/attack_mob.php');
        Notification::addIfExists($html);
        if (str_contains($html, "location.href = 'combat.php'")) {
            return redirect('/cgi/combat.php');
        }
        return redirect('/cgi/arena.php');
    }
}
