<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\LabParser;

class LabController extends Controller
{
    public function move() {
        $content = $this->get('cgi/maze_move.php');
        $content .= $this->get('cgi/maze_ref.php', []);
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
        $content .= $this->get('/cgi/mc_hid.php');
        return view('labyrinth_quest', (new LabParser)->getQuest($content));
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
