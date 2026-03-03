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

    private function getTimestamp(): ?string
    {
        $html = $this->curl->boot('https://www.fantasyland.ru');
        preg_match('/guestlogin\.php\?t=([a-z0-9]+)/i', $html, $matches);
        return $matches[1] ?? null;
    }

    public function index() {
        return view('login', ['timestamp' => $this->getTimestamp()]);
    }

    public function login() {
        $data = request()->only(['login', 'password']);
        $loginResult = $this->curl->boot('https://www.fantasyland.ru/login.php', $data);
        if (preg_match("#<FONT COLOR='\\#FF0000'>(.*?)</FONT>#is", $loginResult, $matches)) {
            $match = $matches[1];
            return view('login', ['error' => $match, 'timestamp' => $this->getTimestamp()]);
        }
        $this->curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('home');
    }

    public function guestLogin() {
        $data = request()->only(['t']);
        $this->curl->boot('https://www.fantasyland.ru/guestlogin.php?t=' . $data['t']);
        $this->curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('home');
    }

    public function register() { // TBD
        $data = request()->post();
        $registerResult = $this->curl->boot('https://www.fantasyland.ru/cgi/register.php?' . http_build_query($data));
        $reg = '#<div id="alertMsg" style="color:red; font-weight: bold; padding-bottom: 12px; width:290px">(.*?)</div>#is';
        if (preg_match($reg, $registerResult, $matches)) {
            $match = $matches[1];
            return view('registry', ['error' => $match]);
        }
        return view('home');
    }
}
