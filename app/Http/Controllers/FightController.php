<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\FightParser;

final class FightController extends Controller
{
    public function combat() {
        $this->get('/cgi/combat.php');
        $parser = new FightParser();
        $htmlArmy = $this->get('/cgi/armylist_yours.php', []);
        return view('combat', [
            'scrolls' => $parser->getScrolls($htmlArmy),
            'army' => $parser->getArmy($htmlArmy),
        ]);
    }

    public function armylistYours() {
        $result = $this->get('/cgi/armylist_yours.php', []);
        return view('empty', ['data' => $result]);
    }

    public function armylistEnemy() {
        $result = $this->get('/cgi/armylist_enemy.php', []);
        return view('empty', ['data' => $result]);
    }

    public function combatPanel() {
        $result = $this->get('/cgi/combat_panel.php', []);
        return view('empty', ['data' => $result]);
    }

    public function combatRef() {
        $result = $this->get('/cgi/combat_ref.php');
        return view('empty', ['data' => $result]);
    }

    public function addScroll() {
        $result = $this->get('/cgi/combat_scroll_ins.php');
        return view('empty', ['data' => $result]);
    }

    public function addArmy() {
        $result = $this->get('/cgi/combat_ins.php');
        return view('empty', ['data' => $result]);
    }

    public function leaveCombat() {
        $this->get('/cgi/leave_combat.php');
        return redirect('/cgi/no_combat.php');
    }
}
