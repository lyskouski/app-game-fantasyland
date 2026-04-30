<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\ArenaParser;
use App\Services\LocationParser;

class ArenaController extends Controller
{
    public function index() {
        $html = $this->get('cgi/no_combat.php', []);
        $data = (new LocationParser)->onArena($html);
        $data['current'] = request()->input('g', 0);
        $htmlArena = $this->get('/cgi/arena.php');
        $parser = new ArenaParser();
        if (strpos($htmlArena, '/cgi/train_start.php') !== false) {
            $data['captcha'] = $this->captcha(time());
            $arena = $parser->train($htmlArena);
            return view('arena_train', [...$data, ...$arena]);
        } else {
            return view('main_arena', $data);
        }
    }
}
