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
        $html = $this->get('/cgi/arena.php');
        if (preg_match('/DoScript\("DoExt\(\'([^\']+)\'\)/', $html, $matches)) {
            return redirect('/cgi/' . $matches[1]);
        }
        if (preg_match('/parent\.document\.location\.href\s*=\s*\'([^\']+)\'/', $html, $matches)) {
            return redirect($matches[1]);
        }
        if (preg_match('/window\.top\.loc\.location\.href\s*=\s*\'([^\']+)\'/', $html, $matches)) {
            return redirect('/cgi/' . $matches[1]);
        }
        $data = $this->mainPage();
        if (!$data['current'] && preg_match('/arenaSelBtn\s*=\s*(\d+)/', $html, $matches)) {
            $data['current'] = (int)$matches[1];
        }
        $parser = new ArenaParser();
        if (str_contains($html, "id='hpLine'")) {
            $health = $parser->getHealthState($html);
            return view('arena_pause', [...$data, ...$health]);
        } elseif (str_contains($html, 'train_stop.php')) {
            return redirect('/cgi/train_start.php');
        }
        $w = $this->get('cgi/w.JS', []);
        switch ($data['current']) {
            case 1: // Ring
                $arena = $parser->getRingGroups($html, $w);
                return view('arena_ring', [...$data, ...$arena]);
            case 2: // Mob
                $data['captcha'] = $this->captcha(time());
                return view('arena_mob', $data);
            case 4: // Group
                $group = $parser->getGroupGroups($html, $w);
                return view('arena_group', [...$data, ...$group]);
            case 6: // Chaos
                $chaos = $parser->getChaosGroups($html, $w);
                return view('arena_chaos', [...$data, ...$chaos]);
            case 9: // Train
                $data['captcha'] = $this->captcha(time());
                $arena = $parser->train($html);
                return view('arena_train', [...$data, ...$arena]);
            default:
                return view('main_arena', $data);
        }
    }

    public function trainStart() {
        $data = $this->mainPage();
        $html = $this->get('/cgi/train_start.php');
        if (!$html || str_contains($html, 'parent.no_combat.ReloadFrame')) {
            $html = $this->get('/cgi/arena.php', ['rld' => 1]);
        }
        $parser = new ArenaParser();
        $start = $parser->timer($html);
        Notification::addIfExists($html);
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
        if (preg_match('/unit_id_moo\s*=\s*(\d+)/', $htmlStop, $m)) {
            $data['unit_id'] = (int) $m[1];
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
