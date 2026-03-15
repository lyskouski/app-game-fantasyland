<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\InfoParser;

class InfoController extends Controller
{
    public function index() {
        $html = $this->curl->boot($this->url . '/cgi/show_info.php');
        return view('info', (new InfoParser)->options($html));
    }

    public function indexPost(?array $post = null) {
        if ($post === null) {
            $post = request()->post();
        }
        return $this->get('/cgi/change_info.php', $post);
    }
}
