<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppProxyProvider;

class LoginController extends Controller
{
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

    public function indexRegister() {
        return view('registry');
    }

    public function register() {
        $data = request()->post();
        $data['t'] = $this->getTimestamp();
        $registerResult = $this->curl->boot('https://www.fantasyland.ru/cgi/register.php?' . http_build_query($data));
        if (strlen(trim($registerResult)) > 0 && trim($registerResult) !== 'ok') {
            return view('registry', ['error' => $registerResult]);
        }
        return view('home');
    }

    public function rules() {
        $html = $this->curl->boot('https://www.fantasyland.ru/rules.php');
        $html = str_replace('BACKGROUND="images', 'BACKGROUND="https://www.fantasyland.ru/images', $html);
        $html = str_replace('SRC="images', 'SRC="https://www.fantasyland.ru/images', $html);
        return view('generic', ['data' => $html]);
    }
}
