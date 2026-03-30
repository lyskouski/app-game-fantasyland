<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\LabParser;

class LabController extends Controller
{
    public function move() {
        $content = '';
        $pattern = '/<script[^>]*>(.*?)<\/script>/is';
        $move = $this->get('cgi/maze_move.php');
        if (preg_match($pattern, $move, $matches)) {
            $content .= $matches[1];
        }
        $html = $this->get('cgi/maze_ref.php', []);
        if (preg_match($pattern, $html, $matches)) {
            $content .= $matches[1];
        }
        return view('empty', ['data' => $content]);
    }

    public function questAction() {
        $activate = $this->get('cgi/maze_qaction.php');
        if (strpos($activate, "location.href='no_combat.php'") !== false) {
            //$this->get('/cgi/no_combat.php', []);
            //$this->get('/cgi/mc_main.php', []);
            $html = $this->get('/cgi/mc_hid.php', []);

            return view('labyrinth_quest', (new LabParser)->getQuest($html));
        }
        return view('empty', ['data' => $activate]);
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
