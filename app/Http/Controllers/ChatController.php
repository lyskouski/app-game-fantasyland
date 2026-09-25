<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\ChatParser;

final class ChatController extends Controller
{
    public function index() {
        $html = $this->get('cgi/ch_ref.php', []);
        $chout = $this->get('ch/chout.php', []);
        $w = $this->get('cgi/w.JS', []);
        $data = (new ChatParser)->parseUserList($html . $chout, $w);
        return view('chat_user_list', $data);
    }

    public function messages() {
        $html = $this->get('ch/chout.php', []);
        $me = (new ChatParser)->getOwnName($html);
        $data = Notification::orderBy('created_at', 'desc')->limit(250)->get();
        return view('chat', ['data' => $data, 'me' => $me]);
    }

    public function clear() {
        Notification::truncate();
        return redirect('/ch/chout.php');
    }

    public function send() {
        $this->get('chinp');
        return redirect('/ch/chout.php');
    }
}
