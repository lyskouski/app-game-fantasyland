<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;

class ChatController extends Controller
{
    public function index() {
        $data = $this->get('cgi/ch_ref.php', []);
        return view('empty', ['data' => $data]);
    }

    public function messages() {
        $data = Notification::orderBy('created_at', 'desc')->limit(250)->get();
        return view('chat', ['data' => $data]);
    }
}
