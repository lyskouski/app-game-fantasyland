<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\ArenaParser;

final class FightController extends Controller
{
    public function combat() {
        $this->get('/cgi/combat.php');
        $htmlArmy = $this->get('/cgi/armylist_yours.php', []);
        $htmlEnemy = $this->get('/cgi/armylist_enemy.php', []);
        $htmlPanel = $this->get('/cgi/combat_panel.php', []);
        $parser = new ArenaParser();

        return view('combat', []);
    }
}
