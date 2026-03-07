<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class ChatController extends Controller
{
    public function index() {
        $data = $this->curl->boot($this->url . 'cgi/ch_ref.php');
        return view('empty', ['data' => $data]);
    }
}
