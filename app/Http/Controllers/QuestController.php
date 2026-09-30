<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\QuestParser;

final class QuestController extends Controller
{
    public function index() {
        $content = $this->get('cgi/mc_main.php');
        Notification::addIfExists($content);
        $hid = $this->get('/cgi/mc_hid.php');
        if (str_contains($content, 'mc_hid.php')) {
           $hid .= $this->get('/cgi/mc_hid.php');
        }
        Notification::addIfExists($hid);
        return view('quest', (new QuestParser)->getQuest($content . $hid));
    }

    public function action() {
        $content = $this->get('cgi/maze_qaction.php');
        Notification::addIfExists($content);
        return view('empty', ['data' => $content]);
    }

    public function reply() {
        $html = $this->post('/cgi/mc_hid.php');
        Notification::addIfExists($html);
        if (str_contains($html, 'location.href="no_combat.php"')) {
            return redirect('/cgi/no_combat.php');
        }
        return view('quest', (new QuestParser)->getQuest($html));
    }
}
