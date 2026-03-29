<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

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
}
