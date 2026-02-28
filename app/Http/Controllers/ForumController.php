<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class ForumController extends Controller
{
    public function index() {
        return $this->get('cgi/forum_rooms.php');
    }

    public function room() {
        $data = request()->all();
        return $this->get('cgi/forum.php?' . http_build_query($data));
    }

    public function topic() {
        $data = request()->all();
        return $this->get('cgi/f_show_thread.php?' . http_build_query($data));
    }
}
