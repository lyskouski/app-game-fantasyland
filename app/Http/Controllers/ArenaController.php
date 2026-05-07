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
        $data = $this->mainPage();
        $htmlArena = $this->get('/cgi/arena.php');
        $parser = new ArenaParser();
        if (str_contains($htmlArena, '/cgi/train_start.php')) {
            $data['captcha'] = $this->captcha(time());
            $arena = $parser->train($htmlArena);
            return view('arena_train', [...$data, ...$arena]);
        }
        return view('main_arena', $data);
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
        $data['captcha'] = $this->captcha(time());
        $arena = $parser->train($htmlArena);
        return view('arena_train', [...$data, ...$arena]);
    }
}
