<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\SecureStorage;

class LoginController extends Controller
{
    private function getTimestamp(): ?string
    {
        $html = $this->curl->boot($this->url);
        preg_match('/guestlogin\.php\?t=([a-z0-9]+)/i', $html, $matches);
        return $matches[1] ?? null;
    }

    public function index() {
        return view('login', [
            'timestamp' => $this->getTimestamp(),
            'login' => SecureStorage::get('login'),
            'password' => SecureStorage::get('password'),
        ]);
    }

    public function login() {
        $data = request()->only(['login', 'password']);
        $loginResult = $this->curl->boot($this->url . 'login.php', $data);
        if (preg_match("#<FONT COLOR='\\#FF0000'>(.*?)</FONT>#is", $loginResult, $matches)) {
            $match = $matches[1];
            return view('login', [
                'error' => $match,
                'timestamp' => $this->getTimestamp(),
                'login' => '',
                'password' => '',
            ]);
        }
        $this->curl->boot($this->url . 'ch/chch.php');
        $opt = request()->only(['save']);
        if (isset($opt['save']) && $opt['save']) {
            SecureStorage::set('login', $data['login']);
            SecureStorage::set('password', $data['password']);
        }
        return view('home');
    }

    public function guestLogin() {
        $data = request()->only(['t']);
        $this->curl->boot($this->url . 'guestlogin.php?t=' . $data['t']);
        $this->curl->boot($this->url . 'ch/chch.php');
        return view('home');
    }

    public function indexRegister() {
        return view('registry');
    }

    public function register() {
        $data = request()->post();
        $data['t'] = $this->getTimestamp();
        $registerResult = $this->curl->boot($this->url . 'cgi/register.php?' . http_build_query($data));
        if (strlen(trim($registerResult)) > 0 && trim($registerResult) !== 'ok') {
            return view('registry', ['error' => $registerResult]);
        }
        return view('home');
    }

    public function rules() {
        $html = $this->curl->boot($this->url . 'rules.php');
        $html = str_replace('BACKGROUND="images', 'BACKGROUND="' . $this->url . 'images', $html);
        $html = str_replace('SRC="images', 'SRC="' . $this->url . 'images', $html);
        return view('generic', ['data' => $html]);
    }
}
