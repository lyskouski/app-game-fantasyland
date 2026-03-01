<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppProxyProvider;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected AppProxyProvider $curl;

    public function __construct()
    {
        $this->curl = new AppProxyProvider();
    }

    public function index() {
        $html = $this->curl->boot('https://www.fantasyland.ru');
        preg_match('/guestlogin\.php\?t=([a-z0-9]+)/i', $html, $matches);
        $t = $matches[1] ?? null;
        return view('login', ['timestamp' => $t]);
    }

    public function login() {
        $data = request()->only(['login', 'password']);
        $loginResult = $this->curl->boot('https://www.fantasyland.ru/login.php', $data);
        if (preg_match("#<FONT COLOR='\\#FF0000'>(.*?)</FONT>#is", $loginResult, $matches)) {
            $match = $matches[1];
            return view('login', ['error' => $match]);
        }
        $this->curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('main');
    }

    public function guestLogin() {
        $data = request()->only(['t']);
        $this->curl->boot('https://www.fantasyland.ru/guestlogin.php?t=' . $data['t']);
        $this->curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('main');
    }
}
