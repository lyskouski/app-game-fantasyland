<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppProxyProvider;
use App\Settings\Defines;

abstract class Controller
{
    protected AppProxyProvider $curl;

    public function __construct()
    {
        $this->curl = new AppProxyProvider();
    }

    public function generic(string $url, ?array $post = null) {
        $addr = Defines::URL;
        $data = $this->curl->boot($addr . $url, $post);
        $data = str_replace('src="../', 'src="' . $addr, $data);
        $data = str_replace('SRC="../', 'src="' . $addr, $data);
        $data = str_replace('src=../', 'src=' . $addr, $data);
        $data = str_replace('src="/', 'src="' . $addr, $data);
        $data = str_replace('src=/', 'src=' . $addr, $data);
        $data = str_replace('src="images/', 'src="' . $addr . 'images/', $data);
        $data = str_replace("BACKGROUND='../", "background='" . $addr, $data);
        $data = str_replace('BACKGROUND="../', 'background="' . $addr, $data);
        $data = str_replace('background="../', 'background="' . $addr, $data);
        $data = str_replace('target="_BLANK"', '', $data);
        $data = str_replace("window.open('help','_blank','scrollbars=yes,width=900,height=560,resizable=yes')", "document.location.href='/help/enc.php?type=menu'", $data);
        return view('generic', ['data' => $data]);
    }

    public function captcha(?string $t) {
        $t = $t ?? random_int(0, 1000000);
        $html = $this->curl->boot(Defines::URL . 'cgi/png.php?c=' . $t, null, false);
        return 'data:image/png;base64,' . base64_encode($html);
    }

    public function get(string $url, ?array $get = null) {
        return $this->post($url, $get, []);
    }

    public function post(string $url, ?array $get = null, ?array $post = null) {
        $get = $get ?? request()->input();
        $query = !empty($get) ? '?' . http_build_query($get) : '';
        $post = $post ?? request()->post();
        return $this->curl->boot(Defines::URL . $url . $query, $post);
    }
}
