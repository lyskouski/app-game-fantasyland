<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;

final class ChatController extends Controller
{
    public function index() {
        $data = $this->get('cgi/ch_ref.php', []);
        return view('empty', ['data' => $data]);
    }

    public function messages() {
        // Purge raw <script> payloads accidentally stored by a previous ListenStream bug.
        Notification::where('message', 'like', '%<script%')->delete();
        $data = Notification::orderBy('created_at', 'desc')->limit(250)->get();
        return view('chat', ['data' => $data]);
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
